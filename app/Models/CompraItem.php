<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompraItem extends Model
{
    protected $table = 'compra_items';

    protected $fillable = [
        'compra_id',
        'tipo_ave',
        'inventario_id',
        'gallo_id',
        'gallina_id',
        'placa',
        'nombre',
        'marca_nacimiento',
        'color',
        'costo',
        'cantidad',
        'foto_path',
        'observaciones',
    ];

    protected $casts = [
        'costo' => 'decimal:2',
    ];

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }

    public function gallo(): BelongsTo
    {
        return $this->belongsTo(Gallo::class, 'gallo_id');
    }

    public function gallina(): BelongsTo
    {
        return $this->belongsTo(Gallina::class, 'gallina_id');
    }
}

