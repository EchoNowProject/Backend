<?php

namespace App\Models;

use App\Models\Base\GroupChatConversation as BaseGroupChatConversation;
use Illuminate\Support\Facades\Auth;

class GroupChatConversation extends BaseGroupChatConversation
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
            ->get()
            ->select('id', 'conversation_id', 'user_id', 'username')
            ->keyBy('conversation_id');

        $chats = [];

        foreach ($conversationIds as $convId) {

            $chats[] = $otherParticipants->get($convId);
        }

        return response()->json($chats, 200);
    }
}
