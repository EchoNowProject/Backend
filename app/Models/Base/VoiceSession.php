<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VoiceSession
 * 
 * @property int $id
 * @property int $channel_id
 * @property int $user_id
 * @property Carbon $joined_at
 * @property Carbon $left_at
 *
 * @package App\Models\Base
 */
class VoiceSession extends Model
{
	protected $table = 'voice_sessions';
	public $timestamps = false;

	protected $casts = [
		'channel_id' => 'int',
		'user_id' => 'int',
		'joined_at' => 'datetime',
		'left_at' => 'datetime'
	];
}
