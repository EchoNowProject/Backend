<?php

namespace App\Models;

use App\Models\Base\ServerChatMessage as BaseServerChatMessage;

class ServerChatMessage extends BaseServerChatMessage
{
	protected $fillable = [
		'conversation_id',
		'user_sender_id',
		'user_sender_name',
		'content',
		'type_msg',
		'has_file',
		'read_by'
	];
}
