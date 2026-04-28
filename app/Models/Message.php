<?php

namespace App\Models;

use App\Models\Base\Message as BaseMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Message extends BaseMessage
{
	protected $fillable = [
		'conversation_id',
		'user_sender_id',
		'content',
		'type_msg',
		'file',
		'read_by'
	];

	protected $appends = ['shipping_time'];

	protected function shippingTime(): Attribute
	{
		return Attribute::make(
			get: function () {
				$hour = str(Carbon::parse($this->created_at)->hour);
				$minutes = str(Carbon::parse($this->created_at)->minute);
				return $hour . ':' . $minutes;
			},
		);
	}

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_sender_id');
	}
}
