<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketplaceOrder extends Model
{
    protected $fillable = [
        'publicacion_id', 'buyer_nombre', 'buyer_email', 'buyer_telefono',
        'buyer_pais', 'buyer_token', 'precio_acordado', 'status',
        'rated_by_buyer_at', 'rated_by_seller_at', 'closed_at',
    ];

    protected $casts = [
        'rated_by_buyer_at'  => 'datetime',
        'rated_by_seller_at' => 'datetime',
        'closed_at'          => 'datetime',
        'precio_acordado'    => 'decimal:2',
    ];

    public function publicacion(): BelongsTo
    {
        return $this->belongsTo(Publicacion::class);
    }

    public function chats(): HasMany
    {
        return $this->hasMany(MarketplaceChat::class, 'order_id')->orderBy('created_at');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(MarketplaceRating::class, 'order_id');
    }

    public function ratingDe(string $by): ?MarketplaceRating
    {
        return $this->ratings()->where('rated_by', $by)->first();
    }

    public function isClosed(): bool
    {
        return $this->closed_at !== null;
    }

    public function canRateBuyer(): bool
    {
        $days = (int) config('marketplace.rating_days', 12);
        return $this->status === 'completada'
            && $this->rated_by_buyer_at === null
            && $this->created_at->diffInDays(now()) <= $days;
    }

    public function canRateSeller(): bool
    {
        $days = (int) config('marketplace.rating_days', 12);
        return $this->status === 'completada'
            && $this->rated_by_seller_at === null
            && $this->created_at->diffInDays(now()) <= $days;
    }
}
