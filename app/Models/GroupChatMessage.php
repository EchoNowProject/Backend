<?php

namespace App\Models;

use App\Models\Base\GroupChatMessage as BaseGroupChatMessage;

class GroupChatMessage extends BaseGroupChatMessage
{
	protected $fillable = [
		'conversation_id',
		'user_sender_id',
		'content',
		'type_msg',
		'has_file',
		'read_by'
	];
}
