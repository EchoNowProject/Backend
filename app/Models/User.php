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
}
