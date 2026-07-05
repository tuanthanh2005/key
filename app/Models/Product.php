<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'slug',
        'color',
        'price',
        'old_price',
        'plan',
        'rating',
        'reviews',
        'features',
        'stock',
        'sold',
        'status',
        'image_path',
        'servers',
        'countries',
        'devices',
        'speed',
        'protocol',
        'headquarter',
        'founded',
        'refund',
        'description',
        'is_popular',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'float',
        'old_price' => 'float',
        'rating' => 'float',
        'reviews' => 'integer',
        'stock' => 'integer',
        'sold' => 'integer',
        'is_popular' => 'boolean',
    ];

    /**
     * Sản phẩm thuộc về một danh mục
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Định dạng nhãn thời gian gói vpn (Ví dụ: 1month -> 1 Tháng, 7day -> 7 Ngày, 2year -> 2 Năm)
     */
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

    /**
     * Định dạng đơn vị thời gian cho phần giá tiền (Ví dụ: 1month -> tháng, 2year -> 2 năm, 7day -> 7 ngày)
     */
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

    /**
     * Get deterministic pseudo-random reviews count if not set in DB
     */
    public function getReviewsAttribute($value)
    {
        if (!empty($value)) {
            return (int) $value;
        }

        $reviewPool = [
            87, 104, 118, 126, 95, 142, 115, 131, 99, 150, 
            89, 112, 137, 108, 121, 145, 93, 119, 134, 128, 
            102, 114, 91, 140, 123, 106, 139, 97, 125, 148
        ];

        $id = $this->id ?: 1;
        return $reviewPool[$id % 30] ?? 120;
    }

    /**
     * Get deterministic pseudo-random rating if not set in DB
     */
    public function getRatingAttribute($value)
    {
        if (!empty($value)) {
            return (float) $value;
        }

        $ratingPool = [
            4.8, 4.9, 4.7, 4.8, 4.9, 4.8, 4.7, 4.8, 4.9, 4.8,
            4.9, 4.7, 4.8, 4.9, 4.8, 4.7, 4.8, 4.9, 4.8, 4.9,
            4.7, 4.8, 4.9, 4.8, 4.7, 4.8, 4.9, 4.8, 4.9, 4.8
        ];

        $id = $this->id ?: 1;
        return $ratingPool[$id % 30] ?? 4.8;
    }
}
