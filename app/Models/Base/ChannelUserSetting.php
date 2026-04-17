<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChannelUserSetting
 * 
 * @property int $id
 * @property int $channel_id
 * @property int $user_id
 * @property bool $muted
 * @property string $notification_level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class ChannelUserSetting extends Model
{
	protected $table = 'channel_user_settings';

	protected $casts = [
		'channel_id' => 'int',
		'user_id' => 'int',
		'muted' => 'bool'
	];
}
