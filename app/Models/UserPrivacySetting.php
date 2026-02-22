<?php

namespace App\Models;

use App\Models\Base\UserPrivacySetting as BaseUserPrivacySetting;

class UserPrivacySetting extends BaseUserPrivacySetting
{
	protected $fillable = [
		'user_id',
		'friend_request_permission',
		'direct_message_permission',
		'allow_search_by_email',
		'allow_search_by_phone',
		'show_online_status',
		'show_activity'
	];
}
