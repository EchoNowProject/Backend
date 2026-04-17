<?php

namespace App\Models;

use App\Models\Base\Channel as BaseChannel;

class Channel extends BaseChannel
{
	protected $fillable = [
		'server_id',
		'name',
		'type_channels',
		'permissions'
	];
}
