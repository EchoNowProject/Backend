<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPrivacySetting
 * 
 * @property int $user_id
 * @property bool $friend_request_permission
 * @property bool $direct_message_permission
 * @property bool $allow_search_by_email
 * @property bool $allow_search_by_phone
 * @property bool $show_online_status
 * @property bool $show_activity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class UserPrivacySetting extends Model
{
	protected $table = 'user_privacy_settings';
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'friend_request_permission' => 'bool',
		'direct_message_permission' => 'bool',
		'allow_search_by_email' => 'bool',
		'allow_search_by_phone' => 'bool',
		'show_online_status' => 'bool',
		'show_activity' => 'bool'
	];
}
