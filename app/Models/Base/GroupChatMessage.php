<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupChatMessage
 * 
 * @property int $id
 * @property int $conversation_id
 * @property int $user_sender_id
 * @property string $user_sender_name
 * @property string|null $content
 * @property int $type_msg
 * @property bool $has_file
 * @property string|null $read_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class GroupChatMessage extends Model
{
	protected $table = 'group_chat_messages';

	protected $casts = [
		'conversation_id' => 'int',
		'user_sender_id' => 'int',
		'type_msg' => 'int',
		'has_file' => 'bool'
	];
}
