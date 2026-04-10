<?php

namespace App\Models;

use App\Models\Base\UserAlert as BaseUserAlert;

class UserAlert extends BaseUserAlert
{
	protected $fillable = [
		'source_user_id',
		'target_user_id',
		'type',
		'message'
	];
}
