<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'seo_title',
        'seo_description',
        'image_path',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('shared_categories'));
        static::deleted(fn () => Cache::forget('shared_categories'));
        static::saved(fn () => Cache::forget('shared_categories_v2'));
        static::deleted(fn () => Cache::forget('shared_categories_v2'));
    }

    /**
     * Một danh mục có nhiều sản phẩm
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Accessor to dynamically resolve the image URL based on whether it is stored in public_uploads (Hostinger) or local public storage.
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return null;
        }

        $path = trim($this->image_path);
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'categories/') || str_starts_with($path, 'uploads/') || str_starts_with($path, 'products/')) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
            if (file_exists(storage_path('app/public/'.$path))) {
                return asset('storage/'.$path);
            }
            return asset($path);
        }

        return asset('storage/'.$path);
    }

    public function getSeoTitleAttribute()
    {
        if (!empty($this->attributes['seo_title'])) {
            $t = $this->attributes['seo_title'];
            if (!str_contains(mb_strtolower($t), 'giá rẻ')) {
                $t .= ' Giá Rẻ - Bảo Hành Trọn Gói';
            }
            return $t;
        }

        $name = trim($this->name);
        if (!str_starts_with(mb_strtolower($name), 'tài khoản')) {
            $name = 'Tài Khoản ' . $name;
        }

        return $name . ' Giá Rẻ - Bảo Hành Trọn Gói';
    }

    public function getSeoDescriptionAttribute()
    {
        if (!empty($this->attributes['seo_description'])) {
            return $this->attributes['seo_description'];
        }

        $name = trim($this->name);
        if (!str_starts_with(mb_strtolower($name), 'tài khoản')) {
            $name = 'Tài khoản ' . $name;
        }

        return 'Mua ' . mb_strtolower($name) . ' giá rẻ, chính hãng tại vpnstore.pro. Giao tài khoản tự động 24/7, bảo hành uy tín trọn gói 1 đổi 1.';
    }
}
