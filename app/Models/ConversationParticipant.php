<?php

namespace App\Models;

use App\Models\Base\ConversationParticipant as BaseConversationParticipant;

class ConversationParticipant extends BaseConversationParticipant
{
	protected $fillable = [
		'conversation_id',
		'user_id',
		'participant_role',
		'joined_at'
	];
}
