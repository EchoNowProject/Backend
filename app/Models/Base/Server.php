<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Server
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $avatar_img
 * @property int $owner_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Server extends Model
{
	protected $table = 'servers';

	protected $casts = [
		'owner_id' => 'int'
	];
}
