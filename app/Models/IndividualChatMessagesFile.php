<?php

namespace App\Models;

use App\Models\Base\IndividualChatMessagesFile as BaseIndividualChatMessagesFile;

class IndividualChatMessagesFile extends BaseIndividualChatMessagesFile
{
	protected $fillable = [
		'message_id',
		'file_name',
		'path_file'
	];
}
