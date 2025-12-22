<?php

namespace App\Models;

use App\Models\Base\VoiceSession as BaseVoiceSession;

class VoiceSession extends BaseVoiceSession
{
	protected $fillable = [
		'channel_id',
		'user_id',
		'joined_at',
		'left_at'
	];
}
