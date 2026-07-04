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
}
