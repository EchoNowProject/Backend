<?php

namespace App\Models;

use App\Models\Base\ServerChatMessagesFile as BaseServerChatMessagesFile;

class ServerChatMessagesFile extends BaseServerChatMessagesFile
{
	protected $fillable = [
		'message_id',
		'file_name',
		'path_file'
	];
}
