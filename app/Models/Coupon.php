<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order',
        'max_uses',
        'used_count',
        'active',
        'expires_at',
        'description',
    ];

    protected $casts = [
        'discount_value' => 'float',
        'min_order'      => 'integer',
        'max_uses'       => 'integer',
        'used_count'     => 'integer',
        'active'         => 'boolean',
        'expires_at'     => 'datetime',
    ];

    /**
     * Chỉ lấy coupon đang active và chưa hết hạn
     */
    public function scopeValid($query)
    {
        return $query->where('active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_uses')
                  ->orWhereColumn('used_count', '<', 'max_uses');
            });
    }

    /**
     * Tính số tiền giảm cho một subtotal
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            return round($subtotal * $this->discount_value / 100);
        }

        // fixed
        return min($this->discount_value, $subtotal);
    }

    /**
     * Lấy tất cả coupon hợp lệ dạng [code => percent_value] để tương thích với JS cũ
     */
    public static function getValidForJs(): array
    {
        return self::valid()->get()->mapWithKeys(function ($coupon) {
            // JS CartManager dùng giá trị %, chỉ hỗ trợ percent type cho tương thích
            if ($coupon->discount_type === 'percent') {
                return [$coupon->code => $coupon->discount_value];
            }
            return [];
        })->toArray();
    }
}
