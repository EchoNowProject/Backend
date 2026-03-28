<?php

namespace App\Models;

use App\Models\Base\UserNotificationSetting as BaseUserNotificationSetting;

class UserNotificationSetting extends BaseUserNotificationSetting
{

	protected $primaryKey = 'user_id';

	protected $fillable = [
		'email_notifications',
		'push_notifications',
		'notify_friend_requests',
		'sound_enabled',
		'show_message_preview',
		'notify_direct_messages',
		'notify_mentions'
	];
}
