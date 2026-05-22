<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class PesajeAve extends Model
{
    use BelongsToTenant;

    protected $table = 'pesaje_aves';

    protected $fillable = [
        'tenant_id',
        'gallo_id',
        'gallina_id',
        'fecha',
        'peso_kg',
    ];

    protected $casts = [
        'fecha' => 'date',
        'peso_kg' => 'decimal:3',
    ];

    public function gallo(): BelongsTo
    {
        return $this->belongsTo(Gallo::class);
    }

    public function gallina(): BelongsTo
    {
        return $this->belongsTo(Gallina::class);
    }
}
