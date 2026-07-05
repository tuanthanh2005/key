<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'google_id', 'avatar', 'phone', 'status'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Kiểm tra quyền admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra trạng thái active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Quan hệ với đơn hàng
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Lấy avatar hoặc chữ cái đầu
     */
    public function getAvatarInitial(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Model Events
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->role === 'user' && \Illuminate\Support\Facades\Schema::hasColumn('coupons', 'user_id')) {
                $couponCode = 'NEW' . $user->id . strtoupper(\Illuminate\Support\Str::random(3));
                \App\Models\Coupon::create([
                    'code'           => $couponCode,
                    'discount_type'  => 'percent',
                    'discount_value' => 2,
                    'min_order'      => 0,
                    'max_uses'       => 1,
                    'expires_at'     => now()->addDay(),
                    'description'    => 'Quà tặng thành viên mới - Giảm 2% (Hạn dùng 24h)',
                    'active'         => true,
                    'user_id'        => $user->id,
                ]);
            }
        });
    }
}
