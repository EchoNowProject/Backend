<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ParticipantRole
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models\Base
 */
class ParticipantRole extends Model
{
	protected $table = 'participant_role';
	public $timestamps = false;
}
