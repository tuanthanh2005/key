<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'type'             => 'required|in:account,license,subscription',
            'price'            => 'required|numeric|min:0',
            'original_price'   => 'nullable|numeric|min:0',
            'duration'         => 'nullable|string|max:100',
            'duration_days'    => 'required|integer|min:1',
            'stock'            => 'required|integer|min:-1',
            'is_active'        => 'boolean',
            'is_popular'       => 'boolean',
            'is_featured'      => 'boolean',
            'require_upgrade_email' => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'features'         => 'nullable|array',
            'features.*'       => 'string',
            'servers'          => 'nullable|string|max:255',
            'countries'        => 'nullable|string|max:255',
            'devices'          => 'nullable|string|max:255',
            'speed'            => 'nullable|string|max:255',
            'protocol'         => 'nullable|string|max:255',
            'headquarter'      => 'nullable|string|max:255',
            'founded'          => 'nullable|string|max:255',
            'refund'           => 'nullable|string|max:255',
            'specs_names'      => 'nullable|array',
            'specs_names.*'    => 'nullable|string|max:255',
            'specs_values'     => 'nullable|array',
            'specs_values.*'   => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        $data['is_active']  = $request->boolean('is_active');
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_featured']= $request->boolean('is_featured');
        $data['require_upgrade_email'] = $request->boolean('require_upgrade_email');

        $specs = [];
        if ($request->has('specs_names') && $request->has('specs_values')) {
            $names = $request->input('specs_names');
            $values = $request->input('specs_values');
            foreach ($names as $index => $name) {
                $val = $values[$index] ?? '';
                if (trim($name) !== '' || trim($val) !== '') {
                    $specs[] = [
                        'name' => trim($name),
                        'value' => trim($val)
                    ];
                }
            }
        }
        $data['specs'] = $specs;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/products', 'public_uploads');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'type'             => 'required|in:account,license,subscription',
            'price'            => 'required|numeric|min:0',
            'original_price'   => 'nullable|numeric|min:0',
            'duration'         => 'nullable|string|max:100',
            'duration_days'    => 'required|integer|min:1',
            'stock'            => 'required|integer|min:-1',
            'is_active'        => 'boolean',
            'is_popular'       => 'boolean',
            'is_featured'      => 'boolean',
            'require_upgrade_email' => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'features'         => 'nullable|array',
            'features.*'       => 'string',
            'servers'          => 'nullable|string|max:255',
            'countries'        => 'nullable|string|max:255',
            'devices'          => 'nullable|string|max:255',
            'speed'            => 'nullable|string|max:255',
            'protocol'         => 'nullable|string|max:255',
            'headquarter'      => 'nullable|string|max:255',
            'founded'          => 'nullable|string|max:255',
            'refund'           => 'nullable|string|max:255',
            'specs_names'      => 'nullable|array',
            'specs_names.*'    => 'nullable|string|max:255',
            'specs_values'     => 'nullable|array',
            'specs_values.*'   => 'nullable|string|max:255',
        ]);

        $data['is_active']  = $request->boolean('is_active');
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_featured']= $request->boolean('is_featured');
        $data['require_upgrade_email'] = $request->boolean('require_upgrade_email');

        $specs = [];
        if ($request->has('specs_names') && $request->has('specs_values')) {
            $names = $request->input('specs_names');
            $values = $request->input('specs_values');
            foreach ($names as $index => $name) {
                $val = $values[$index] ?? '';
                if (trim($name) !== '' || trim($val) !== '') {
                    $specs[] = [
                        'name' => trim($name),
                        'value' => trim($val)
                    ];
                }
            }
        }
        $data['specs'] = $specs;

        if ($request->hasFile('image')) {
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public_uploads')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('uploads/products', 'public_uploads');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public_uploads')->delete($product->image);
        }
        $product->delete();
        return back()->with('success', 'Sản phẩm đã được xóa!');
    }

    public function toggleActive($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Trạng thái đã được cập nhật!');
    }

    public function updateRating(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'rating'  => 'required|numeric|min:1|max:5',
            'reviews' => 'required|integer|min:0',
        ]);

        $product->update([
            'rating'  => $request->rating,
            'reviews' => $request->reviews,
        ]);

        return back()->with('success', 'Đánh giá sản phẩm đã được cập nhật!');
    }

    public function storeFakeReview(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|max:1000',
        ]);

        // Create the fake order with a review
        $orderCode = 'FAKE' . strtoupper(Str::random(8));
        \App\Models\Order::create([
            'order_code'     => $orderCode,
            'user_id'        => null,
            'customer_name'  => $request->customer_name,
            'customer_email' => 'fake_' . Str::random(5) . '@gmail.com',
            'customer_phone' => '090' . rand(1000000, 9999999),
            'product_name'   => $product->name,
            'brand'          => $product->brand,
            'plan'           => $product->plan,
            'quantity'       => 1,
            'price'          => $product->price,
            'total'          => $product->price,
            'payment_method' => 'CK',
            'payment_status' => 'paid',
            'order_status'   => 'completed',
            'is_reviewed'    => true,
            'review_rating'  => $request->rating,
            'review_comment' => $request->comment,
            'created_at'     => now()->subHours(rand(1, 240)), // past 10 days
            'updated_at'     => now()->subHours(rand(1, 240)),
        ]);

        // Recalculate product rating/reviews
        $orders = \App\Models\Order::where('brand', $product->brand)
            ->where('plan', $product->plan)
            ->whereNotNull('review_rating')
            ->get();

        $totalRating = 0;
        $count       = $orders->count();
        foreach ($orders as $o) {
            $totalRating += $o->review_rating;
        }

        // We use the base count from settings or model defaults, or direct database values
        // Let's use the current database reviews (or default 120) and add the reviews count
        // Wait, since we are inserting a real order, if we just want to update stats:
        $baseReviews      = 120;
        $baseRating       = 4.8;
        $newReviewsCount  = $baseReviews + $count;
        $newAverageRating = round((($baseRating * $baseReviews) + $totalRating) / $newReviewsCount, 1);

        $product->update([
            'rating'  => $newAverageRating,
            'reviews' => $newReviewsCount,
        ]);

        return back()->with('success', 'Đã tạo đánh giá ảo thành công và cập nhật lại điểm trung bình!');
    }
}
