<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryTypeController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\IndexingController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SocialAuthController;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| VPNStore - Web Routes
|--------------------------------------------------------------------------
*/

if (app()->isLocal()) {
    Route::get('/clear-cache', function () {
        Artisan::call('optimize:clear');

        return 'Laravel caches cleared successfully.';
    });
}

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
Route::post('/lien-he', [ShopController::class, 'storeContact'])->name('contact.store');
Route::get('/tim-kiem', [ShopController::class, 'search'])->name('search');
Route::get('/tin-tuc', [ShopController::class, 'postList'])->name('posts.index');
Route::get('/tin-tuc/{slug}', [ShopController::class, 'postDetail'])->name('posts.show');

// =============================================
// XML Sitemap
// =============================================
Route::get('/sitemap.xml', function () {
    // Get all Category slugs (e.g. vpn, chatgpt, netflix brand pages)
    $categorySlugs = Category::pluck('slug')->toArray();

    // Get all active Product slugs
    $productSlugs = Product::where('status', 'active')
        ->whereNotNull('slug')
        ->pluck('slug')
        ->toArray();

    // Merge and unique them so both brand hubs and specific products are indexed
    $brands = array_unique(array_filter(array_merge($categorySlugs, $productSlugs)));

    // Get published posts
    $posts = Post::published()
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

// Live Chat Public API
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::post('/chat/mark-read', [ChatController::class, 'markAsRead'])->name('chat.mark-read');

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
        Route::get('/', [LicenseController::class, 'index'])->name('index');
        Route::post('/', [LicenseController::class, 'store'])->name('store');
        Route::post('/send-email', [LicenseController::class, 'sendEmail'])->name('send_email');
        Route::delete('/{license}', [LicenseController::class, 'destroy'])->name('destroy');
    });

    // Quản lý hạn dùng khách hàng (Subscriptions)
    Route::prefix('han-khach-hang')->name('subscriptions.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::put('/{id}/gia-han', [SubscriptionController::class, 'extend'])->name('extend');
    });

    // Quản lý danh mục
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    // Quản lý loại danh mục (Category Types)
    Route::prefix('loai-danh-muc')->name('category-types.')->group(function () {
        Route::get('/', [CategoryTypeController::class, 'index'])->name('index');
        Route::post('/', [CategoryTypeController::class, 'store'])->name('store');
        Route::put('/{id}', [CategoryTypeController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryTypeController::class, 'destroy'])->name('destroy');
    });

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
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/them-moi', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{id}/sua', [PostController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
    });

    // Tiếp nhận email (Liên hệ)
    Route::prefix('tiep-nhan-email')->name('contacts.')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::get('/{contact}', [ContactController::class, 'show'])->name('show');
        Route::post('/{contact}/reply', [ContactController::class, 'reply'])->name('reply');
        Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('destroy');
    });

    // Live Chat Admin
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [AdminChatController::class, 'index'])->name('index');
        Route::get('/sessions', [AdminChatController::class, 'getSessions'])->name('sessions');
        Route::get('/messages/{sessionId}', [AdminChatController::class, 'getMessages'])->name('messages');
        Route::post('/send', [AdminChatController::class, 'sendMessage'])->name('send');
    });
});
