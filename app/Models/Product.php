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
        'sort_order', 'meta_title', 'meta_description', 'sold_count', 'review_count'
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
        return \App\Models\Order::where('brand', $this->brand)
            ->whereNotNull('review_rating')
            ->count();
    }

    public function getRatingAttribute($value)
    {
        $avg = \App\Models\Order::where('brand', $this->brand)
            ->whereNotNull('review_rating')
            ->avg('review_rating');
            
        return $avg ? (float) $avg : 0.0;
    }

    public function getSeoTitleAttribute(): string
    {
        if (!empty($this->meta_title)) {
            return $this->meta_title;
        }

        $lowerName = mb_strtolower($this->name);

        if (str_contains($lowerName, 'expressvpn') || (str_contains($lowerName, 'express') && str_contains($lowerName, 'vpn'))) {
            return 'Mua tài khoản ExpressVPN giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'nordvpn') || (str_contains($lowerName, 'nord') && str_contains($lowerName, 'vpn'))) {
            return 'Mua tài khoản NordVPN giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'surfshark')) {
            return 'Mua tài khoản Surfshark VPN giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'hma')) {
            return 'Mua tài khoản HMA VPN giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'vpn')) {
            return 'Mua tài khoản VPN Premium giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'chatgpt') || str_contains($lowerName, 'chat gpt') || str_contains($lowerName, 'openai')) {
            return 'Mua tài khoản ChatGPT Plus giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'gemini')) {
            return 'Mua tài khoản Gemini Advanced giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'youtube')) {
            return 'Mua tài khoản YouTube Premium giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'netflix') || str_contains($lowerName, 'nexflix')) {
            return 'Mua tài khoản Netflix Premium 4K giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'cursor')) {
            return 'Mua tài khoản Cursor Pro giá rẻ - Bảo hành Full - vpnstore.pro';
        }
        if (str_contains($lowerName, 'claude')) {
            return 'Mua tài khoản Claude Pro / Claude Code giá rẻ - Bảo hành Full - vpnstore.pro';
        }

        return 'Mua tài khoản ' . $this->name . ' giá rẻ - Bảo hành Full - vpnstore.pro';
    }

    public function getSeoDescriptionAttribute(): string
    {
        if (!empty($this->meta_description)) {
            return $this->meta_description;
        }

        $lowerName = mb_strtolower($this->name);

        if (str_contains($lowerName, 'expressvpn') || (str_contains($lowerName, 'express') && str_contains($lowerName, 'vpn'))) {
            return 'Mua tài khoản ExpressVPN chính hãng giá rẻ nhất thị trường. Bảo hành 1-đổi-1, giao key tự động 24/7. Tối ưu hóa tốc độ lướt web bảo mật tại vpnstore.pro.';
        }
        if (str_contains($lowerName, 'nordvpn') || (str_contains($lowerName, 'nord') && str_contains($lowerName, 'vpn'))) {
            return 'Mua tài khoản NordVPN chính hãng giá rẻ nhất. Bảo hành 1-đổi-1, giao tài khoản nhanh chóng, hỗ trợ bảo mật thông tin và truy cập mọi website tại vpnstore.pro.';
        }
        if (str_contains($lowerName, 'surfshark')) {
            return 'Mua tài khoản Surfshark VPN chính hãng giá tốt nhất. Bảo hành 1-đổi-1 toàn thời gian, hỗ trợ kết nối không giới hạn thiết bị, bảo mật an toàn.';
        }
        if (str_contains($lowerName, 'hma')) {
            return 'Cung cấp tài khoản HMA VPN chính hãng giá rẻ nhất thị trường. Bảo hành 1-đổi-1, giao hàng tự động 24/7. Mua ngay tại vpnstore.pro!';
        }
        if (str_contains($lowerName, 'vpn')) {
            return 'Cung cấp tài khoản VPN Premium chính hãng giá rẻ nhất thị trường. Bảo hành 1-đổi-1, giao hàng tự động 24/7. Mua ngay tại vpnstore.pro!';
        }
        if (str_contains($lowerName, 'chatgpt') || str_contains($lowerName, 'chat gpt') || str_contains($lowerName, 'openai')) {
            return 'Mua tài khoản ChatGPT Plus (OpenAI) giá rẻ nhất thị trường. Nâng cấp chính chủ, bảo hành full thời hạn sử dụng. Giao key tự động 24/7.';
        }
        if (str_contains($lowerName, 'gemini')) {
            return 'Cung cấp tài khoản Gemini Advanced AI giá rẻ, bảo hành trọn đời, hỗ trợ nâng cấp tài khoản chính chủ nhanh chóng tại vpnstore.pro.';
        }
        if (str_contains($lowerName, 'youtube')) {
            return 'Nâng cấp YouTube Premium chính chủ giá rẻ nhất, không quảng cáo, nghe nhạc background. Bảo hành 1-đổi-1 toàn thời gian sử dụng.';
        }
        if (str_contains($lowerName, 'netflix') || str_contains($lowerName, 'nexflix')) {
            return 'Mua tài khoản Netflix Premium 4K UHD giá rẻ, xem phim thả ga chất lượng cao. Hỗ trợ bảo hành toàn thời gian sử dụng, giao tài khoản tự động.';
        }
        if (str_contains($lowerName, 'cursor')) {
            return 'Cung cấp tài khoản Cursor Pro AI code editor giá rẻ, hỗ trợ đắc lực lập trình viên. Bảo hành uy tín, giao tài khoản tức thì tại vpnstore.pro.';
        }
        if (str_contains($lowerName, 'claude')) {
            return 'Mua tài khoản Claude Pro & Claude Code AI giá rẻ nhất thị trường. Bảo hành 1-đổi-1 uy tín, hỗ trợ đắc lực cho lập trình và viết code.';
        }

        if (!empty($this->description)) {
            return Str::limit(strip_tags($this->description), 160);
        }

        return 'Mua tài khoản ' . $this->name . ' giá rẻ, chính hãng tại vpnstore.pro. Giao hàng tự động, bảo hành uy tín full thời gian sử dụng.';
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
}
