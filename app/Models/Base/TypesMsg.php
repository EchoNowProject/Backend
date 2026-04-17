<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TypesMsg
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models\Base
 */
class TypesMsg extends Model
{
	protected $table = 'types_msg';
	public $timestamps = false;
}
