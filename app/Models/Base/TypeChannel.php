<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TypeChannel
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models\Base
 */
class TypeChannel extends Model
{
	protected $table = 'type_channel';
	public $timestamps = false;
}
