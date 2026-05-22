<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comision extends Model
{
    protected $table = 'comisiones';

    protected $fillable = [
        'tenant_id',
        'venta_id',
        'monto',
        'estado',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }
}
