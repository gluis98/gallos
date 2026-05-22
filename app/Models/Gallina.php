<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Gallina extends Model
{
    use BelongsToTenant;

    protected $table = 'gallinas';

    public $timestamps = true;

    protected $casts = [
        'padre_id' => 'int',
        'madre_id' => 'int',
    ];

    protected $fillable = [
        'tenant_id',
        'padre_id',
        'madre_id',
        'placa',
        'nombre',
        'marca',
        'anillo',
        'marca_nacimiento',
        'marca_federacion',
        'color',
        'color_alternativo',
        'cresta',
        'fecha_nacimiento',
        'luna',
        'observaciones',
        'estatus',
    ];

    public function tenantModel(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function gallinas_imagenes(): HasMany
    {
        return $this->hasMany(GallinasImagene::class, 'gallina_id');
    }

    public function gallos_hijos(): MorphOne
    {
        return $this->morphOne(GallosHijo::class, 'hijoable');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(GallosHijo::class, 'madre_id');
    }
}
