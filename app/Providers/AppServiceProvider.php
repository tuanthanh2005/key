<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
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
                // Cache only plain arrays. Serialized Eloquent collections can become
                // incomplete objects when a production deploy replaces loaded classes.
                $categoryRows = Cache::remember('shared_categories_v2', 600, fn () => Category::query()
                    ->select(['id', 'name', 'slug', 'type', 'image_path'])
                    ->orderBy('name')
                    ->get()
                    ->map(fn (Category $category) => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'type' => $category->type,
                        'image_path' => $category->image_path,
                        'image_url' => $category->image_url,
                    ])
                    ->all()
                );
                $sharedCategories = collect($categoryRows)
                    ->map(fn (array $category) => (object) $category);
            }

            static $sharedHotProducts = null;
            if ($sharedHotProducts === null) {
                $productRows = Cache::remember('shared_hot_products_v4', 600, function() {
                    $prods = Product::query()
                        ->where('is_active', true)
                        ->where('show_in_list', true)
                        ->where('is_popular', true)
                        ->select(['id', 'name', 'slug', 'brand', 'price'])
                        ->orderBy('id', 'desc')
                        ->limit(9)
                        ->get()
                        ->toArray();
                    if (count($prods) < 9) {
                        $existingIds = array_column($prods, 'id');
                        $moreProds = Product::query()
                            ->where('is_active', true)
                            ->where('show_in_list', true)
                            ->whereNotIn('id', $existingIds)
                            ->select(['id', 'name', 'slug', 'brand', 'price'])
                            ->orderBy('sold', 'desc')
                            ->limit(9 - count($prods))
                            ->get()
                            ->toArray();
                        $prods = array_merge($prods, $moreProds);
                    }
                    return $prods;
                });
                $sharedHotProducts = $productRows;
            }

            $view->with([
                'sharedVpnCategories' => $sharedCategories->where('type', 'vpn'),
                'sharedProxyCategories' => $sharedCategories->where('type', 'proxy'),
                'sharedCategories' => $sharedCategories,
                'sharedHotProducts' => $sharedHotProducts,
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
