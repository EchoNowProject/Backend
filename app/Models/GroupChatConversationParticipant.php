<?php

namespace App\Models;

use App\Models\Base\GroupChatConversationParticipant as BaseGroupChatConversationParticipant;

class GroupChatConversationParticipant extends BaseGroupChatConversationParticipant
{
	protected $fillable = [
		'conversation_id',
		'user_id',
		'participant_role',
		'username',
		'last_read_at',
		'avatar_image',
		'joined_at'
	];

	public function conversation()
	{
		return $this->hasOne(GroupChatConversation::class, 'id', 'conversation_id');
	}
}
