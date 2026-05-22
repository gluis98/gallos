<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserExtraGalpon extends Model
{
    protected $table = 'user_extra_galpones';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'label',
        'granted_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function displayName(): string
    {
        return $this->label ?: ($this->tenant?->name ?? 'Galpón');
    }
}
