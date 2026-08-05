<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

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
            static $sharedCategories = null;

            if ($sharedCategories === null) {
                $sharedCategories = Cache::remember('shared_categories', 600, fn () => Category::query()
                    ->select(['id', 'name', 'slug', 'type', 'image_path'])
                    ->orderBy('name')
                    ->get()
                );
            }
            $view->with([
                'sharedVpnCategories' => $sharedCategories->where('type', 'vpn'),
                'sharedProxyCategories' => $sharedCategories->where('type', 'proxy'),
                'sharedCategories' => $sharedCategories,
            ]);

            if ($settings === null) {
                $settings = Setting::getAllKeyed();
            }
            $view->with('settings', $settings);

            if ($publicCoupons === null) {
                $publicCoupons = Cache::remember('public_coupons_js', 60, fn () => Coupon::getValidForJs()
                );
            }
            static $userCoupons = null;
            if ($userCoupons === null) {
                $userCoupons = auth()->check()
                    ? Coupon::valid()->where('user_id', auth()->id())->get()
                    : collect();
            }

            $personalCoupons = $userCoupons
                ->where('discount_type', 'percent')
                ->mapWithKeys(fn (Coupon $coupon) => [$coupon->code => $coupon->discount_value])
                ->all();

            $view->with('publicCoupons', array_merge($publicCoupons, $personalCoupons));
            $view->with('userCoupons', $userCoupons);
        });
    }
}
