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
        'amount',
        'currency',
        'method',
        'reference',
        'status',
        'proof_path',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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
