<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConversationParticipant
 * 
 * @property int $id
 * @property int $conversation_id
 * @property int $user_id
 * @property string|null $username
 * @property Carbon|null $last_read_at
 * @property string|null $avatar_image
 * @property Carbon $joined_at
 *
 * @package App\Models\Base
 */
class ConversationParticipant extends Model
{
	protected $table = 'conversation_participants';
	public $timestamps = false;

	protected $casts = [
		'conversation_id' => 'int',
		'user_id' => 'int',
		'last_read_at' => 'datetime',
		'joined_at' => 'datetime'
	];
}
