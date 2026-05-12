<?php

namespace App\Models;

use App\Models\Base\ServerChatConversation as BaseServerChatConversation;

class ServerChatConversation extends BaseServerChatConversation
{
	protected $fillable = [
		'group_name',
		'description',
		'path_cover_image'
	];
}
