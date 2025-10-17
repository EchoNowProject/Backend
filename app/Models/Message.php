<?php

namespace App\Models;

use App\Models\Base\Message as BaseMessage;

class Message extends BaseMessage
{
	protected $fillable = [
		'conversation_id',
		'user_sender_id',
		'content',
		'type',
		'file_url',
		'read_by'
	];
}
