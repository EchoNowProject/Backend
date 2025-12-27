<?php

namespace App\Models;

use App\Models\Base\Message as BaseMessage;

class Message extends BaseMessage
{
	protected $fillable = [
		'conversation_id',
		'user_sender_id',
		'content',
		'type_msg',
		'file',
		'read_by'
	];
}
