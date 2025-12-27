<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Channel
 * 
 * @property int $id
 * @property int $server_id
 * @property string $name
 * @property int $type_channels
 * @property string $permissions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Channel extends Model
{
	protected $table = 'channels';

	protected $casts = [
		'server_id' => 'int',
		'type_channels' => 'int'
	];
}
