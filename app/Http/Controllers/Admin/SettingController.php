<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Hiển thị trang cài đặt
     */
    public function index()
    {
        $settings = Setting::getAllKeyed();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Lưu cài đặt hệ thống (bulk update)
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'favicon', 'store_logo']);

        // Validate một số field quan trọng
        $request->validate([
            'auto_discount_threshold' => 'nullable|integer|min:0',
            'auto_discount_rate'      => 'nullable|numeric|min:0|max:100',
            'dashboard_max_days'      => 'nullable|integer|min:1|max:365',
            'dashboard_orders_per_page' => 'nullable|integer|min:5|max:100',
            'contact_email'           => 'nullable|email',
            'favicon'                 => 'nullable|image|mimes:ico,png,jpg,jpeg,gif,svg,webp|max:2048',
            'store_logo'              => 'nullable|image|mimes:png,jpg,jpeg,gif,svg,webp|max:2048',
        ], [
            'auto_discount_rate.max'     => 'Tỷ lệ giảm giá tối đa 100%.',
            'dashboard_max_days.max'     => 'Không được vượt quá 365 ngày.',
            'contact_email.email'        => 'Email không hợp lệ.',
            'favicon.image'              => 'Favicon phải là một hình ảnh.',
            'favicon.mimes'              => 'Favicon phải thuộc định dạng: ico, png, jpg, jpeg, gif, svg, webp.',
            'favicon.max'                => 'Dung lượng favicon tối đa 2MB.',
            'store_logo.image'           => 'Logo phải là một hình ảnh.',
            'store_logo.mimes'           => 'Logo phải thuộc định dạng: png, jpg, jpeg, gif, svg, webp.',
            'store_logo.max'             => 'Dung lượng logo tối đa 2MB.',
        ]);

        // Xử lý upload favicon
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            
            if (!file_exists(public_path('uploads/settings'))) {
                mkdir(public_path('uploads/settings'), 0777, true);
            }

            // Xóa favicon cũ nếu có
            $oldFavicon = Setting::get('favicon_path');
            if ($oldFavicon && file_exists(public_path($oldFavicon))) {
                @unlink(public_path($oldFavicon));
            }

            $file->move(public_path('uploads/settings'), $filename);
            Setting::set('favicon_path', 'uploads/settings/' . $filename);
        }

        // Xử lý upload logo
        if ($request->hasFile('store_logo')) {
            $file = $request->file('store_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            
            if (!file_exists(public_path('uploads/settings'))) {
                mkdir(public_path('uploads/settings'), 0777, true);
            }

            // Xóa logo cũ nếu có
            $oldLogo = Setting::get('logo_path');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }

            $file->move(public_path('uploads/settings'), $filename);
            Setting::set('logo_path', 'uploads/settings/' . $filename);
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        // Xóa cache sau khi cập nhật xong
        Setting::clearCache();

        return redirect()->back()->with('success', 'Đã lưu cấu hình hệ thống thành công!');
    }

    /**
     * API public: Trả về settings cần cho frontend JS (discount, bank info)
     */
    public function publicApi()
    {
        $s = Setting::getAllKeyed();
        return response()->json([
            'auto_discount_threshold' => (int) ($s['auto_discount_threshold'] ?? 500000),
            'auto_discount_rate'      => (float) ($s['auto_discount_rate'] ?? 5),
            'bank_code'               => $s['bank_code'] ?? 'OCB',
            'bank_account_number'     => $s['bank_account_number'] ?? '',
            'bank_account_name'       => $s['bank_account_name'] ?? '',
            'bank_bin'                => $s['bank_bin'] ?? '',
        ]);
    }
}
