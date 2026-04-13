<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Friend
 * 
 * @property int $id
 * @property int $first_user_id
 * @property string $first_user_username
 * @property int $second_user_id
 * @property string $second_user_username
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Friend extends Model
{
	protected $table = 'friends';

	protected $casts = [
		'first_user_id' => 'int',
		'second_user_id' => 'int'
	];
}
