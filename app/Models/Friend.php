<?php

namespace App\Models;

use App\Models\Base\Friend as BaseFriend;

class Friend extends BaseFriend
{
	protected $fillable = [
		'first_user_id',
		'first_user_username',
		'second_user_id',
		'second_user_username'
	];
}
