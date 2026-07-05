<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Coupon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Notifications\ResetPassword;

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
        // Dùng route đặt lại mật khẩu tùy chỉnh thay vì route mặc định của Laravel
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return route('auth.reset-password', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });
        // Share settings + coupons tới mọi view
        // Dùng View::composer để tránh lỗi khi migrate (table chưa tồn tại)
        View::composer('*', function ($view) {
            static $settings = null;
            static $publicCoupons = null;
            static $checkedSettingsTable = null;
            static $checkedCouponsTable = null;
            static $sharedVpnCategories = null;
            static $sharedProxyCategories = null;
            static $checkedCategoriesTable = null;

            if ($checkedCategoriesTable === null) {
                $checkedCategoriesTable = Schema::hasTable('categories');
            }

            if ($checkedCategoriesTable) {
                if ($sharedVpnCategories === null) {
                    $sharedVpnCategories = \App\Models\Category::where('type', 'vpn')->get();
                }
                if ($sharedProxyCategories === null) {
                    $sharedProxyCategories = \App\Models\Category::where('type', 'proxy')->get();
                }
                $view->with([
                    'sharedVpnCategories' => $sharedVpnCategories,
                    'sharedProxyCategories' => $sharedProxyCategories,
                    'sharedCategories' => $sharedVpnCategories->concat($sharedProxyCategories)
                ]);
            }

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
