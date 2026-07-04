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
    ];

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
