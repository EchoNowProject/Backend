<?php

namespace App\Models;

use App\Models\Base\User as BaseUser;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends BaseUser
{
	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'email',
		'display_name',
		'biography',
		'telephone_number',
		'prefix_telephone_number',
		'avatar_img',
		'status',
		'plan',
		'password'
	];

	protected $appends = ['file_avatar_image'];

	//RelationShips

	//Devuelve el stado del usuario
	public function statusUser()
	{
		return $this->hasOne(StatusUser::class, 'id', 'status');
	}

	// Retorna el plan elejido del usuario
	public function plan()
	{
		return $this->hasOne(PlanUser::class, 'id', 'plan');
	}

	// https://stackoverflow.com/questions/3967515/how-to-convert-an-image-to-base64-encoding
	protected function fileAvatarImage(): Attribute
	{
		return Attribute::make(
			get: function () {
				if (!$this->avatar_img) {
					return null;
				}

				$path = storage_path('app/private/users/' . $this->avatar_img);

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


	/**
	 * Funcion de validacion para la creacion
	 * @deprecated
	 */
	/* public static function boot()
	{
		parent::boot();

		static::saving(function ($user) {
			$usuario = static::where('username', $user->username)->first();
			$email = static::where('email', $user->email)->first();

			if ($usuario) {
				throw new Exception("El nombre de usuario ya esta siendo utilizado");
			}
			if ($email) {
				throw new Exception("El correo electronico ya esta siendo utilizado");
			}
		});
	} */
}
