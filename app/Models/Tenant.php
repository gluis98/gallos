<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'status',
            'catalog_enabled',
            'catalog_token_hash',
            'catalog_token_encrypted',
            'created_at',
            'updated_at',
            'data',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'id');
    }

    public function paymentOrders(): HasMany
    {
        return $this->hasMany(PaymentOrder::class, 'tenant_id', 'id');
    }
}
