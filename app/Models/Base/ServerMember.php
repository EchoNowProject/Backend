<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServerMember
 * 
 * @property int $id
 * @property int $server_id
 * @property int $user_id
 * @property int $role_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class ServerMember extends Model
{
	protected $table = 'server_members';

	protected $casts = [
		'server_id' => 'int',
		'user_id' => 'int',
		'role_id' => 'int'
	];
}
