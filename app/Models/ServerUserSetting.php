<?php

namespace App\Models;

use App\Models\Base\ServerUserSetting as BaseServerUserSetting;

class ServerUserSetting extends BaseServerUserSetting
{
	protected $fillable = [
		'server_id',
		'user_id',
		'nickname',
		'muted',
		'notifications_level'
	];
}
