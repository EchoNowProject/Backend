<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServerRole
 * 
 * @property int $id
 * @property int $server_id
 * @property string $name
 * @property string $permissions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class ServerRole extends Model
{
	protected $table = 'server_roles';

	protected $casts = [
		'server_id' => 'int'
	];
}
