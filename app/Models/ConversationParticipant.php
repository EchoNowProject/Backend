<?php

namespace App\Models;

use App\Models\Base\ConversationParticipant as BaseConversationParticipant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class ConversationParticipant extends BaseConversationParticipant
{

	const IMAGEUSERPATH = "/users/";

	protected $fillable = [
		'conversation_id',
		'user_id',
		'participant_role',
		'joined_at',
		'last_read_at',
		'avatar_image',
	];

	protected $appends = ['file_avatar_image'];

	/* ------------------------- RelationShips ------------------------- */
	public function conversation()
	{
		return $this->hasOne(Conversation::class, 'id', 'conversation_id');
	}

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	protected function fileAvatarImage(): Attribute
	{
		return Attribute::make(
			get: function () {
				if (!$this->avatar_image) {
					return null;
				}

				$path = Storage::disk('public')->path(self::IMAGEUSERPATH . $this->avatar_image);

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
}
