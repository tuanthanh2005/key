<?php
  
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\IndexingController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Artisan;
   
/*
|--------------------------------------------------------------------------
| VPNStore - Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    return "All Laravel caches cleared successfully! (OPcache cleared too)";
});

Route::get('/debug-view', function() {
    return response()->json([
        'content' => base64_encode(file_get_contents(resource_path('views/product-detail.blade.php')))
    ]);
});

Route::get('/debug-line/{start}/{end}', function($start, $end) {
    $lines = explode("\n", file_get_contents(resource_path('views/product-detail.blade.php')));
    return bin2hex(implode("\n", array_slice($lines, $start - 1, $end - $start + 1)));
});

Route::get('/debug-log', function() {
    $logPath = storage_path('logs/laravel.log');
    if (!file_exists($logPath)) {
        return "Log file does not exist.";
    }
    $content = file_get_contents($logPath);
    $lines = explode("\n", $content);
    return response(implode("\n", array_slice($lines, -100)), 200, ['Content-Type' => 'text/plain']);
});

// =============================================
// TRANG CÔNG KHAI (SHOP)
// =============================================
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/san-pham', [ShopController::class, 'products'])->name('products');
Route::get('/san-pham/{slug}', [ShopController::class, 'productDetail'])
    ->where('slug', '[a-zA-Z0-9\-]+')
    ->name('product.detail');
Route::get('/gio-hang', [ShopController::class, 'cart'])->name('cart');
Route::get('/thanh-toan', [ShopController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/thanh-toan', [ShopController::class, 'storeOrder'])->name('checkout.store')->middleware('auth');
Route::get('/order/success', [ShopController::class, 'orderSuccess'])->name('order.success');
Route::get('/tra-don-hang', [ShopController::class, 'orderCheck'])->name('order.check');
Route::post('/tra-don-hang/review', [ShopController::class, 'submitOrderReview'])->name('order.review.submit');
Route::get('/lich-su-don-hang', [ShopController::class, 'orderHistory'])->name('order.history')->middleware('auth');
Route::post('/wishlist/toggle', [ShopController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::get('/san-pham-yeu-thich', [ShopController::class, 'wishlistPage'])->name('wishlist.index')->middleware('auth');
Route::get('/gioi-thieu', [ShopController::class, 'about'])->name('about');
Route::get('/lien-he', [ShopController::class, 'contact'])->name('contact');
Route::get('/tim-kiem', [ShopController::class, 'search'])->name('search');
Route::get('/tin-tuc', [ShopController::class, 'postList'])->name('posts.index');
Route::get('/tin-tuc/{slug}', [ShopController::class, 'postDetail'])->name('posts.show');

// =============================================
// XML Sitemap
// =============================================
Route::get('/sitemap.xml', function () {
    // Get all Category slugs (e.g. vpn, chatgpt, netflix brand pages)
    $categorySlugs = \App\Models\Category::pluck('slug')->toArray();

    // Get all active Product slugs
    $productSlugs = \App\Models\Product::where('status', 'active')
        ->whereNotNull('slug')
        ->pluck('slug')
        ->toArray();

    // Merge and unique them so both brand hubs and specific products are indexed
    $brands = array_unique(array_filter(array_merge($categorySlugs, $productSlugs)));

    // Get published posts
    $posts = \App\Models\Post::published()
        ->orderBy('created_at', 'desc')
        ->get(['slug', 'updated_at']);

    return response()->view('sitemap', compact('brands', 'posts'))
        ->header('Content-Type', 'text/xml');
})->name('sitemap');

// =============================================
// XÁC THỰC (Auth)
// =============================================
Route::prefix('auth')->name('auth.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
        Route::get('/dang-nhap', [AuthController::class, 'loginForm'])->name('login');
        Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.post');
        Route::get('/dang-ky', [AuthController::class, 'registerForm'])->name('register');
        Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.post');
        Route::get('/quen-mat-khau', [AuthController::class, 'forgotPasswordForm'])->name('forgot-password');
        Route::post('/quen-mat-khau', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');
        Route::get('/dat-lai-mat-khau/{token}', [AuthController::class, 'resetPasswordForm'])->name('reset-password');
        Route::post('/dat-lai-mat-khau', [AuthController::class, 'resetPassword'])->name('reset-password.post');
    });

    // Authenticated
    Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Google OAuth
    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// =============================================
// ADMIN DASHBOARD
// =============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Cài đặt hệ thống
    Route::get('/cai-dat', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/cai-dat', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/api/settings', [SettingController::class, 'publicApi'])->name('settings.api');

    // Quản lý mã coupon
    Route::prefix('coupon')->name('coupons.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::post('/', [CouponController::class, 'store'])->name('store');
        Route::put('/{coupon}', [CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('destroy');
    });

    // Quản lý đơn hàng
    Route::prefix('don-hang')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}/trang-thai', [OrderController::class, 'updateStatus'])->name('status');
        Route::post('/{order}/gui-email', [OrderController::class, 'sendEmail'])->name('send-email');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
    });

    // Quản lý sản phẩm
    Route::prefix('san-pham')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/them-moi', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/sua', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/clone', [ProductController::class, 'clone'])->name('clone');
        Route::post('/{id}/toggle', [ProductController::class, 'toggleActive'])->name('toggle');
    });

    // Quản lý license
    Route::prefix('licenses')->name('licenses.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LicenseController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\LicenseController::class, 'store'])->name('store');
        Route::post('/send-email', [\App\Http\Controllers\Admin\LicenseController::class, 'sendEmail'])->name('send_email');
        Route::delete('/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'destroy'])->name('destroy');
    });

    // Quản lý hạn dùng khách hàng (Subscriptions)
    Route::prefix('han-khach-hang')->name('subscriptions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('index');
        Route::put('/{id}/gia-han', [\App\Http\Controllers\Admin\SubscriptionController::class, 'extend'])->name('extend');
    });

    // Quản lý danh mục
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    // Quản lý người dùng
    Route::prefix('nguoi-dung')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/them-moi', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/sua', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::patch('/{user}/khoa', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Lập chỉ mục Google
    Route::prefix('lap-chi-muc')->name('indexing.')->group(function () {
        Route::get('/', [IndexingController::class, 'index'])->name('index');
        Route::post('/gui', [IndexingController::class, 'submit'])->name('submit');
    });

    // Quản lý bài viết
    Route::prefix('bai-viet')->name('posts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('index');
        Route::get('/them-moi', [\App\Http\Controllers\Admin\PostController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\PostController::class, 'store'])->name('store');
        Route::get('/{id}/sua', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('destroy');
    });
});


