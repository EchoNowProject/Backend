<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StatusUser
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models\Base
 */
class StatusUser extends Model
{
	protected $table = 'status_user';
	public $timestamps = false;
}
