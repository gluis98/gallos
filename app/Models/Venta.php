<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Venta extends Model
{
    use BelongsToTenant;

    protected $table = 'ventas';

    protected $fillable = [
        'tenant_id',
        'gallo_id',
        'gallina_id',
        'cliente_id',
        'fecha',
        'precio',
        'tipo_venta',
        'tipo_item',
        'inventario_id',
        'cantidad',
        'observaciones',
        'estatus',
    ];

    protected $casts = [
        'gallo_id'      => 'int',
        'gallina_id'    => 'int',
        'cliente_id'    => 'int',
        'inventario_id' => 'int',
        'fecha'         => 'date',
        'precio'        => 'decimal:2',
        'cantidad'      => 'decimal:2',
    ];

    protected $appends = [
        'nombre_cliente',
        'telefono',
        'monto',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function gallo(): BelongsTo
    {
        return $this->belongsTo(Gallo::class, 'gallo_id');
    }

    public function gallina(): BelongsTo
    {
        return $this->belongsTo(Gallina::class, 'gallina_id');
    }

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }

    public function getNombreClienteAttribute(): ?string
    {
        return $this->cliente?->name;
    }

    public function getTelefonoAttribute(): ?string
    {
        return $this->cliente?->phone;
    }

    public function getMontoAttribute(): ?string
    {
        return $this->precio !== null ? (string) $this->precio : null;
    }
}
