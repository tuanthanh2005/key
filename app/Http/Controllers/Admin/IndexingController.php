<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Product;
use App\Services\GoogleIndexingService;
use Illuminate\Http\Request;

class IndexingController extends Controller
{
    protected $indexingService;

    public function __construct(GoogleIndexingService $indexingService)
    {
        $this->indexingService = $indexingService;
    }

    /**
     * Hiển thị trang cấu hình và danh sách URL lập chỉ mục
     */
    public function index()
    {
        $isConfigured = $this->indexingService->isConfigured();

        // Lấy thông tin tài khoản từ settings (nếu cấu hình rồi)
        $clientEmail = '';
        if ($isConfigured) {
            $jsonStr = Setting::get('google_service_account_json');
            $creds = json_decode($jsonStr, true);
            $clientEmail = $creds['client_email'] ?? '';
        }

        // Đồng bộ và lấy hạn ngạch hôm nay
        $today = date('Y-m-d');
        $quotaDate = Setting::get('google_indexing_quota_date', $today);
        $quotaUsed = (int) Setting::get('google_indexing_quota_used', 0);

        if ($quotaDate !== $today) {
            // Sang ngày mới -> reset quota về 0
            Setting::set('google_indexing_quota_date', $today);
            Setting::set('google_indexing_quota_used', 0);
            $quotaUsed = 0;
            Setting::clearCache();
        }

        // 1. Thu thập Trang tĩnh
        $staticUrls = [
            'Trang chủ' => route('home'),
            'Sản phẩm' => route('products'),
            'Bảng giá' => route('pricing'),
            'Giới thiệu' => route('about'),
            'Liên hệ' => route('contact'),
        ];

        // 2. Thu thập Thương hiệu / Danh mục
        $brands = Product::where('status', 'active')
            ->whereNotNull('slug')
            ->distinct()
            ->pluck('slug')
            ->toArray();

        $brandUrls = [];
        foreach ($brands as $slug) {
            $brandUrls[$slug] = route('product.detail', $slug);
        }

        return view('admin.indexing.index', compact(
            'isConfigured',
            'clientEmail',
            'quotaUsed',
            'staticUrls',
            'brandUrls'
        ));
    }

    /**
     * Xử lý yêu cầu gửi lập chỉ mục lên Google API
     */
    public function submit(Request $request)
    {
        if (!$this->indexingService->isConfigured()) {
            return back()->with('error', 'Chưa cấu hình tài khoản Google Service Account. Vui lòng kiểm tra lại file cấu hình.');
        }

        $request->validate([
            'urls' => 'nullable|array',
            'custom_urls' => 'nullable|array',
            'type' => 'required|in:URL_UPDATED,URL_DELETED',
        ]);

        // Gom tất cả các URL được chọn và nhập tay
        $checkedUrls = $request->urls ?? [];
        $customUrls = array_filter(array_map('trim', $request->custom_urls ?? []));
        $allUrls = array_unique(array_merge($checkedUrls, $customUrls));

        if (empty($allUrls)) {
            return back()->with('error', 'Vui lòng chọn ít nhất một URL hoặc nhập URL riêng lẻ để lập chỉ mục.');
        }

        // Lấy thông tin hạn ngạch hiện tại
        $today = date('Y-m-d');
        $quotaDate = Setting::get('google_indexing_quota_date', $today);
        $quotaUsed = (int) Setting::get('google_indexing_quota_used', 0);

        if ($quotaDate !== $today) {
            $quotaUsed = 0;
            Setting::set('google_indexing_quota_date', $today);
        }

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($allUrls as $url) {
            // Kiểm tra giới hạn 200 lượt
            if ($quotaUsed >= 200) {
                $errors[] = "Đạt giới hạn quota 200 lượt/ngày. Các URL còn lại không được gửi.";
                break;
            }

            // Gửi API
            $res = $this->indexingService->indexUrl($url, $request->type);

            if ($res['success']) {
                $successCount++;
                $quotaUsed++;
            } else {
                $failedCount++;
                $errors[] = "URL: $url - Lỗi: " . $res['message'];
            }
        }

        // Lưu quota mới
        Setting::set('google_indexing_quota_used', $quotaUsed);
        Setting::clearCache();

        // Tạo thông báo kết quả
        $msg = "Đã gửi yêu cầu lập chỉ mục: thành công $successCount URL" . ($failedCount > 0 ? ", thất bại $failedCount URL" : "") . ".";

        if ($failedCount > 0 || !empty($errors)) {
            return back()->with('warning', $msg)->with('errors_list', $errors);
        }

        return back()->with('success', $msg);
    }
}
