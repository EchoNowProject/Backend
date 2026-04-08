<?php

namespace App\Models;

use App\Models\Base\FriendRequest as BaseFriendRequest;

class FriendRequest extends BaseFriendRequest
{
	protected $fillable = [
		'user_id_send_request',
		'user_id_receive_request'
	];

	public function senderUser()
	{
		return $this->hasOne(User::class, 'id', 'user_id_send_request');
	}
}
