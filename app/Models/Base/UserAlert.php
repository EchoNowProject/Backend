<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAlert
 * 
 * @property int $id
 * @property int $source_user_id
 * @property int $target_user_id
 * @property string $type
 * @property string|null $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class UserAlert extends Model
{
	protected $table = 'user_alerts';

	protected $casts = [
		'source_user_id' => 'int',
		'target_user_id' => 'int'
	];
}
