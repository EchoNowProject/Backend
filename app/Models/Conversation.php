<?php

namespace App\Models;

use App\Models\Base\Conversation as BaseConversation;

class Conversation extends BaseConversation
{
	protected $fillable = [
		'type_conversation'
	];

	public function participants()
	{
		return $this->belongsToMany(User::class, 'conversation_participants');
	}

	public function messages()
	{
		return $this->hasMany(Message::class, 'conversation_id', 'id')->orderBy('id', 'asc');
	}
}
