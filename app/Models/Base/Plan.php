<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Plan
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models\Base
 */
class Plan extends Model
{
	protected $table = 'plans';
	public $timestamps = false;
}
