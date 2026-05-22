<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Gallo extends Model
{
    use BelongsToTenant;

    protected $table = 'gallos';

    public $timestamps = true;

    protected $fillable = [
        'tenant_id',
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
        'peleas',
        'observaciones',
        'estatus',
    ];

    public function tenantModel(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function gallos_hijos(): MorphOne
    {
        return $this->morphOne(GallosHijo::class, 'hijoable');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(GallosHijo::class, 'padre_id');
    }

    public function gallos_imagenes(): HasMany
    {
        return $this->hasMany(GallosImagene::class, 'gallo_id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'gallo_id');
    }
}
