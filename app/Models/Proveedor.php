<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Proveedor extends Model
{
    use BelongsToTenant;

    protected $table = 'proveedores';

    protected $fillable = [
        'tenant_id',
        'name',
        'phone',
        'email',
    ];

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'proveedor_id');
    }
}

