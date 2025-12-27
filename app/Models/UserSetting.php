<?php

namespace App\Models;

use App\Models\Base\UserSetting as BaseUserSetting;

class UserSetting extends BaseUserSetting
{
	protected $fillable = [
		'user_id',
		'theme',
		'notifications_enable',
		'sound_enable',
		'volume'
	];
}
