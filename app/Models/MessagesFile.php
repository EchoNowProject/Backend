<?php

namespace App\Models;

use App\Models\Base\MessagesFile as BaseMessagesFile;

class MessagesFile extends BaseMessagesFile
{
	protected $fillable = [
		'message_id',
		'file_name',
		'path_file',
	];
}
