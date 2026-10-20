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
        'plan_sku',
        'billing_cycle',
        'status',
        'ends_at',
        'trial_ends_at',
        'paypal_subscription_id',
        'paypal_payer_id',
        'cancelled_at',
    ];

    protected $casts = [
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
}
