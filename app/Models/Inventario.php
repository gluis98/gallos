<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Inventario extends Model
{
    use BelongsToTenant;

    protected $table = 'inventarios';

    protected $fillable = [
        'tenant_id',
        'nombre',
        'descripcion',
        'categoria',
        'unidad_medida',
        'stock_actual',
        'costo_unitario',
        'utilidad_porcentaje',
        'precio_venta',
        'foto_path',
        'notas',
    ];

    protected $casts = [
        'stock_actual'        => 'decimal:2',
        'costo_unitario'      => 'decimal:4',
        'utilidad_porcentaje' => 'decimal:2',
        'precio_venta'        => 'decimal:4',
    ];

    protected $appends = ['precio_venta_calculado'];

    /** Precio de venta calculado: costo × (1 + utilidad/100). Usa manual si diferente. */
    public function getPrecioVentaCalculadoAttribute(): float
    {
        if ((float) $this->precio_venta > 0) {
            return (float) $this->precio_venta;
        }
        return round((float) $this->costo_unitario * (1 + (float) $this->utilidad_porcentaje / 100), 4);
    }

    public function ajustarStock(float $cantidad, string $operacion = 'incrementar'): void
    {
        if ($operacion === 'incrementar') {
            $this->increment('stock_actual', $cantidad);
        } else {
            $this->decrement('stock_actual', $cantidad);
        }
    }
}
