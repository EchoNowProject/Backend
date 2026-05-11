<?php

namespace App\Models;

use App\Models\Base\GroupChatConversation as BaseGroupChatConversation;

class GroupChatConversation extends BaseGroupChatConversation
{
    protected $fillable = [
        'group_name',
        'description',
        'path_cover_image',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'group_chat_conversation_participants', 'conversation_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(GroupChatMessage::class, 'conversation_id', 'id')->orderBy('id', 'asc');
    }
}
