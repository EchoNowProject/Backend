<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Status
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models\Base
 */
class Status extends Model
{
	protected $table = 'status';
	public $timestamps = false;
}
