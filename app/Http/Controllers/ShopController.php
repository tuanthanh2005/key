<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $allProducts = \App\Models\Product::where('status', 'active')->get()->toArray();
        return view('products', compact('allProducts'));
    }

    /**
     * Chi tiết sản phẩm theo slug thương hiệu
     */
    public function productDetail($slug)
    {
        $validSlugs = ['nordvpn', 'expressvpn', 'surfshark', 'hma', 'cyberghost', 'purevpn', 'ipvanish', 'protonvpn'];

        if (!in_array($slug, $validSlugs)) {
            abort(404, 'Sản phẩm không tồn tại');
        }

        $dbProducts = \App\Models\Product::where('status', 'active')
            ->where('slug', $slug)
            ->get();

        $defaultProduct = $dbProducts->first();
        $brandName = $defaultProduct ? $defaultProduct->brand : '';

        $dbReviews = \App\Models\Order::where('brand', $brandName)
            ->whereNotNull('review_rating')
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

        if (empty($realReviews)) {
            $realReviews = [
                ['name'=>'Trần Quốc Bảo','star'=>5,'date'=>'2 ngày trước','text'=>'Mua key ' . $brandName . ' ở đây kích hoạt nhanh thật sự, giao dịch tự động trong 5 phút. Tốc độ ổn định và load phim 4K vi vu.'],
                ['name'=>'Nguyễn Minh Tuấn','star'=>5,'date'=>'5 ngày trước','text'=>'Shop uy tín, hỗ trợ kích hoạt qua Zalo siêu nhiệt tình và nhanh chóng. Giá rẻ hơn nhiều so với mua trực tiếp trên trang chủ.'],
                ['name'=>'Phan Hoàng Long','star'=>4,'date'=>'1 tuần trước','text'=>'Sản phẩm hoạt động tốt, dùng mượt mà trên cả điện thoại và PC. Trừ 1 sao vì lúc thanh toán chờ quét mã hơi lâu chút nhưng vẫn ổn.'],
                ['name'=>'Hoàng Thị Mai','star'=>5,'date'=>'2 tuần trước','text'=>'Đã mua gói 1 năm ' . $brandName . ', key hoạt động chuẩn. Chạy mượt mà, đổi IP nhanh chóng và giúp mình làm việc remote an toàn.'],
                ['name'=>'Lê Thanh Hải','star'=>5,'date'=>'3 tuần trước','text'=>'Dịch vụ tuyệt vời. Key bị lỗi nhỏ được shop đổi mới ngay lập tức trong 2 phút. Rất hài lòng với chế độ bảo hành chu đáo.'],
                ['name'=>'Đặng Phương Thảo','star'=>5,'date'=>'1 tháng trước','text'=>'Được bạn giới thiệu mua ở đây. Giao diện web trực quan, thanh toán tiện lợi và chất lượng key ' . $brandName . ' hoàn hảo không có gì để chê.'],
            ];
        }

        return view('product-detail', compact('slug', 'dbProducts', 'realReviews'));
    }

    /**
     * Giỏ hàng
     */
    public function cart()
    {
        return view('cart');
    }

    /**
     * Trang thanh toán
     */
    public function checkout()
    {
        return view('checkout');
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
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        $order = \App\Models\Order::where('order_code', $request->order_code)->firstOrFail();

        if ($order->order_status !== 'completed') {
            return redirect()->back()->with('error', 'Chỉ có thể đánh giá đơn hàng đã hoàn tất.');
        }

        if ($order->review_rating !== null) {
            return redirect()->back()->with('error', 'Đơn hàng này đã được đánh giá trước đó.');
        }

        $order->update([
            'review_rating' => $request->rating,
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
            $count = $orders->count();
            foreach ($orders as $o) {
                $totalRating += $o->review_rating;
            }

            // Gộp đánh giá thật vào mốc base mặc định của sản phẩm
            $baseReviews = intval($product->reviews ?: 120);
            $baseRating = floatval($product->rating ?: 4.8);
            
            $newReviewsCount = $baseReviews + $count;
            $newAverageRating = round((($baseRating * $baseReviews) + $totalRating) / $newReviewsCount, 1);

            $product->update([
                'rating' => $newAverageRating,
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
                'order_code' => 'required|string|max:50|unique:orders,order_code',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'telegram' => 'nullable|string|max:255',
                'note' => 'nullable|string',
                'payment_method' => 'required|in:bank_transfer,momo,zalopay,crypto',
                'cart' => 'required|array|min:1',
            ]);

            $cart = $request->cart;
            $productNames = [];
            $brands = [];
            $plans = [];
            $totalQty = 0;
            $subtotal = 0;

            foreach ($cart as $item) {
                $qty = intval($item['qty'] ?? 1);
                $price = floatval($item['price'] ?? 0);
                
                $productNames[] = ($item['name'] ?? 'VPN Product') . ' (' . ($item['plan'] ?? '1 Year') . ') x' . $qty;
                $brands[] = $item['brand'] ?? 'VPN';
                $plans[] = $item['plan'] ?? '1year';
                $totalQty += $qty;
                $subtotal += $price * $qty;
            }

            $productName = implode(', ', $productNames);
            $brand = !empty($brands) ? $brands[0] : 'VPN';
            $plan = !empty($plans) ? $plans[0] : '1year';

            // Discount: 5% if subtotal > 500,000 VND
            $discount = $subtotal > 500000 ? round($subtotal * 0.05) : 0;
            $total = $subtotal - $discount;

            $order = \App\Models\Order::create([
                'order_code' => $request->order_code,
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'product_name' => $productName,
                'brand' => $brand,
                'plan' => $plan,
                'quantity' => $totalQty,
                'price' => count($cart) === 1 ? floatval($cart[0]['price'] ?? 0) : $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending', // default order status is pending (Chờ xử lý)
                'note' => $request->note . ($request->telegram ? ' | Telegram/Zalo: ' . $request->telegram : ''),
            ]);

            return response()->json([
                'success' => true,
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
        return view('pricing');
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
        $q = strtolower($request->q ?? '');
        $allProductsQuery = \App\Models\Product::where('status', 'active');
        if ($q) {
            $allProductsQuery->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%");
            });
        }
        $allProducts = $allProductsQuery->get()->toArray();
        return view('search', compact('allProducts'));
    }

    public function orderHistory()
    {
        $user = auth()->user();
        $orders = \App\Models\Order::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order-history', compact('orders'));
    }
}
