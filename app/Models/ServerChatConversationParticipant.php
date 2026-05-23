<?php

namespace App\Models;

use App\Models\Base\ServerChatConversationParticipant as BaseServerChatConversationParticipant;

class ServerChatConversationParticipant extends BaseServerChatConversationParticipant
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
}
