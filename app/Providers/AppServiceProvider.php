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
        $publicPath = base_path('public');
        
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
            if (basename($scriptDir) === 'public_html') {
                $publicPath = $scriptDir;
            }
        }
        
        if (basename($publicPath) !== 'public_html') {
            $siblingPublicHtml = base_path('../public_html');
            if (@file_exists($siblingPublicHtml) && @is_dir($siblingPublicHtml)) {
                $publicPath = $siblingPublicHtml;
            }
        }

        $this->app->usePublicPath($publicPath);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings + coupons tới mọi view
        // Dùng View::composer để tránh lỗi khi migrate (table chưa tồn tại)
        View::composer('*', function ($view) {
            static $settings = null;
            static $publicCoupons = null;
            static $checkedSettingsTable = null;
            static $checkedCouponsTable = null;

            if ($checkedSettingsTable === null) {
                $checkedSettingsTable = Schema::hasTable('settings');
            }

            if ($checkedSettingsTable) {
                if ($settings === null) {
                    $settings = Setting::getAllKeyed();
                }
                $view->with('settings', $settings);
            }

            if ($checkedCouponsTable === null) {
                $checkedCouponsTable = Schema::hasTable('coupons');
            }

            if ($checkedCouponsTable) {
                if ($publicCoupons === null) {
                    $publicCoupons = Coupon::getValidForJs(auth()->id());
                }
                $view->with('publicCoupons', $publicCoupons);

                static $userCoupons = null;
                if ($userCoupons === null) {
                    $userCoupons = (auth()->check() && Schema::hasColumn('coupons', 'user_id'))
                        ? Coupon::valid()->where('user_id', auth()->id())->get()
                        : collect();
                }
                $view->with('userCoupons', $userCoupons);
            }
        });
    }
}
