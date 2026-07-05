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
        return view('products', compact('allProducts'));
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

        // Fake reviews: kiểm tra setting show_fake_reviews
        if (empty($realReviews) && Setting::get('show_fake_reviews', '1') == '1') {
            $poolNames = [
                'Trần Quốc Bảo', 'Nguyễn Minh Tuấn', 'Phan Hoàng Long', 'Hoàng Thị Mai', 'Lê Thanh Hải',
                'Đặng Phương Thảo', 'Phạm Minh Đức', 'Vũ Hoài Nam', 'Đỗ Thu Hà', 'Bùi Tiến Dũng',
                'Ngô Anh Tú', 'Dương Khánh Linh', 'Hoàng Văn Quyết', 'Lâm Gia Bảo', 'Đinh Thị Thu',
                'Lý Hải Đăng', 'Tạ Minh Quang', 'Trịnh Kim Chi', 'Phùng Xuân Tiến', 'Mai Quốc Khánh',
                'Đặng Ngọc Anh', 'Bùi Hữu Phước', 'Cao Minh Triết', 'Nguyễn Nhật Nam', 'Trần Thế Vinh',
                'Huỳnh Ngọc Diệp', 'Đỗ Minh Khang', 'Lê Cát Tường', 'Nguyễn Bích Liên', 'Phạm Hùng Dũng'
            ];

            $pool5Star = [
                'Mua key :brand ở đây kích hoạt nhanh thật sự, giao dịch tự động trong 5 phút. Tốc độ ổn định và load phim 4K vi vu.',
                'Shop uy tín, hỗ trợ kích hoạt qua Zalo siêu nhiệt tình và nhanh chóng. Giá rẻ hơn nhiều so với mua trực tiếp trên trang chủ.',
                'Đã mua gói 1 năm :brand, key hoạt động chuẩn. Chạy mượt mà, đổi IP nhanh chóng và giúp mình làm việc remote an toàn.',
                'Dịch vụ tuyệt vời. Key bị lỗi nhỏ được shop đổi mới ngay lập tức trong 2 phút. Rất hài lòng với chế độ bảo hành chu đáo.',
                'Được bạn giới thiệu mua ở đây. Giao diện web trực quan, thanh toán tiện lợi và chất lượng key :brand hoàn hảo không có gì để chê.',
                'Đã test thử vài ngày thấy kết nối rất ổn định, không bị ngắt kết nối giữa chừng. Chơi game server nước ngoài ping giảm đáng kể.',
                'Giá quá hời cho một tài khoản chính hãng chất lượng cao. Sẽ tiếp tục gia hạn khi hết hạn gói.',
                'Web giao hàng cực kỳ nhanh, thanh toán xong là thấy thông tin đăng nhập liền. Cảm ơn shop rất nhiều!',
                'Dùng để xem Netflix kho Mỹ mượt mà, không hề bị giật lag gì luôn. Đáng đồng tiền bát gạo!',
                'Giao diện thân thiện, dễ cài đặt và kết nối cực kỳ nhanh. Rất khuyên dùng dịch vụ của bên shop này nha mọi người.',
                'Support tận tình hết mức, 12h đêm vẫn online hướng dẫn mình setup trên router. Vote 5 sao nhiệt tình.',
                'Tốc độ download không bị bóp băng thông nhiều, dùng làm việc và giải trí đều mượt. Rất đáng mua.',
                'Đăng nhập phát ăn ngay, chuẩn tài khoản premium luôn. Giá rẻ mà chất lượng đỉnh thế này thì quá tốt rồi.',
                'Đã mua lần thứ 3 ở shop và chưa bao giờ làm mình thất vọng. Chúc shop luôn buôn may bán đắt!',
                'Trải nghiệm tuyệt vời, không còn lo bị chặn các trang web nước ngoài nữa. Cực kỳ hài lòng.',
                'Key kích hoạt cực kỳ nhanh, dùng cho máy tính và điện thoại cùng lúc đều ổn định, không lỗi lầm gì.',
                'Mọi tính năng hoạt động hoàn hảo, tốc độ vượt trội hơn hẳn so với mấy bên VPN miễn phí.',
                'Tài khoản dùng ổn định suốt mấy tháng nay chưa phải bảo hành lần nào. Chất lượng tuyệt đối!',
                'Đơn giản, dễ sử dụng, bảo mật tốt mà giá cả lại phải chăng. Rất hài lòng với dịch vụ :brand tại đây.',
                'Giao dịch an toàn, uy tín. Nhận hàng sau 1 phút. Cảm ơn shop rất nhiều nhé.'
            ];

            $pool4Star = [
                'Sản phẩm hoạt động tốt, dùng mượt mà trên cả điện thoại và PC. Trừ 1 sao vì lúc thanh toán chờ quét mã hơi lâu chút nhưng vẫn ổn.',
                'Mua cho cả nhà dùng chung, mọi người đều khen kết nối ổn định. Chỉ tiếc là không có hướng dẫn chi tiết cho SmartTV đời cũ.',
                'Khá ấn tượng với tốc độ phản hồi của admin, xử lý đơn hàng chuyên nghiệp. Tốc độ kết nối ở vài server Châu Á hơi chậm một tí vào giờ cao điểm.',
                'Gói cước đa dạng phù hợp nhu cầu. Dùng tốt nhưng thi thoảng bị tự động log out trên app điện thoại, phải đăng nhập lại.',
                'Tiết kiệm được một khoản tiền khá lớn so với mua trực tiếp. Nói chung là chất lượng ổn áp, thỉnh thoảng giật nhẹ lúc chuyển server.',
                'Shop bảo hành 1 đổi 1 làm mình thấy rất yên tâm khi mua sắm. Kết nối thi thoảng bị drop nhưng bật lại là được ngay.'
            ];

            $pool3Star = [
                'Lúc đầu mua về key bị lỗi không kích hoạt được, nhắn tin Zalo support hỗ trợ đổi key khác thì mới dùng được. Hơi mất thời gian tí nhưng được cái hỗ trợ nhiệt tình.',
                'Dùng tạm ổn. Nhưng tốc độ kết nối VPN của :brand lúc đứt cáp quang biển bị chậm thấy rõ, load web hơi oải. Hết đứt cáp thì bình thường.',
                'Tài khoản thi thoảng bị quá số thiết bị đăng nhập làm mình bị out ra. Phải nhờ shop reset lại thiết bị. Hy vọng shop khắc phục lỗi chia sẻ tài khoản này.',
                'Setup trên hệ điều hành Linux hơi phức tạp, tự làm không được phải nhờ kỹ thuật ultraview giúp. Giao diện app trên Win thì dễ dùng hơn.'
            ];

            shuffle($poolNames);
            shuffle($pool5Star);
            shuffle($pool4Star);
            shuffle($pool3Star);

            $countToDisplay = rand(6, 10);
            
            for ($i = 0; $i < $countToDisplay; $i++) {
                $days = rand(1, 30);
                $dateText = ($days == 1) ? 'Hôm qua' : "{$days} ngày trước";
                if ($days > 7) {
                    $weeks = floor($days / 7);
                    $dateText = "{$weeks} tuần trước";
                }
                if ($days > 28) {
                    $dateText = '1 tháng trước';
                }

                // Star distribution: 75% for 5-star, 18% for 4-star, 7% for 3-star
                $randVal = rand(1, 100);
                if ($randVal <= 75) {
                    $rating = 5;
                    $rawComment = $pool5Star[$i % count($pool5Star)];
                } elseif ($randVal <= 93) {
                    $rating = 4;
                    $rawComment = $pool4Star[$i % count($pool4Star)];
                } else {
                    $rating = 3;
                    $rawComment = $pool3Star[$i % count($pool3Star)];
                }

                $commentText = str_replace(':brand', $brandName, $rawComment);

                $realReviews[] = [
                    'name' => $poolNames[$i],
                    'star' => $rating,
                    'date' => $dateText,
                    'text' => $commentText
                ];
            }
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
        $products = \App\Models\Product::get(['brand', 'plan', 'stock']);
        $stockMap = [];
        foreach ($products as $p) {
            $key = strtolower(str_replace(' ', '', $p->brand)) . '_' . strtolower($p->plan);
            $stockMap[$key] = $p->stock;
        }
        return view('checkout', ['stockMap' => $stockMap]);
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

                    \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
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
     * Bảng giá
     */
    public function pricing()
    {
        $activeProducts = \App\Models\Product::where('status', 'active')->get();
        return view('pricing', compact('activeProducts'));
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
     * Tìm kiếm sản phẩm
     */
    public function search(Request $request)
    {
        $q                = strtolower($request->q ?? '');
        $allProductsQuery = \App\Models\Product::where('status', 'active');
        if ($q) {
            $allProductsQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%");
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
}
