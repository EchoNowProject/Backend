<?php

namespace App\Models;

use App\Models\Base\PlanUser as BasePlanUser;

class PlanUser extends BasePlanUser
{
	protected $fillable = [
		'name'
	];
}
