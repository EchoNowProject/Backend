<?php

namespace App\Models;

use App\Actions\Chats\ChatActions;
use App\Models\Base\ServerChatMessage as BaseServerChatMessage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ServerChatMessage extends BaseServerChatMessage
{
	protected $fillable = [
		'conversation_id',
		'user_sender_id',
		'user_sender_name',
		'content',
		'type_msg',
		'has_file',
		'read_by'
	];

	protected $appends = ['shipping_time'];

	protected function shippingTime(): Attribute
	{
		return Attribute::make(
			get: fn() => ChatActions::makeShippingTime($this->created_at),
		);
	}

	// --------------------------------- RelationShips ---------------------------------
	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_sender_id');
	}

	public function filesMessage()
	{
		return $this->hasMany(ServerChatMessagesFile::class, 'message_id', 'id');
	}
}
