<?php

namespace App\Models;

use App\Actions\Chats\ChatActions;
use App\Models\IndividualChatMessagesFile;
use App\Models\Base\IndividualChatMessage as BaseIndividualChatMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;


class IndividualChatMessage extends BaseIndividualChatMessage
{
	protected $fillable = [
		'conversation_id',
		'user_sender_id',
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
		return $this->hasMany(IndividualChatMessagesFile::class, 'message_id', 'id');
	}

}
