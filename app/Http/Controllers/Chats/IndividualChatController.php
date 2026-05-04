<?php

namespace App\Http\Controllers\Chats;

use App\Actions\Files\UpdateFile;
use App\Events\IndividualChatEvent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\MessagesFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndividualChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // ! Comprobar que todavoa son amigos
        // ! Terminar

        $conversation = Conversation::where('type_conversation', 'private')
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('participants', function ($query) use ($request) {
                $query->where('user_id', $request->data['friendId']);
            })
            ->first();

        if (!$conversation) {

            $participants = [Auth::id(), $request->data['friendId']];

            $conversation = Conversation::create([
                'type_conversation' => 'private',
            ]);

            foreach ($participants as $idParticipant) {

                $user = User::findOrFail($idParticipant);

                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $idParticipant,
                    'joined_at' => Carbon::now(),
                    'avatar_image' => $user->avatar_img ?? null,
                ]);
            }
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_sender_id' => Auth::id(),
            'content' => $request->data['message'] ?? null,
            'has_file' => $request->data['files'] != null ? true : false,
            'type_msg' => Message::setTypeMessage($request->data['message'], $request->data['files']),
        ]);

        if ($request->data['files'] != null) {
            $fileSaved = $this->uploadFiles($request->data['files'], $conversation->id);

            foreach ($fileSaved as $file) {
                if ($file['success'] == true)
                    MessagesFile::create([
                        'message_id' => $message->id,
                        'file_name' => $file['file_name'],
                        'path_file' => $file['path'],
                    ]);

            }
        }

        // Se lanza evento al websocket
        broadcast(new IndividualChatEvent($message, $request->data['friendId']))->toOthers();

        return response()->json($message, 200);
    }

    /**
     * Funcion que recoge los mensajes anteriores en un chat individual
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserMessages()
    {
        $conversationParticipants = ConversationParticipant::where('user_id', Auth::id())
            ->with('conversation.messages.filesMessage')
            ->first();

        // Recogemos el usuario implicado en la relacion
        $userInvolved = ConversationParticipant::where('conversation_id', $conversationParticipants->conversation_id)->whereNot('user_id', Auth::id())->first();

        // $usersInvolved = ConversationParticipant::where('conversation_id', $conversationParticipants->conversation_id)->whereNot('conversation_id', Auth::id())->with('user')->first()->pluck('user');

        return response()->json([
            'messages' => $conversationParticipants->conversation->messages,
            'userInvolved' => $userInvolved,
        ], 200);
    }

    //-----------------------------Funciones privadas-----------------------------

    private function uploadFiles(array $files, int $conversationId): array
    {
        $filesSaved = [];

        foreach ($files as $file) {
            $action = new UpdateFile();
            $fileData = $action->update($file['base64'], "/messages/$conversationId/", $file['name']);

            array_push($filesSaved, $fileData);
        }

        return $filesSaved;
    }
}
