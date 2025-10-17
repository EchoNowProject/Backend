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
		'verified',
		'status',
		'plan'
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
}
