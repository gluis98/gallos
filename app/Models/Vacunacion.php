<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacunacion extends Model
{
    protected $table = 'vacunaciones';

    protected $fillable = [
        'ave_id', 'ave_type', 'vacuna', 'descripcion',
        'dosis', 'via_administracion', 'fecha_aplicacion',
        'proxima_fecha', 'lote', 'veterinario', 'notas',
        'tenant_id', 'user_id',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'proxima_fecha'    => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ave(): Model|null
    {
        return match ($this->ave_type) {
            'gallo'   => Gallo::withoutGlobalScopes()->find($this->ave_id),
            'gallina' => Gallina::withoutGlobalScopes()->find($this->ave_id),
            default   => null,
        };
    }

    public function scopeForTenant($query, string $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeProximas($query, int $days = 30)
    {
        return $query->whereNotNull('proxima_fecha')
            ->whereBetween('proxima_fecha', [now(), now()->addDays($days)]);
    }
}
