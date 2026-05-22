<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceChat extends Model
{
    protected $fillable = [
        'order_id', 'sender_type', 'sender_nombre',
        'mensaje', 'adjunto_path', 'adjunto_nombre',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(MarketplaceOrder::class, 'order_id');
    }
}
