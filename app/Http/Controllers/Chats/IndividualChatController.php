<?php

namespace App\Http\Controllers\Chats;

use App\Actions\Files\UpdateFile;
use App\Events\IndividualChatEvent;
use App\Http\Controllers\Controller;
use App\Models\IndividualChatConversation;
use App\Models\IndividualChatConversationParticipant;
use App\Models\IndividualChatMessage;
use App\Models\IndividualChatMessagesFile;
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
        $conversationIds = IndividualChatConversationParticipant::where('user_id', $userId)
            ->whereHas('conversation', function ($query) {
                $query->where('type_conversation', 'private');
            })
            ->pluck('conversation_id');

        // Obtenemos a los otros participantes de esas conversaciones
        $otherParticipants = IndividualChatConversationParticipant::whereIn('conversation_id', $conversationIds)
            ->where('user_id', '!=', $userId)
            ->select('id', 'conversation_id', 'user_id', 'username')
            ->get()
            ->keyBy('conversation_id');

        $chats = [];

        foreach ($conversationIds as $convId) {
            $participant = $otherParticipants->get($convId);
            if ($participant) {
                $chats[] = $participant;
            }
        }

        return response()->json($chats, 200);
    }

    public function sendMessage(Request $request)
    {
        // ! Comprobar que todavoa son amigos
        // ! Terminar

        $conversation = IndividualChatConversation::where('id', $request->data['conversationId'])
            ->where('type_conversation', 'private')
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found'], 404);
        }

        $message = IndividualChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_sender_id' => Auth::id(),
            'content' => $request->data['message'] ?? null,
            'has_file' => $request->data['files'] != null ? true : false,
            'type_msg' => IndividualChatMessage::setTypeMessage($request->data['message'], $request->data['files']),
        ]);

        if ($request->data['files'] != null) {
            $fileSaved = $this->uploadFiles($request->data['files'], $conversation->id);

            foreach ($fileSaved as $file) {
                if ($file['success'] == true)
                    IndividualChatMessagesFile::create([
                        'message_id' => $message->id,
                        'file_name' => $file['file_name'],
                        'path_file' => $file['path'],
                    ]);
            }

            // Cargamos la relación para que el frontend reciba los archivos adjuntos
            $message->load('filesMessage');
        }

        $friendId = IndividualChatConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', Auth::id())
            ->first()
            ->user_id;

        // Se lanza evento al websocket
        broadcast(new IndividualChatEvent($message, $friendId))->toOthers();

        return response()->json($message, 200);
    }

    /**
     * Funcion que recoge los mensajes anteriores en un chat individual
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserMessages(Request $request)
    {

        $conversationId = $request->query('conversation_id');

        $conversation = IndividualChatConversation::where('id', $conversationId)
            ->where('type_conversation', 'private')
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('messages.filesMessage')
            ->first();

        if (!$conversation) {
            return response()->json([
                'messages' => [],
                'userInvolved' => null,
            ], 404);
        }

        // Recogemos el usuario implicado en la relacion
        $userInvolved = IndividualChatConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', Auth::id())
            ->first();

        return response()->json([
            'messages' => $conversation->messages,
            'userInvolved' => $userInvolved,
        ], 200);
    }

    public function createConversationIfNeccesary(Request $request)
    {
        $conversation = IndividualChatConversation::where('type_conversation', 'private')
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('participants', function ($query) use ($request) {
                $query->where('user_id', $request->data['friendId']);
            })
            ->first();

        if (!$conversation) {

            $participants = [Auth::id(), $request->data['friendId']];

            $conversation = IndividualChatConversation::create([
                'type_conversation' => 'private',
            ]);

            foreach ($participants as $idParticipant) {

                $user = User::findOrFail($idParticipant);

                IndividualChatConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $idParticipant,
                    'username' => $user->username,
                    'joined_at' => Carbon::now(),
                    'avatar_image' => $user->avatar_img ?? null,
                ]);
            }
        }

        return response()->json($conversation, 200);
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
