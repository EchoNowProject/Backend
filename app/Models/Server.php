<?php

namespace App\Models;

use App\Models\Base\Server as BaseServer;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Server extends BaseServer
{
	protected $fillable = [
		'name',
		'description',
		'avatar_img',
		'owner_id',
		'invitation_code',
		'type_server',
	];

	protected static function boot(): void
	{

		parent::boot();

		static::creating(function ($server) {

			$savedServer = Server::where('owner_id', Auth::id())
				->where('name', $server->name)
				->exists();

			if ($savedServer) {
				throw new Exception("El servidor que intentas crear ya existe");
			}
		});
	}
}
