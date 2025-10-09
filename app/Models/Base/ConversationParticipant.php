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
		'joined_at' => 'datetime'
	];
}
