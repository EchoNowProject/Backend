<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServerUserSetting
 * 
 * @property int $id
 * @property int $server_id
 * @property int $user_id
 * @property string|null $nickname
 * @property bool $muted
 * @property int $notifications_level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class ServerUserSetting extends Model
{
	protected $table = 'server_user_settings';

	protected $casts = [
		'server_id' => 'int',
		'user_id' => 'int',
		'muted' => 'bool',
		'notifications_level' => 'int'
	];
}
