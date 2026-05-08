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
        $conversationIds = GroupChatConversationParticipant::where('user_id', $userId)
            ->whereHas('conversation', function ($query) {
                $query->where('type_conversation', 'private');
            })
            ->pluck('conversation_id');

        // Obtenemos a los otros participantes de esas conversaciones
        $otherParticipants = GroupChatConversationParticipant::whereIn('conversation_id', $conversationIds)
            ->whereNot('user_id', $userId)
            ->get()
            ->select('id', 'conversation_id', 'user_id', 'username')
            ->keyBy('conversation_id');

        $chats = [];

        foreach ($conversationIds as $convId) {

            $chats[] = $otherParticipants->get($convId);
        }

        return response()->json($chats, 200);
    }

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
                //'path_cover_image'
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
