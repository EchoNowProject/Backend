<?php

namespace App\Models;

use App\Models\Base\Server as BaseServer;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Server extends BaseServer
{
	const IMAGESERVERPATH = "/servers/";

	protected $fillable = [
		'name',
		'description',
		'avatar_img',
		'owner_id',
		'invitation_code',
		'type_server',
	];

	protected $appends = ['file_avatar_image'];

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

	public function mainConversation()
	{
		return $this->hasOne(ServerChatConversation::class, 'id_server', 'id')->where('is_main', true);
	}

	public function conversations()
	{
		return $this->hasMany(ServerChatConversation::class, 'id_server', 'id')->where('is_main', false); // Es posible que lo necesitemos sin el flag en algunos casos
	}

	public function allConversations()
	{
		return $this->hasMany(ServerChatConversation::class, 'id_server', 'id');
	}

	public function participants()
	{
		return $this->belongsToMany(User::class, 'server_members', 'server_id', 'user_id');
	}

	public function owner()
	{
		return $this->hasOne(User::class, 'id', 'owner_id');
	}

	protected function fileAvatarImage(): Attribute
	{
		return Attribute::make(
			get: function () {
				if (!$this->avatar_img) {
					return null;
				}

				$path = Storage::disk('public')->path(self::IMAGESERVERPATH . $this->avatar_img);

				if (!file_exists($path)) {
					return null;
				}

				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);

				return [
					'base64' => base64_encode($data),
					'mime_type' => $type,
				];
			}
		);
	}
}
