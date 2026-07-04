<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Lấy giá trị theo key (không cache, để realtime)
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set hoặc update một setting và xóa cache
     */
    public static function set($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('site_settings');
    }

    /**
     * Lấy tất cả settings dạng [key => value] - có cache 60 phút
     */
    public static function getAllKeyed(): array
    {
        return Cache::remember('site_settings', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Xóa cache settings (gọi sau khi bulk update)
     */
    public static function clearCache(): void
    {
        Cache::forget('site_settings');
    }
}

