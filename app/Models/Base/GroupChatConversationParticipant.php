<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupChatConversationParticipant
 * 
 * @property int $id
 * @property int $conversation_id
 * @property int $user_id
 * @property int|null $participant_role
 * @property string|null $username
 * @property Carbon|null $last_read_at
 * @property string|null $avatar_image
 * @property Carbon $joined_at
 *
 * @package App\Models\Base
 */
class GroupChatConversationParticipant extends Model
{
	protected $table = 'group_chat_conversation_participants';
	public $timestamps = false;

	protected $casts = [
		'conversation_id' => 'int',
		'user_id' => 'int',
		'participant_role' => 'int',
		'last_read_at' => 'datetime',
		'joined_at' => 'datetime'
	];
}
