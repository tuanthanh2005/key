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
   
/*
|--------------------------------------------------------------------------
| VPNStore - Web Routes
|--------------------------------------------------------------------------
*/

// =============================================
// TRANG CÔNG KHAI (SHOP)
// =============================================
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/san-pham', [ShopController::class, 'products'])->name('products');
Route::get('/san-pham/{slug}', [ShopController::class, 'productDetail'])
    ->where('slug', '[a-z0-9\-]+')
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
Route::get('/bang-gia', [ShopController::class, 'pricing'])->name('pricing');
Route::get('/gioi-thieu', [ShopController::class, 'about'])->name('about');
Route::get('/lien-he', [ShopController::class, 'contact'])->name('contact');
Route::get('/tim-kiem', [ShopController::class, 'search'])->name('search');

// =============================================
// XML Sitemap
// =============================================
Route::get('/sitemap.xml', function () {
    // Lấy slug từ DB thay vì hardcode
    $brands = \App\Models\Product::where('status', 'active')
        ->whereNotNull('slug')
        ->distinct()
        ->pluck('slug')
        ->toArray();
    return response()->view('sitemap', compact('brands'))
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
});


