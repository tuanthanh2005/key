<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Coupon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('path.public', function() {
            $publicHtmlPath = base_path('../public_html');
            if (is_dir($publicHtmlPath)) {
                return $publicHtmlPath;
            }
            return base_path('public');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings + coupons tới mọi view
        // Dùng View::composer để tránh lỗi khi migrate (table chưa tồn tại)
        View::composer('*', function ($view) {
            if (Schema::hasTable('settings')) {
                $settings = Setting::getAllKeyed();
                $view->with('settings', $settings);
            }
            if (Schema::hasTable('coupons')) {
                $publicCoupons = Coupon::getValidForJs();
                $view->with('publicCoupons', $publicCoupons);
            }
        });
    }
}
