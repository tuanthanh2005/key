<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    protected $fillable = [
        'product_id', 'order_item_id', 'order_id', 'user_id',
        'license_key', 'type', 'is_used', 'assigned_at', 'expires_at'
    ];

    protected $casts = [
        'is_used'     => 'boolean',
        'assigned_at' => 'datetime',
        'expires_at'  => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_used', false);
    }
}
