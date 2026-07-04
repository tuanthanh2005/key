<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'           => 'required|string|max:50|unique:coupons,code',
            'discount_type'  => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order'      => 'nullable|integer|min:0',
            'max_uses'       => 'nullable|integer|min:1',
            'expires_at'     => 'nullable|date|after:today',
            'description'    => 'nullable|string|max:255',
            'active'         => 'nullable|boolean',
        ]);

        Coupon::create([
            'code'           => strtoupper($request->code),
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order'      => $request->min_order ?? 0,
            'max_uses'       => $request->max_uses,
            'expires_at'     => $request->expires_at,
            'description'    => $request->description,
            'active'         => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Tạo mã coupon thành công!');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'           => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type'  => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order'      => 'nullable|integer|min:0',
            'max_uses'       => 'nullable|integer|min:1',
            'expires_at'     => 'nullable|date',
            'description'    => 'nullable|string|max:255',
            'active'         => 'nullable|boolean',
        ]);

        $coupon->update([
            'code'           => strtoupper($request->code),
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order'      => $request->min_order ?? 0,
            'max_uses'       => $request->max_uses,
            'expires_at'     => $request->expires_at ?: null,
            'description'    => $request->description,
            'active'         => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cập nhật coupon thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Xóa coupon thành công!');
    }

    /**
     * API: Validate coupon từ frontend JS
     */
    public function validate(Request $request)
    {
        $code    = strtoupper($request->code ?? '');
        $subtotal = floatval($request->subtotal ?? 0);

        $coupon = Coupon::valid()->where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
        }

        if ($subtotal < $coupon->min_order) {
            return response()->json([
                'valid'   => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($coupon->min_order) . 'đ để dùng mã này.',
            ]);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return response()->json([
            'valid'          => true,
            'discount'       => $discount,
            'discount_type'  => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'message'        => 'Áp dụng mã ' . $coupon->code . ' thành công! Giảm ' . number_format($discount) . 'đ',
        ]);
    }
}
