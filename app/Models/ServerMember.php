<?php

namespace App\Models;

use App\Models\Base\ServerMember as BaseServerMember;

class ServerMember extends BaseServerMember
{
	protected $fillable = [
		'server_id',
		'user_id',
		'role_id'
	];
}
