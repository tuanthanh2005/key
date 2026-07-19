<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryTypeController extends Controller
{
    public function index()
    {
        $types = CategoryType::all();
        return view('admin.category-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:category_types,slug',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (CategoryType::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        CategoryType::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.category-types.index')->with('success', 'Thêm loại danh mục mới thành công!');
    }

    public function update(Request $request, $id)
    {
        $type = CategoryType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:category_types,slug,' . $id,
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (CategoryType::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $type->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.category-types.index')->with('success', 'Cập nhật loại danh mục thành công!');
    }

    public function destroy($id)
    {
        $type = CategoryType::findOrFail($id);
        
        // Prevent deleting types if there are categories associated with them
        $associated = \App\Models\Category::where('type', $type->slug)->exists();
        if ($associated) {
            return redirect()->route('admin.category-types.index')->with('error', 'Không thể xóa loại danh mục này vì có danh mục đang sử dụng!');
        }

        $type->delete();
        return redirect()->route('admin.category-types.index')->with('success', 'Xóa loại danh mục thành công!');
    }
}
