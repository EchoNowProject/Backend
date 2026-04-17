<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserNotificationSetting
 * 
 * @property int $user_id
 * @property bool $email_notifications
 * @property bool $push_notifications
 * @property bool $notify_friend_requests
 * @property bool $sound_enabled
 * @property bool $show_message_preview
 * @property bool $notify_direct_messages
 * @property bool $notify_mentions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class UserNotificationSetting extends Model
{
	protected $table = 'user_notification_settings';
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'email_notifications' => 'bool',
		'push_notifications' => 'bool',
		'notify_friend_requests' => 'bool',
		'sound_enabled' => 'bool',
		'show_message_preview' => 'bool',
		'notify_direct_messages' => 'bool',
		'notify_mentions' => 'bool'
	];
}
