<?php

namespace App\Models;

use App\Models\Base\GroupChatMessagesFile as BaseGroupChatMessagesFile;

class GroupChatMessagesFile extends BaseGroupChatMessagesFile
{
	protected $fillable = [
		'message_id',
		'file_name',
		'path_file'
	];
}
