<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $display_name
 * @property string|null $biography
 * @property string|null $avatar_img
 * @property int $status
 * @property int|null $plan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 *
 * @package App\Models\Base
 */

class User extends Authenticatable
{
	use HasFactory, HasApiTokens, Notifiable;

	protected $table = 'users';

	protected $casts = [
		'status' => 'int',
		'plan' => 'int',
		'two_factor_confirmed_at' => 'datetime'
	];
}
