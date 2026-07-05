<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $q = $request->search;
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%");
            });
        }

        if ($request->brand) {
            $query->where('brand', $request->brand);
        }

        $products = $query->latest()->get();

        $stats = [
            'total'    => Product::count(),
            'active'   => Product::where('status', 'active')->count(),
            'total_sold' => Product::sum('sold'),
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'plan' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'features' => 'nullable|string',
            'servers' => 'nullable|string|max:255',
            'countries' => 'nullable|string|max:255',
            'devices' => 'nullable|string|max:255',
            'speed' => 'nullable|string|max:255',
            'protocol' => 'nullable|string|max:255',
            'headquarter' => 'nullable|string|max:255',
            'founded' => 'nullable|string|max:255',
            'refund' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews' => 'nullable|integer|min:0',
        ]);

        $category = \App\Models\Category::where('name', $request->brand)->first();
        $categoryId = $category ? $category->id : null;
        $slug = $category ? $category->slug : strtolower(str_replace(' ', '', $request->brand));
        // Màu: lấy từ product.color nếu có, không hardcode theo brand
        $color = '#4687FF'; // fallback mặc định

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('uploads/products'))) {
                mkdir(public_path('uploads/products'), 0777, true);
            }
            $file->move(public_path('uploads/products'), $filename);
            $imagePath = 'uploads/products/' . $filename;
        }

        $features = [];
        if ($request->features) {
            $features = array_filter(array_map('trim', explode(',', $request->features)));
        } else {
            // Lấy default features từ settings
            $defaultFeatureStr = \App\Models\Setting::get('default_product_features', 'Mã bản quyền chính hãng,Bảo mật tuyệt đối,Hỗ trợ 24/7');
            $features = array_filter(array_map('trim', explode(',', $defaultFeatureStr)));
        }

        Product::create([
            'category_id' => $categoryId,
            'name' => $request->name,
            'brand' => $request->brand,
            'slug' => $slug,
            'color' => $color,
            'price' => $request->price,
            'old_price' => $request->old_price,
            'plan' => $request->plan,
            'stock' => $request->stock,
            'status' => $request->status,
            'image_path' => $imagePath,
            'features' => $features,
            'rating' => $request->rating ?? 4.8,
            'reviews' => $request->reviews ?? 120,
            'sold' => 0,
            'servers' => $request->servers,
            'countries' => $request->countries,
            'devices' => $request->devices,
            'speed' => $request->speed,
            'protocol' => $request->protocol,
            'headquarter' => $request->headquarter,
            'founded' => $request->founded,
            'refund' => $request->refund,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Thêm gói VPN mới thành công!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'plan' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'features' => 'nullable|string',
            'servers' => 'nullable|string|max:255',
            'countries' => 'nullable|string|max:255',
            'devices' => 'nullable|string|max:255',
            'speed' => 'nullable|string|max:255',
            'protocol' => 'nullable|string|max:255',
            'headquarter' => 'nullable|string|max:255',
            'founded' => 'nullable|string|max:255',
            'refund' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews' => 'nullable|integer|min:0',
        ]);

        $category = \App\Models\Category::where('name', $request->brand)->first();
        $categoryId = $category ? $category->id : null;
        $slug = $category ? $category->slug : strtolower(str_replace(' ', '', $request->brand));
        // Giữ nguyên color từ product, hoặc fallback
        $color = $product->color ?: '#4687FF';

        $imagePath = $product->image_path;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path && file_exists(public_path($product->image_path))) {
                @unlink(public_path($product->image_path));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            if (!file_exists(public_path('uploads/products'))) {
                mkdir(public_path('uploads/products'), 0777, true);
            }

            $file->move(public_path('uploads/products'), $filename);
            $imagePath = 'uploads/products/' . $filename;
        }

        $features = [];
        if ($request->features) {
            $features = array_filter(array_map('trim', explode(',', $request->features)));
        } else {
            $defaultFeatureStr = \App\Models\Setting::get('default_product_features', 'Mã bản quyền chính hãng,Bảo mật tuyệt đối,Hỗ trợ 24/7');
            $features = $product->features ?: array_filter(array_map('trim', explode(',', $defaultFeatureStr)));
        }

        $product->update([
            'category_id' => $categoryId,
            'name' => $request->name,
            'brand' => $request->brand,
            'slug' => $slug,
            'color' => $color,
            'price' => $request->price,
            'old_price' => $request->old_price,
            'plan' => $request->plan,
            'stock' => $request->stock,
            'status' => $request->status,
            'image_path' => $imagePath,
            'features' => $features,
            'rating' => $request->rating ?? 4.8,
            'reviews' => $request->reviews ?? 120,
            'servers' => $request->servers,
            'countries' => $request->countries,
            'devices' => $request->devices,
            'speed' => $request->speed,
            'protocol' => $request->protocol,
            'headquarter' => $request->headquarter,
            'founded' => $request->founded,
            'refund' => $request->refund,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật gói VPN thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete image file if exists
        if ($product->image_path && file_exists(public_path($product->image_path))) {
            @unlink(public_path($product->image_path));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xóa gói VPN thành công!');
    }
}
