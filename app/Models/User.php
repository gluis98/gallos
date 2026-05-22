<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	protected $table = 'users';

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'tenant_id',
		'is_superadmin',
		'extra_galpones_enabled',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
		'is_superadmin' => 'boolean',
		'extra_galpones_enabled' => 'boolean',
	];

	public function primaryTenant(): BelongsTo
	{
		return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
	}

	public function extraGalpones(): HasMany
	{
		return $this->hasMany(UserExtraGalpon::class);
	}
}
