<?php

namespace App\Models;

use App\Models\Base\ServerChatConversation as BaseServerChatConversation;

class ServerChatConversation extends BaseServerChatConversation
{
	protected $fillable = [
		'id_server',
		'channel_text_name',
		'description',
	];
}
