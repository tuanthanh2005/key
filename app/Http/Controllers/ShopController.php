<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Coupon;

class ShopController extends Controller
{
    /**
     * Trang chủ
     */
    public function home()
    {
        $allProducts = \App\Models\Product::where('status', 'active')->get()->toArray();
        return view('home', compact('allProducts'));
    }

    /**
     * Danh sách sản phẩm
     */
    public function products(Request $request)
    {
        $allProducts = \App\Models\Product::with('category')->where('status', 'active')->get()->toArray();
        $categories = \App\Models\Category::withCount(['products' => function($q) {
            $q->where('status', 'active');
        }])->get();

        $categorySlug = $request->query('category');
        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = $categories->where('slug', $categorySlug)->first();
        }

        return view('products', compact('allProducts', 'categories', 'selectedCategory'));
    }

    /**
     * Chi tiết sản phẩm theo slug thương hiệu – slug lấy dynamic từ DB
     */
    public function productDetail($slug)
    {
        $category = \App\Models\Category::where('slug', $slug)->first();
        if ($category) {
            $dbProducts = \App\Models\Product::where('status', 'active')
                ->where('category_id', $category->id)
                ->get();
        } else {
            $dbProducts = \App\Models\Product::where('status', 'active')
                ->where('slug', $slug)
                ->get();
        }

        if ($dbProducts->isEmpty()) {
            abort(404, 'Sản phẩm không tồn tại');
        }

        $defaultProduct = $dbProducts->first();
        $brandName = $defaultProduct ? $defaultProduct->brand : '';

        $dbReviews = \App\Models\Order::where('brand', $brandName)
            ->whereNotNull('review_rating')
            ->whereNotNull('review_comment')
            ->where('review_comment', '!=', '')
            ->orderBy('updated_at', 'desc')
            ->get();

        $realReviews = [];
        foreach ($dbReviews as $o) {
            $realReviews[] = [
                'name' => $o->customer_name,
                'star' => $o->review_rating,
                'date' => $o->updated_at->diffForHumans(),
                'text' => $o->review_comment
            ];
        }



        return view('product-detail', compact('slug', 'dbProducts', 'realReviews', 'category'));
    }

    /**
     * Giỏ hàng
     */
    public function cart()
    {
        $products = \App\Models\Product::get(['brand', 'plan', 'stock']);
        $stockMap = [];
        foreach ($products as $p) {
            $key = strtolower(str_replace(' ', '', $p->brand)) . '_' . strtolower($p->plan);
            $stockMap[$key] = $p->stock;
        }
        return view('cart', ['stockMap' => $stockMap]);
    }

    /**
     * Trang thanh toán
     */
    public function checkout()
    {
        $products = \App\Models\Product::get(['id', 'brand', 'plan', 'stock', 'require_upgrade_email']);
        $stockMap = [];
        $emailRequireMap = [];
        foreach ($products as $p) {
            $key = strtolower(str_replace(' ', '', $p->brand)) . '_' . strtolower($p->plan);
            $stockMap[$key] = $p->stock;
            $emailRequireMap[$p->id] = (bool)$p->require_upgrade_email;
        }
        return view('checkout', [
            'stockMap' => $stockMap,
            'emailRequireMap' => $emailRequireMap
        ]);
    }

    /**
     * Bật/Tắt sản phẩm yêu thích (Wishlist)
     */
    public function toggleWishlist(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào danh sách yêu thích!'
            ], 401);
        }

        $id = $request->product_id;
        if (!$id) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không hợp lệ!'], 400);
        }

        $product = \App\Models\Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại!'], 404);
        }

        $wishlist = \App\Models\Wishlist::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $action = 'removed';
            $msg = 'Đã xóa khỏi danh sách yêu thích!';
        } else {
            \App\Models\Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $id
            ]);
            $action = 'added';
            $msg = 'Đã thêm vào danh sách yêu thích!';
        }

        $userWishlistIds = \App\Models\Wishlist::where('user_id', auth()->id())
            ->pluck('product_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'action' => $action,
            'message' => $msg,
            'wishlist' => $userWishlistIds
        ]);
    }

    /**
     * Trang sản phẩm yêu thích
     */
    public function wishlistPage()
    {
        $wishlistIds = \App\Models\Wishlist::where('user_id', auth()->id())
            ->pluck('product_id')
            ->toArray();

        $allProducts = \App\Models\Product::where('status', 'active')
            ->whereIn('id', $wishlistIds)
            ->get()
            ->toArray();

        return view('wishlist', compact('allProducts'));
    }

    /**
     * Đặt hàng thành công
     */
    public function orderSuccess(Request $request)
    {
        return view('order-success');
    }

    /**
     * Tra cứu đơn hàng
     */
    public function orderCheck(Request $request)
    {
        $order = null;
        if ($request->order) {
            $order = \App\Models\Order::where('order_code', $request->order)->first();
        }
        return view('order-check', compact('order'));
    }

    /**
     * Gửi đánh giá đơn hàng
     */
    public function submitOrderReview(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string|exists:orders,order_code',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $order = \App\Models\Order::where('order_code', $request->order_code)->firstOrFail();

        if ($order->order_status !== 'completed') {
            return redirect()->back()->with('error', 'Chỉ có thể đánh giá đơn hàng đã hoàn tất.');
        }

        if ($order->review_rating !== null) {
            return redirect()->back()->with('error', 'Đơn hàng này đã được đánh giá trước đó.');
        }

        $order->update([
            'review_rating'  => $request->rating,
            'review_comment' => $request->comment,
        ]);

        // Cập nhật rating và reviews cho sản phẩm tương ứng
        $product = \App\Models\Product::where('brand', $order->brand)
            ->where('plan', $order->plan)
            ->first();

        if ($product) {
            $orders = \App\Models\Order::where('brand', $order->brand)
                ->where('plan', $order->plan)
                ->whereNotNull('review_rating')
                ->get();

            $totalRating = 0;
            $count       = $orders->count();
            foreach ($orders as $o) {
                $totalRating += $o->review_rating;
            }

            $baseReviews      = intval($product->reviews ?: Setting::get('default_product_reviews', 120));
            $baseRating       = floatval($product->rating  ?: Setting::get('default_product_rating', 4.8));
            $newReviewsCount  = $baseReviews + $count;
            $newAverageRating = round((($baseRating * $baseReviews) + $totalRating) / $newReviewsCount, 1);

            $product->update([
                'rating'  => $newAverageRating,
                'reviews' => $newReviewsCount,
            ]);
        }

        return redirect()->route('order.check', ['order' => $order->order_code])
            ->with('success', 'Cảm ơn bạn đã gửi đánh giá sản phẩm!');
    }

    /**
     * Lưu đơn hàng mới từ checkout
     */
    public function storeOrder(Request $request)
    {
        try {
            $request->validate([
                'order_code'     => 'required|string|max:50|unique:orders,order_code',
                'customer_name'  => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'telegram'       => 'nullable|string|max:255',
                'note'           => 'nullable|string',
                'payment_method' => 'required|in:bank_transfer,momo,zalopay,crypto',
                'coupon'         => 'nullable|string|max:50',
                'cart'           => 'required|array|min:1',
            ]);

            $cart         = $request->cart;
            $productNames = [];
            $brands       = [];
            $plans        = [];
            $totalQty     = 0;
            $subtotal     = 0;

            foreach ($cart as $item) {
                $qty   = intval($item['qty'] ?? 1);
                $price = floatval($item['price'] ?? 0);

                // Backend verification: verify stock level in DB
                $dbProd = \App\Models\Product::where('brand', $item['brand'] ?? '')
                    ->where('plan', $item['plan'] ?? '')
                    ->first();
                if ($dbProd && $dbProd->stock <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sản phẩm ' . ($item['name'] ?? 'VPN') . ' hiện đang hết hàng. Vui lòng xóa khỏi giỏ hàng.'
                    ], 422);
                }

                $productNames[] = ($item['name'] ?? 'VPN Product') . ' (' . ($item['plan'] ?? '1 Year') . ') x' . $qty;
                $brands[]       = $item['brand'] ?? 'VPN';
                $plans[]        = $item['plan'] ?? '1year';
                $totalQty       += $qty;
                $subtotal       += $price * $qty;
            }

            $productName = implode(', ', $productNames);
            $brand       = !empty($brands) ? $brands[0] : 'VPN';
            $plan        = !empty($plans)  ? $plans[0]  : '1year';

            // Auto Discount: lấy ngưỡng và tỷ lệ từ settings
            $threshold    = (float) Setting::get('auto_discount_threshold', 500000);
            $rate         = (float) Setting::get('auto_discount_rate', 5);
            $autoDiscount = $subtotal > $threshold ? round($subtotal * $rate / 100) : 0;

            // Coupon Discount: validate từ DB
            $coupon         = $request->coupon;
            $couponDiscount = 0;

            if ($coupon) {
                $couponModel = Coupon::valid()->where('code', strtoupper($coupon));
                if (\Illuminate\Support\Facades\Schema::hasColumn('coupons', 'user_id')) {
                    $couponModel->where(function ($q) {
                        $q->whereNull('user_id')
                          ->orWhere('user_id', auth()->id());
                    });
                }
                $couponModel = $couponModel->first();
                if ($couponModel && $subtotal >= $couponModel->min_order) {
                    $couponDiscount = $couponModel->calculateDiscount($subtotal);
                    $couponModel->increment('used_count');
                }
            }

            $totalDiscount = $autoDiscount + $couponDiscount;
            $total         = max(0, $subtotal - $totalDiscount);

            $order = \App\Models\Order::create([
                'order_code'     => $request->order_code,
                'user_id'        => auth()->id(),
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'product_name'   => $productName,
                'brand'          => $brand,
                'plan'           => $plan,
                'quantity'       => $totalQty,
                'price'          => count($cart) === 1 ? floatval($cart[0]['price'] ?? 0) : $subtotal,
                'discount'       => $totalDiscount,
                'coupon'         => $coupon,
                'total'          => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status'   => 'pending',
            ]);

            // Send Telegram Notification
            try {
                $botToken = env('TELEGRAM_BOT_TOKEN');
                $chatId = env('TELEGRAM_CHAT_ID');

                if ($botToken && $chatId) {
                    $text = "🔔 *ĐƠN HÀNG MỚI TỪ AI CỦA TÔI . COM*\n\n"
                          . "📦 *Mã đơn*: `" . $order->order_code . "`\n"
                          . "👤 *Khách hàng*: " . $order->customer_name . "\n"
                          . "✉️ *Email*: " . $order->customer_email . "\n"
                          . "📞 *Số điện thoại*: " . $order->customer_phone . "\n"
                          . "🛍️ *Sản phẩm*: " . $order->product_name . "\n"
                          . "💰 *Tổng tiền*: " . number_format($order->total) . "đ\n"
                          . "💳 *Phương thức*: " . ($order->payment_method === 'bank_transfer' ? 'Chuyển khoản ngân hàng' : $order->payment_method) . "\n"
                          . "📝 *Ghi chú*: " . ($order->note ?: 'Không có');

                    \Illuminate\Support\Facades\Http::timeout(3)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $text,
                        'parse_mode' => 'Markdown',
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Telegram notification error: ' . $e->getMessage());
            }

            return response()->json([
                'success'    => true,
                'order_code' => $order->order_code
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Giới thiệu
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Liên hệ
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Lưu tin nhắn liên hệ
     */
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        \App\Models\Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tin nhắn của bạn đã được gửi thành công! Chúng tôi sẽ phản hồi qua email sớm nhất.',
        ]);
    }

    /**
     * Tìm kiếm sản phẩm
     */
    public function search(Request $request)
    {
        $q                = strtolower($request->q ?? '');
        $allProductsQuery = \App\Models\Product::where('status', 'active');
        if ($q) {
            $allProductsQuery->where(function ($sub) use ($q) {
                // 1. Exact match in name, brand, or description
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
                
                // 2. Space-stripped match (e.g. "chat gpt" -> "chatgpt")
                $stripped = str_replace(' ', '', $q);
                if ($stripped !== $q) {
                    $sub->orWhere('name', 'like', "%{$stripped}%")
                        ->orWhere('brand', 'like', "%{$stripped}%");
                }
                
                // 3. Keyword matching (words >= 2 chars)
                $words = array_filter(explode(' ', $q));
                if (count($words) > 1) {
                    foreach ($words as $word) {
                        if (mb_strlen($word) >= 2) {
                            $sub->orWhere('name', 'like', "%{$word}%")
                                ->orWhere('brand', 'like', "%{$word}%");
                        }
                    }
                }
            });
        }
        $allProducts = $allProductsQuery->get()->toArray();
        return view('search', compact('allProducts'));
    }

    public function orderHistory()
    {
        $user   = auth()->user();
        $orders = \App\Models\Order::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order-history', compact('orders'));
    }

    /**
     * Trang danh sách bài viết công khai
     */
    public function postList()
    {
        $posts = \App\Models\Post::published()->orderBy('created_at', 'desc')->paginate(9);
        return view('posts.index', compact('posts'));
    }

    /**
     * Trang chi tiết bài viết công khai
     */
    public function postDetail($slug)
    {
        $post = \App\Models\Post::published()->where('slug', $slug)->firstOrFail();
        
        // Bài viết liên quan / mới nhất khác
        $recentPosts = \App\Models\Post::published()
            ->where('id', '!=', $post->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Sản phẩm hot
        $hotProducts = \App\Models\Product::where('status', 'active')
            ->where('is_popular', true)
            ->limit(5)
            ->get();

        if ($hotProducts->isEmpty()) {
            $hotProducts = \App\Models\Product::where('status', 'active')
                ->limit(5)
                ->get();
        }

        return view('posts.detail', compact('post', 'recentPosts', 'hotProducts'));
    }
}
