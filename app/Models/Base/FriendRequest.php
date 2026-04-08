<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FriendRequest
 * 
 * @property int $user_id_send_request
 * @property int $user_id_receive_request
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class FriendRequest extends Model
{
	protected $table = 'friend_requests';
	public $incrementing = false;

	protected $casts = [
		'user_id_send_request' => 'int',
		'user_id_receive_request' => 'int'
	];
}
