<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Subscription extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'plan',
        'status',
        'ends_at',
    ];

    protected $casts = [
        'ends_at' => 'datetime',
    ];
}
