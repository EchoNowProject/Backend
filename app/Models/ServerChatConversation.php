<?php

namespace App\Models;

use App\Models\Base\ServerChatConversation as BaseServerChatConversation;

class ServerChatConversation extends BaseServerChatConversation
{
	protected $fillable = [
		'id_server',
		'channel_text_name',
		'description',
		'is_main',
	];

	public function participants()
	{
		return $this->belongsToMany(User::class, 'server_chat_conversation_participants', 'conversation_id', 'user_id');
	}

	public function messages()
	{
		return $this->hasMany(ServerChatMessage::class, 'conversation_id', 'id')->orderBy('id', 'asc');
	}
}
