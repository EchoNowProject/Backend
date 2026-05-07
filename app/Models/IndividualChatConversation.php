<?php

namespace App\Models;

use App\Models\Base\IndividualChatConversation as BaseIndividualChatConversation;

class IndividualChatConversation extends BaseIndividualChatConversation
{
	protected $fillable = [
		'type_conversation'
	];

	public function participants()
	{
		return $this->belongsToMany(User::class, 'individual_chat_conversation_participants', 'conversation_id', 'user_id');
	}

	public function messages()
	{
		return $this->hasMany(IndividualChatMessage::class, 'conversation_id', 'id')->orderBy('id', 'asc');
	}
}
