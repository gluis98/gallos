<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Client extends Model
{
    use BelongsToTenant;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'tenant_id',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }
}
