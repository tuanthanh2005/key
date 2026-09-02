<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm được nhóm theo Thương Hiệu
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('brand', 'like', $searchTerm)
                  ->orWhere('slug', 'like', $searchTerm);
            });
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $allProducts = $query->orderByDesc('created_at')->get();
        $categories = Category::all();

        // Nhóm các gói sản phẩm theo Thương Hiệu (Brand hoặc Slug)
        $groupedProducts = $allProducts->groupBy(function($item) {
            return !empty($item->brand) ? $item->brand : $item->slug;
        });

        return view('admin.products.index', compact('groupedProducts', 'categories'));
    }

    /**
     * Giao diện tạo sản phẩm mới (Hỗ trợ tạo nhiều gói trong 1 Form)
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Lưu thông tin thương hiệu và danh sách gói thời hạn
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'brand'            => 'nullable|string|max:255',
            'slug'             => 'nullable|string|max:255',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'type'             => 'required|in:account,license,subscription',
            'servers'          => 'nullable|string|max:255',
            'countries'        => 'nullable|string|max:255',
            'devices'          => 'nullable|string|max:255',
            'speed'            => 'nullable|string|max:255',
            'protocol'         => 'nullable|string|max:255',
            'headquarter'      => 'nullable|string|max:255',
            'founded'          => 'nullable|string|max:255',
            'refund'           => 'nullable|string|max:255',
            'require_upgrade_email' => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'specs_names'      => 'nullable|array',
            'specs_values'     => 'nullable|array',
            'plans'            => 'nullable|array',
            'plans.*.label'    => 'required|string',
            'plans.*.key'      => 'nullable|string',
            'plans.*.price'    => 'required|numeric|min:0',
            'plans.*.original_price' => 'nullable|numeric|min:0',
            'plans.*.duration_days'  => 'nullable|integer',
            'plans.*.stock'          => 'nullable|integer',
            'plans.*.plan_note'      => 'nullable|string',
            'plans.*.is_popular'     => 'nullable|boolean',
        ]);

        $brandName = trim($data['brand'] ?? $data['name']);
        $slug = Str::slug($data['slug'] ?? $brandName);
        $isActive = $request->boolean('is_active', true);
        $isFeatured = $request->boolean('is_featured', false);
        $isPopularMain = $request->boolean('is_popular', false);
        $showInList = $request->boolean('show_in_list', true);
        $requireUpgradeEmail = $request->boolean('require_upgrade_email', false);

        // Xử lý bảng thông số kỹ thuật
        $specs = [];
        if ($request->has('specs_names') && $request->has('specs_values')) {
            $names = $request->input('specs_names');
            $values = $request->input('specs_values');
            foreach ($names as $index => $name) {
                $val = $values[$index] ?? '';
                if (trim($name) !== '' || trim($val) !== '') {
                    $specs[] = ['name' => trim($name), 'value' => trim($val)];
                }
            }
        }

        // Tải ảnh đại diện
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/products', 'public_uploads');
        }

        $plansInput = $request->input('plans', []);

        if (!empty($plansInput)) {
            foreach ($plansInput as $p) {
                $planLabel = trim($p['label'] ?? '1 Tháng');
                $planKey = !empty($p['key']) ? trim($p['key']) : Product::generatePlanKey($planLabel);
                $durationDays = isset($p['duration_days']) && $p['duration_days'] > 0 ? (int)$p['duration_days'] : Product::guessDurationDays($planKey);

                Product::create([
                    'name' => $brandName . ' ' . $planLabel,
                    'brand' => $brandName,
                    'slug' => $slug,
                    'category_id' => $data['category_id'] ?? null,
                    'description' => $data['description'] ?? null,
                    'type' => $data['type'] ?? 'account',
                    'price' => $p['price'],
                    'original_price' => $p['original_price'] ?? null,
                    'plan' => $planKey,
                    'duration' => $planLabel,
                    'duration_days' => $durationDays,
                    'stock' => isset($p['stock']) ? (int)$p['stock'] : -1,
                    'plan_note' => $p['plan_note'] ?? null,
                    'is_active' => $isActive,
                    'is_featured' => $isFeatured,
                    'is_popular' => !empty($p['is_popular']) || $isPopularMain,
                    'show_in_list' => $showInList,
                    'require_upgrade_email' => $requireUpgradeEmail,
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                    'specs' => $specs,
                    'servers' => $data['servers'] ?? null,
                    'countries' => $data['countries'] ?? null,
                    'devices' => $data['devices'] ?? null,
                    'speed' => $data['speed'] ?? null,
                    'protocol' => $data['protocol'] ?? null,
                    'headquarter' => $data['headquarter'] ?? null,
                    'founded' => $data['founded'] ?? null,
                    'refund' => $data['refund'] ?? null,
                    'image' => $imagePath,
                ]);
            }
        } else {
            // Fallback tạo 1 gói đơn
            Product::create([
                'name' => $data['name'],
                'brand' => $brandName,
                'slug' => $slug,
                'category_id' => $data['category_id'] ?? null,
                'description' => $data['description'] ?? null,
                'type' => $data['type'] ?? 'account',
                'price' => $request->input('price', 0),
                'original_price' => $request->input('original_price'),
                'duration' => $request->input('duration', '1 Tháng'),
                'duration_days' => $request->input('duration_days', 30),
                'stock' => $request->input('stock', -1),
                'is_active' => $isActive,
                'show_in_list' => $showInList,
                'require_upgrade_email' => $requireUpgradeEmail,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'specs' => $specs,
                'image' => $imagePath,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Đã lưu thương hiệu và các gói sản phẩm thành công!');
    }

    /**
     * Form chỉnh sửa thương hiệu & tất cả gói thời hạn
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $brandName = $product->brand ?: $product->name;

        $brandProducts = Product::where(function($q) use ($product, $brandName) {
            if (!empty($product->brand)) {
                $q->where('brand', $product->brand);
            } else if (!empty($product->slug)) {
                $q->where('slug', $product->slug);
            } else {
                $q->where('id', $product->id);
            }
        })
        ->orderBy('price', 'asc')
        ->get();

        if ($brandProducts->isEmpty()) {
            $brandProducts = collect([$product]);
        }

        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'brandProducts', 'categories'));
    }

    /**
     * Cập nhật thông tin thương hiệu và đồng bộ tất cả gói thời hạn
     */
    public function update(Request $request, $id)
    {
        $mainProduct = Product::findOrFail($id);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'brand'            => 'nullable|string|max:255',
            'slug'             => 'nullable|string|max:255',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'type'             => 'required|in:account,license,subscription',
            'servers'          => 'nullable|string|max:255',
            'countries'        => 'nullable|string|max:255',
            'devices'          => 'nullable|string|max:255',
            'speed'            => 'nullable|string|max:255',
            'protocol'         => 'nullable|string|max:255',
            'headquarter'      => 'nullable|string|max:255',
            'founded'          => 'nullable|string|max:255',
            'refund'           => 'nullable|string|max:255',
            'require_upgrade_email' => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'specs_names'      => 'nullable|array',
            'specs_values'     => 'nullable|array',
            'plans'            => 'nullable|array',
            'plans.*.id'       => 'nullable|integer',
            'plans.*.label'    => 'required|string',
            'plans.*.key'      => 'nullable|string',
            'plans.*.price'    => 'required|numeric|min:0',
            'plans.*.original_price' => 'nullable|numeric|min:0',
            'plans.*.duration_days'  => 'nullable|integer',
            'plans.*.stock'          => 'nullable|integer',
            'plans.*.plan_note'      => 'nullable|string',
            'plans.*.is_popular'     => 'nullable|boolean',
        ]);

        $brandName = trim($data['brand'] ?? $data['name']);
        $slug = Str::slug($data['slug'] ?? $brandName);
        $isActive = $request->boolean('is_active');
        $isFeatured = $request->boolean('is_featured');
        $isPopularMain = $request->boolean('is_popular');
        $showInList = $request->boolean('show_in_list');
        $requireUpgradeEmail = $request->boolean('require_upgrade_email');

        // Xử lý thông số kỹ thuật
        $specs = [];
        if ($request->has('specs_names') && $request->has('specs_values')) {
            $names = $request->input('specs_names');
            $values = $request->input('specs_values');
            foreach ($names as $index => $name) {
                $val = $values[$index] ?? '';
                if (trim($name) !== '' || trim($val) !== '') {
                    $specs[] = ['name' => trim($name), 'value' => trim($val)];
                }
            }
        }

        // Tải ảnh đại diện mới nếu có
        $imagePath = $mainProduct->image;
        if ($request->hasFile('image')) {
            if ($mainProduct->image && \Illuminate\Support\Facades\Storage::disk('public_uploads')->exists($mainProduct->image)) {
                \Illuminate\Support\Facades\Storage::disk('public_uploads')->delete($mainProduct->image);
            }
            $imagePath = $request->file('image')->store('uploads/products', 'public_uploads');
        }

        // Lấy danh sách tất cả các gói hiện tại của thương hiệu này
        $existingBrandProducts = Product::where('brand', $mainProduct->brand)
            ->orWhere('slug', $mainProduct->slug)
            ->get();

        $plansInput = $request->input('plans', []);
        $processedIds = [];

        if (!empty($plansInput)) {
            foreach ($plansInput as $p) {
                $planLabel = trim($p['label'] ?? '1 Tháng');
                $planKey = !empty($p['key']) ? trim($p['key']) : Product::generatePlanKey($planLabel);
                $durationDays = isset($p['duration_days']) && $p['duration_days'] > 0 ? (int)$p['duration_days'] : Product::guessDurationDays($planKey);
                $planId = !empty($p['id']) ? (int)$p['id'] : null;

                $isPlanPopular = !empty($p['is_popular']) || $isPopularMain;

                $planPayload = [
                    'name' => $brandName . ' ' . $planLabel,
                    'brand' => $brandName,
                    'slug' => $slug,
                    'category_id' => $data['category_id'] ?? null,
                    'description' => $data['description'] ?? null,
                    'type' => $data['type'] ?? 'account',
                    'price' => $p['price'],
                    'original_price' => $p['original_price'] ?? null,
                    'plan' => $planKey,
                    'duration' => $planLabel,
                    'duration_days' => $durationDays,
                    'stock' => isset($p['stock']) ? (int)$p['stock'] : -1,
                    'plan_note' => $p['plan_note'] ?? null,
                    'is_active' => $isActive,
                    'is_featured' => $isFeatured,
                    'is_popular' => !empty($p['is_popular']),
                    'show_in_list' => $showInList,
                    'require_upgrade_email' => $requireUpgradeEmail,
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                    'specs' => $specs,
                    'servers' => $data['servers'] ?? null,
                    'countries' => $data['countries'] ?? null,
                    'devices' => $data['devices'] ?? null,
                    'speed' => $data['speed'] ?? null,
                    'protocol' => $data['protocol'] ?? null,
                    'headquarter' => $data['headquarter'] ?? null,
                    'founded' => $data['founded'] ?? null,
                    'refund' => $data['refund'] ?? null,
                    'image' => $imagePath,
                ];

                if ($planId && $existingProd = $existingBrandProducts->firstWhere('id', $planId)) {
                    $existingProd->update($planPayload);
                    $processedIds[] = $existingProd->id;
                } else {
                    $newProd = Product::create($planPayload);
                    $processedIds[] = $newProd->id;
                }
            }

            // Xóa các gói đã bị người dùng bấm xóa trên Form
            foreach ($existingBrandProducts as $oldProd) {
                if (!in_array($oldProd->id, $processedIds)) {
                    if ($oldProd->image && $oldProd->image !== $imagePath && \Illuminate\Support\Facades\Storage::disk('public_uploads')->exists($oldProd->image)) {
                        \Illuminate\Support\Facades\Storage::disk('public_uploads')->delete($oldProd->image);
                    }
                    $oldProd->delete();
                }
            }
        } else {
            // Cập nhật thông tin chung cho gói duy nhất
            $mainProduct->update([
                'name' => $data['name'],
                'brand' => $brandName,
                'slug' => $slug,
                'category_id' => $data['category_id'] ?? null,
                'description' => $data['description'] ?? null,
                'type' => $data['type'] ?? 'account',
                'price' => $request->input('price', $mainProduct->price),
                'original_price' => $request->input('original_price', $mainProduct->original_price),
                'duration_days' => $request->input('duration_days', $mainProduct->duration_days),
                'stock' => $request->input('stock', $mainProduct->stock),
                'is_active' => $isActive,
                'show_in_list' => $showInList,
                'require_upgrade_email' => $requireUpgradeEmail,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'specs' => $specs,
                'image' => $imagePath,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa sản phẩm / Xóa tất cả các gói thuộc thương hiệu
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->has('delete_all')) {
            $brandProducts = Product::where('brand', $product->brand)
                ->orWhere('slug', $product->slug)
                ->get();

            foreach ($brandProducts as $p) {
                if ($p->image && \Illuminate\Support\Facades\Storage::disk('public_uploads')->exists($p->image)) {
                    \Illuminate\Support\Facades\Storage::disk('public_uploads')->delete($p->image);
                }
                $p->delete();
            }
            return back()->with('success', 'Đã xóa tất cả các gói thuộc thương hiệu này!');
        }

        if ($product->image && \Illuminate\Support\Facades\Storage::disk('public_uploads')->exists($product->image)) {
            \Illuminate\Support\Facades\Storage::disk('public_uploads')->delete($product->image);
        }
        $product->delete();
        return back()->with('success', 'Sản phẩm đã được xóa!');
    }

    /**
     * Bật/tắt trạng thái cho tất cả gói thời hạn thuộc thương hiệu
     */
    public function toggleActive($id)
    {
        $product = Product::findOrFail($id);
        $newStatus = !$product->is_active;

        Product::where('brand', $product->brand)
            ->orWhere('slug', $product->slug)
            ->update(['is_active' => $newStatus]);

        return back()->with('success', 'Trạng thái đã được cập nhật cho toàn bộ thương hiệu!');
    }

    /**
     * Nhân bản sản phẩm
     */
    public function clone($id)
    {
        $product = Product::findOrFail($id);

        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' - Copy';
        $newProduct->slug = Str::slug($newProduct->name) . '-' . Str::random(6);
        $newProduct->rating = 0.0;
        $newProduct->reviews = 0;
        $newProduct->sold = 0;

        if ($product->image && \Illuminate\Support\Facades\Storage::disk('public_uploads')->exists($product->image)) {
            $pathInfo = pathinfo($product->image);
            $newImageName = Str::random(40) . '.' . ($pathInfo['extension'] ?? 'jpg');
            $newImagePath = 'uploads/products/' . $newImageName;
            \Illuminate\Support\Facades\Storage::disk('public_uploads')->copy($product->image, $newImagePath);
            $newProduct->image = $newImagePath;
        }

        $newProduct->save();

        return redirect()->route('admin.products.index')->with('success', 'Nhân bản sản phẩm thành công!');
    }
}
