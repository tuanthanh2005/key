<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        // Original fields
        'category_id', 'name', 'brand', 'slug', 'color', 'price', 'old_price',
        'plan', 'duration_days', 'rating', 'reviews', 'features', 'stock', 'sold', 'status',
        'image_path', 'servers', 'countries', 'devices', 'speed', 'protocol',
        'headquarter', 'founded', 'refund', 'description', 'is_popular', 'require_upgrade_email', 'specs',
        // Key_new fields
        'type', 'original_price', 'duration', 'image', 'is_active', 'is_featured',
        'sort_order', 'meta_title', 'meta_description', 'sold_count', 'review_count', 'show_in_list', 'plan_note'
    ];

    protected $casts = [
        'features'      => 'array',
        'specs'         => 'array',
        'price'         => 'decimal:0',
        'original_price'=> 'decimal:0',
        'old_price'     => 'float',
        'stock'         => 'integer',
        'sold'          => 'integer',
        'is_popular'    => 'boolean',
        'is_active'     => 'boolean',
        'is_featured'   => 'boolean',
        'require_upgrade_email' => 'boolean',
        'duration_days' => 'integer',
        'show_in_list'  => 'boolean',
    ];

    protected $appends = ['image_url'];

    protected static function booted()
    {
        static::saving(function ($product) {
            // Keep brand and color populated for old frontend compatibility
            if (empty($product->brand) && $product->category_id) {
                $category = Category::find($product->category_id);
                if ($category) {
                    $product->brand = $category->slug;
                }
            }
            if (empty($product->color)) {
                $product->color = '#4687FF';
            }

            // Sync original columns from key_new columns
            if (isset($product->original_price)) {
                $product->old_price = $product->original_price;
            }
            if (isset($product->duration)) {
                $product->plan = $product->duration;
            }
            if (isset($product->image)) {
                $product->image_path = $product->image;
            }
            if (isset($product->is_active)) {
                $product->status = $product->is_active ? 'active' : 'inactive';
            }
            if (isset($product->sold_count)) {
                $product->sold = $product->sold_count;
            }
            if (isset($product->review_count)) {
                $product->reviews = $product->review_count;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function getAvailableLicensesCountAttribute(): int
    {
        return $this->licenses()->where('is_used', false)->count();
    }

    public function getInStockAttribute(): bool
    {
        if ($this->stock === -1) return true;
        return $this->stock > 0 || $this->licenses()->where('is_used', false)->exists();
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, '.', '.') . 'đ';
    }

    public function getFormattedOriginalPriceAttribute(): ?string
    {
        if ($this->original_price) {
            return number_format($this->original_price, 0, '.', '.') . 'đ';
        }
        return null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public static function formatPlanDuration($planKey): string
    {
        if (empty($planKey)) {
            return '';
        }

        if (preg_match('/^(\d+)\s*(day|month|year|d|m|y|week|w)s?$/i', trim($planKey), $matches)) {
            $num = $matches[1];
            $unit = strtolower($matches[2]);
            $unitLabel = match($unit) {
                'day', 'd' => 'Ngày',
                'week', 'w' => 'Tuần',
                'month', 'm' => 'Tháng',
                'year', 'y' => 'Năm',
                default => $unit
            };
            return "{$num} {$unitLabel}";
        }
        
        $mappings = [
            '1month' => '1 Tháng',
            '6month' => '6 Tháng',
            '1year' => '1 Năm',
            '2year' => '2 Năm',
            '3year' => '3 Năm',
        ];
        return $mappings[$planKey] ?? $planKey;
    }

    public static function formatPlanUnit($planKey): string
    {
        if (empty($planKey)) {
            return '';
        }

        if (preg_match('/^(\d+)\s*(day|month|year|d|m|y|week|w)s?$/i', trim($planKey), $matches)) {
            $num = $matches[1];
            $unit = strtolower($matches[2]);
            $unitLabel = match($unit) {
                'day', 'd' => 'ngày',
                'week', 'w' => 'tuần',
                'month', 'm' => 'tháng',
                'year', 'y' => 'năm',
                default => $unit
            };
            if ($num == 1 && $unitLabel === 'năm') {
                return 'năm';
            }
            if ($num == 1 && $unitLabel === 'tháng') {
                return 'tháng';
            }
            return "{$num} {$unitLabel}";
        }
        
        $mappings = [
            '1month' => 'tháng',
            '6month' => '6 tháng',
            '1year' => 'năm',
            '2year' => '2 năm',
            '3year' => '3 năm',
        ];
        return $mappings[$planKey] ?? $planKey;
    }

    public function getReviewsAttribute($value)
    {
        return $value !== null ? (int) $value : 0;
    }

    public function getRatingAttribute($value)
    {
        return $value !== null ? (float) $value : 0.0;
    }

    public function getSeoTitleAttribute(): string
    {
        if (!empty($this->meta_title)) {
            $t = $this->meta_title;
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

    public function getSeoDescriptionAttribute(): string
    {
        if (!empty($this->meta_description)) {
            return $this->meta_description;
        }

        if (!empty($this->description)) {
            $plainDesc = trim(strip_tags($this->description));
            if (mb_strlen($plainDesc) > 20) {
                return Str::limit($plainDesc, 160);
            }
        }

        $name = trim($this->name);
        if (!str_starts_with(mb_strtolower($name), 'tài khoản')) {
            $name = 'Tài khoản ' . $name;
        }

        return 'Mua ' . mb_strtolower($name) . ' giá rẻ, chính hãng tại vpnstore.pro. Giao tài khoản tự động 24/7, bảo hành uy tín trọn gói 1 đổi 1.';
    }

    /**
     * Accessor to dynamically resolve the product image URL.
     */
    public function getImageUrlAttribute()
    {
        $path = $this->image ?: $this->image_path;
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'products/') || str_starts_with($path, 'uploads/products/')) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
            if (file_exists(storage_path('app/public/' . $path))) {
                return asset('storage/' . $path);
            }
            return asset($path);
        }

        return asset('storage/' . $path);
    }

    /**
     * Helper to generate standardized plan key from label string
     */
    public static function generatePlanKey($label)
    {
        $l = strtolower(trim($label));
        if (str_contains($l, '1 tháng') || str_contains($l, '1thang') || $l === '1m') return '1month';
        if (str_contains($l, '2 tháng') || str_contains($l, '2thang') || $l === '2m') return '2month';
        if (str_contains($l, '3 tháng') || str_contains($l, '3thang') || $l === '3m') return '3month';
        if (str_contains($l, '6 tháng') || str_contains($l, '6thang') || $l === '6m') return '6month';
        if (str_contains($l, '1 năm') || str_contains($l, '1nam') || $l === '1y') return '1year';
        if (str_contains($l, '2 năm') || str_contains($l, '2nam') || $l === '2y') return '2year';
        if (str_contains($l, '3 năm') || str_contains($l, '3nam') || $l === '3y') return '3year';
        if (str_contains($l, 'vĩnh viễn') || str_contains($l, 'lifetime')) return 'lifetime';
        return \Illuminate\Support\Str::slug($label);
    }

    /**
     * Helper to guess duration days from plan key
     */
    public static function guessDurationDays($planKey)
    {
        $k = strtolower(trim($planKey));
        if ($k === '1month') return 30;
        if ($k === '2month') return 60;
        if ($k === '3month') return 90;
        if ($k === '6month') return 180;
        if ($k === '1year') return 365;
        if ($k === '2year') return 730;
        if ($k === '3year') return 1095;
        if ($k === 'lifetime') return 3650;
        return 30;
    }
}
