<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation
 * 
 * @property int $id
 * @property int $channel_id
 * @property string $type_conversation
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Conversation extends Model
{
	protected $table = 'conversations';

	protected $casts = [
		'channel_id' => 'int'
	];
}
