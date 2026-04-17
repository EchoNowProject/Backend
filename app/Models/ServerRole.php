<?php

namespace App\Models;

use App\Models\Base\ServerRole as BaseServerRole;

class ServerRole extends BaseServerRole
{
	protected $fillable = [
		'server_id',
		'name',
		'permissions'
	];
}
