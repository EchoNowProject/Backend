<?php

namespace App\Models;

use App\Models\Base\UserAlert as BaseUserAlert;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAlert extends BaseUserAlert
{
	protected $fillable = [
		'source_user_id',
		'target_user_id',
		'type',
		'message'
	];

	public function sourceUser(): HasOne
	{
		return $this->hasOne(User::class, 'id', 'source_user_id');
	}

	public function targetUser(): HasOne
	{
		return $this->hasOne(User::class, 'id', 'target_user_id');
	}
}
