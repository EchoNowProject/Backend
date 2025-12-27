<?php

namespace App\Models;

use App\Models\Base\ChannelUserSetting as BaseChannelUserSetting;

class ChannelUserSetting extends BaseChannelUserSetting
{
	protected $fillable = [
		'channel_id',
		'user_id',
		'muted',
		'notification_level'
	];
}
