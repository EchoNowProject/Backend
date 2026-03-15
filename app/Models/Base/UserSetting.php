<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSetting
 * 
 * @property int $user_id
 * @property string $theme
 * @property bool $notifications_enable
 * @property bool $sound_enable
 * @property int $volume
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class UserSetting extends Model
{
	protected $table = 'user_settings';
	protected $primaryKey = 'user_id';
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'notifications_enable' => 'bool',
		'sound_enable' => 'bool',
		'volume' => 'int'
	];
}
