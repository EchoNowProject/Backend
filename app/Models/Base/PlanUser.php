<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PlanUser
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models\Base
 */
class PlanUser extends Model
{
	protected $table = 'plan_user';
	public $timestamps = false;
}
