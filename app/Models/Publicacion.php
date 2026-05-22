<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Publicacion extends Model
{
    use BelongsToTenant;

    protected $table = 'publicaciones';

    protected $fillable = [
        'tenant_id',
        'ave_type',
        'ave_id',
        'precio',
        'descripcion',
        'activo',
        'destacado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
        'destacado' => 'boolean',
    ];

    public function ave(): MorphTo
    {
        return $this->morphTo('ave', 'ave_type', 'ave_id');
    }
}
