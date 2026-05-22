<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class GallosHijo extends Model
{
    use BelongsToTenant;

    protected $table = 'gallos_hijos';

    public $timestamps = true;

    protected $casts = [
        'padre_id' => 'int',
        'madre_id' => 'int',
        'hijoable_id' => 'int',
    ];

    protected $fillable = [
        'tenant_id',
        'padre_id',
        'madre_id',
        'hijoable_type',
        'hijoable_id',
        'tipo',
    ];

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Gallo::class, 'padre_id');
    }

    public function madre(): BelongsTo
    {
        return $this->belongsTo(Gallina::class, 'madre_id');
    }

    public function hijoable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'hijoable_type', 'hijoable_id');
    }
}
