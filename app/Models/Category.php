<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'seo_title',
        'seo_description',
        'image_path'
    ];

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
        if (str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }
        if (str_starts_with($this->image_path, 'categories/')) {
            if (file_exists(public_path($this->image_path))) {
                return asset($this->image_path);
            }
            if (file_exists(storage_path('app/public/' . $this->image_path))) {
                return asset('storage/' . $this->image_path);
            }
            return asset($this->image_path);
        }
        return asset('storage/' . $this->image_path);
    }
}
