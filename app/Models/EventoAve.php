<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class EventoAve extends Model
{
    use BelongsToTenant;

    protected $table = 'evento_aves';

    protected $fillable = [
        'tenant_id',
        'ave_type',
        'ave_id',
        'tipo_evento',
        'fecha',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function ave(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'ave_type', 'ave_id');
    }
}
