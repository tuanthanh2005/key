<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'user_id', 'customer_name', 'customer_email',
        'customer_phone', 'product_name', 'brand', 'plan',
        'quantity', 'price', 'discount', 'total', 'coupon',
        'payment_method', 'payment_status', 'order_status',
        'license_key', 'note', 'review_rating', 'review_comment',
        'start_date', 'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($order) {
            // When the order status is changing to completed
            if ($order->order_status === 'completed' && empty($order->start_date)) {
                // Find matching product
                $product = \App\Models\Product::where('brand', $order->brand)
                    ->where('plan', $order->plan)
                    ->first();

                $days = 30; // Default fallback duration (30 days)
                if ($product && !empty($product->duration_days)) {
                    $days = intval($product->duration_days);
                } else {
                    // Fallback based on plan text
                    $planLower = strtolower($order->plan);
                    if (str_contains($planLower, 'day')) {
                        preg_match('/\d+/', $planLower, $matches);
                        $days = isset($matches[0]) ? intval($matches[0]) : 1;
                    } elseif (str_contains($planLower, 'month')) {
                        preg_match('/\d+/', $planLower, $matches);
                        $months = isset($matches[0]) ? intval($matches[0]) : 1;
                        $days = $months * 30;
                    } elseif (str_contains($planLower, 'year')) {
                        preg_match('/\d+/', $planLower, $matches);
                        $years = isset($matches[0]) ? intval($matches[0]) : 1;
                        $days = $years * 365;
                    }
                }

                $order->start_date = now();
                $order->end_date = now()->addDays($days);
            }
        });
    }

    /**
     * Quan hệ với user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Label thanh toán
     */
    public function getPaymentMethodLabel(): string
    {
        return match($this->payment_method) {
            'bank_transfer' => 'Chuyển Khoản',
            'momo'         => 'MoMo',
            'zalopay'      => 'ZaloPay',
            'crypto'       => 'Crypto',
            default        => $this->payment_method,
        };
    }

    /**
     * Label trạng thái đơn
     */
    public function getStatusLabel(): string
    {
        return match($this->order_status) {
            'pending'    => 'Chờ Xử Lý',
            'processing' => 'Đang Xử Lý',
            'completed'  => 'Hoàn Tất',
            'cancelled'  => 'Đã Hủy',
            default      => $this->order_status,
        };
    }

    /**
     * Badge color trạng thái
     */
    public function getStatusBadge(): string
    {
        return match($this->order_status) {
            'pending'    => 'warning',
            'processing' => 'primary',
            'completed'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    }

    /**
     * Badge color thanh toán
     */
    public function getPaymentBadge(): string
    {
        return match($this->payment_status) {
            'pending'  => 'warning',
            'paid'     => 'success',
            'failed'   => 'danger',
            'refunded' => 'secondary',
            default    => 'secondary',
        };
    }

    /**
     * Generate order code
     */
    public static function generateCode(): string
    {
        return 'VPN' . strtoupper(substr(uniqid(), -8));
    }
}
