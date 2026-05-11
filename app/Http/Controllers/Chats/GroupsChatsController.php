<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Models\GroupChatConversation;
use App\Models\GroupChatConversationParticipant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsChatsController extends Controller
{
    /**
     * Funcion que recoje los chats activos de los grupos para mostrarlos en el Home
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupsChats()
    {

        $userId = Auth::id();

        // Obtenemos los IDs de las conversaciones privadas en las que participa el usuario
        $conversations = GroupChatConversationParticipant::where('user_id', $userId)->with('conversation')->get()->pluck('conversation');


        return response()->json($conversations, 200);
    }

    /**
     * Funcion que carga los mensajes de un grupo con las refrencias de los archivos en caso de que contengan
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupMessages(Request $request)
    {

        $conversationId = (int) $request->query('conversation_id');

        $conversation = GroupChatConversation::where('id', $conversationId)
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('messages.filesMessage')
            ->first();

        if (!$conversation) {
            return response()->json([], 404);
        }

        return response()->json($conversation->messages, 200);
    }

    /**
     * Funcion que crea una Conversacion para un grupo en caso de no tenerla
     * @param Request $request
     * @return GroupChatConversation|object
     */
    public function createConversationIfNeccesary(Request $request)
    {

        $friendsIds = $request->data['friendsIds'];
        $participantsIds = array_merge([Auth::id()], $friendsIds);

        $conversation = GroupChatConversation::where('group_name', $request->data['groupName'])->
            whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })->whereHas('participants', function ($query) use ($friendsIds) {
                $query->whereIn('user_id', $friendsIds);
            })->first();

        if (!$conversation) {

            $conversation = GroupChatConversation::create([
                'group_name' => $request->data['groupName'],
            ]);

            foreach ($participantsIds as $participantId) {

                $user = User::findOrFail($participantId);

                GroupChatConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $participantId,
                    'username' => $user->username,
                    'joined_at' => Carbon::now(),
                    'avatar_image' => $user->avatar_img ?? null,
                ]);
            }
        }

        return $conversation;
    }


}
