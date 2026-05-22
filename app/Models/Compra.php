<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Compra extends Model
{
    use BelongsToTenant;

    protected $table = 'compras';

    protected $fillable = [
        'tenant_id',
        'proveedor_id',
        'fecha_compra',
        'total',
        'observaciones',
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'total' => 'decimal:2',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CompraItem::class, 'compra_id');
    }
}

