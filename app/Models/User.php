<?php

namespace App\Models;

use App\Models\Base\User as BaseUser;

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
		'avatar_img',
		'status',
		'plan',
		'password'
	];

	//RelationShips

	//Devuelve el stado del usuario
	public function status()
	{
		return $this->hasOne(Status::class, 'id', 'status');
	}

	// Retorna el plan elejido del usuario
	public function plan()
	{
		return $this->hasOne(Plan::class, 'id', 'plan');
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
