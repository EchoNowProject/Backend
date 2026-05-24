<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Models\ServerChatConversation;
use Illuminate\Http\Request;

class ServerChatsController extends Controller
{

    /**
     * Funcion que carga los mensajes de un grupo con las refrencias de los archivos en caso de que contengan
     * @param Request $request
     */
    public function getServerMessages(Request $request)
    {
        //return 'hola';

        $conversationId = (int) $request->query('conversation_id');

        $conversation = ServerChatConversation::where('id_server', (int) $request->query('server_id'))
            ->where('id', $conversationId)
            /* ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            }) */
            ->with('messages.filesMessage')
            ->first();

        if (!$conversation) {
            return response()->json([], 404);
        }

        $messages = $conversation->messages;
        $conversation->unsetRelation('messages');

        return response()->json(['messages' => $messages, 'conversation' => $conversation], 200);
    }
}
