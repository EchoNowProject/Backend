<?php

namespace App\Models;

use App\Models\Base\Conversation as BaseConversation;

class Conversation extends BaseConversation
{
	protected $fillable = [
		'channel_id',
		'type_conversation'
	];
}
