<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PaymentOrder extends Model
{
    protected $table = 'payment_orders';

    protected $fillable = [
        'tenant_id',
        'plan_sku',
        'amount',
        'currency',
        'method',
        'gateway',
        'gateway_order_id',
        'gateway_subscription_id',
        'reference',
        'status',
        'proof_path',
        'notes',
        'period_starts_at',
        'period_ends_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'period_starts_at' => 'datetime',
        'period_ends_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    /** Primer usuario del tenant asociado a esta orden. */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Tenant::class,
            'id',        // FK en tenants → id (PK de tenants)
            'tenant_id', // FK en users → tenant_id
            'tenant_id', // FK local en payment_orders
            'id'         // PK de tenants
        );
    }
}
