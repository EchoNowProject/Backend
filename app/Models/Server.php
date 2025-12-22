<?php

namespace App\Models;

use App\Models\Base\Server as BaseServer;

class Server extends BaseServer
{
	protected $fillable = [
		'name',
		'description',
		'avatar_img',
		'owner_id'
	];
}
