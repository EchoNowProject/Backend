<?php

namespace App\Models;

use App\Models\Base\ParticipantRole as BaseParticipantRole;

class ParticipantRole extends BaseParticipantRole
{
	protected $fillable = [
		'name'
	];
}
