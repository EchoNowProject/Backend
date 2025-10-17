<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * 
 * @property int $id
 * @property int $conversation_id
 * @property int $user_sender_id
 * @property string|null $content
 * @property string $type
 * @property string|null $file_url
 * @property string|null $read_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Message extends Model
{
	protected $table = 'messages';

	protected $casts = [
		'conversation_id' => 'int',
		'user_sender_id' => 'int'
	];
}
