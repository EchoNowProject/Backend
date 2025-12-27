<?php

namespace App\Models;

use App\Models\Base\StatusUser as BaseStatusUser;

class StatusUser extends BaseStatusUser
{
	protected $fillable = [
		'name'
	];
}
