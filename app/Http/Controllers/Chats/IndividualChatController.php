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

    public function getIntividualChats()
    {
        $userId = Auth::id();

        // Obtenemos los IDs de las conversaciones privadas en las que participa el usuario
        $conversationIds = ConversationParticipant::where('user_id', $userId)
            ->whereHas('conversation', function ($query) {
                $query->where('type_conversation', 'private');
            })
            ->pluck('conversation_id');

        // Obtenemos a los otros participantes de esas conversaciones
        $otherParticipants = ConversationParticipant::whereIn('conversation_id', $conversationIds)
            ->where('user_id', '!=', $userId)
            ->get()
            ->select('id', 'conversation_id', 'user_id', 'username')
            ->keyBy('conversation_id');

        $chats = [];

        foreach ($conversationIds as $convId) {

            $chats[] = $otherParticipants->get($convId);
        }

        return response()->json($chats, 200);
    }

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
                    'username' => $user->username,
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

            // Cargamos la relación para que el frontend reciba los archivos adjuntos
            $message->load('filesMessage');
        }

        // Se lanza evento al websocket
        broadcast(new IndividualChatEvent($message, $request->data['friendId']))->toOthers();

        return response()->json($message, 200);
    }

    /**
     * Funcion que recoge los mensajes anteriores en un chat individual
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserMessages(Request $request)
    {

        $userTarget = $request->query('userTarget');

        $conversation = Conversation::where('type_conversation', 'private')
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('participants', function ($query) use ($userTarget) {
                $query->where('user_id', $userTarget);
            })
            ->with('messages.filesMessage')
            ->first();

        if (!$conversation) {
            $userTargetModel = User::find($userTarget);

            return response()->json([
                'messages' => [],
                'userInvolved' => $userTargetModel ? [
                    'user_id' => $userTargetModel->id,
                    'username' => $userTargetModel->username,
                    'avatar_image' => $userTargetModel->avatar_img ?? null,
                ] : null,
            ], 200);
        }

        // Recogemos el usuario implicado en la relacion
        $userInvolved = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $userTarget)
            ->first();

        return response()->json([
            'messages' => $conversation->messages,
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
