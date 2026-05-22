<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceRating extends Model
{
    protected $fillable = ['order_id', 'rated_by', 'score', 'comentario'];

    protected $casts = ['score' => 'integer'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(MarketplaceOrder::class, 'order_id');
    }
}
