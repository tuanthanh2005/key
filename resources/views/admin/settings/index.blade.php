@extends('admin.layouts.admin')

@section('title', 'Cài Đặt Hệ Thống')
@section('page_title', 'Cài Đặt Hệ Thống')
@section('breadcrumb', 'Admin / Cài Đặt')

@section('content')
<style>
.settings-tabs .nav-link {
    color: var(--admin-muted);
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 13.5px;
    font-weight: 500;
    border: none;
    margin-bottom: 4px;
}
.settings-tabs .nav-link.active {
    background: var(--admin-primary);
    color: #fff;
}
.settings-tabs .nav-link:hover:not(.active) {
    background: var(--admin-hover);
    color: var(--admin-text);
}
.setting-section { display: none; }
.setting-section.active { display: block; }
.setting-label { font-size: 13px; font-weight: 600; color: var(--admin-text); margin-bottom: 4px; }
.setting-hint { font-size: 11.5px; color: var(--admin-muted); margin-top: 3px; }
.setting-row { padding: 14px 0; border-bottom: 1px solid var(--admin-border); }
.setting-row:last-child { border-bottom: none; }
</style>

<div class="row g-4">
    <!-- Sidebar Tabs -->
    <div class="col-lg-3">
        <div class="admin-card p-3">
            <nav class="settings-tabs nav flex-column" id="settingsTabs">
                <a class="nav-link active" data-tab="store" href="#">
                    <i class="bi bi-shop me-2"></i>Thông Tin Cửa Hàng
                </a>
                <a class="nav-link" data-tab="contact" href="#">
                    <i class="bi bi-telephone me-2"></i>Liên Hệ & Hỗ Trợ
                </a>
                <a class="nav-link" data-tab="payment" href="#">
                    <i class="bi bi-credit-card me-2"></i>Thanh Toán
                </a>
                <a class="nav-link" data-tab="discount" href="#">
                    <i class="bi bi-percent me-2"></i>Khuyến Mãi & Giảm Giá
                </a>
                <a class="nav-link" data-tab="ui" href="#">
                    <i class="bi bi-palette me-2"></i>Giao Diện
                </a>
                <a class="nav-link" data-tab="product" href="#">
                    <i class="bi bi-box-seam me-2"></i>Sản Phẩm
                </a>
                <a class="nav-link" data-tab="dashboard" href="#">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                <a class="nav-link" data-tab="seo" href="#">
                    <i class="bi bi-search me-2"></i>SEO & Meta
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px; font-size:13.5px">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius:12px; font-size:13.5px">
            <i class="bi bi-exclamation-circle me-1"></i>
            @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
        </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm" enctype="multipart/form-data">
            @csrf
            @method('POST')

            {{-- ===================== THÔNG TIN CỬA HÀNG ===================== --}}
            <div class="admin-card setting-section active" id="tab-store">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-shop text-primary"></i> Thông Tin Cửa Hàng</div>
                </div>
                <div class="admin-card-body">
                    <div class="setting-row">
                        <div class="setting-label">Tên Cửa Hàng</div>
                        <input type="text" name="store_name" class="form-control mt-1" value="{{ $settings['store_name'] ?? 'VPNStore' }}" placeholder="VPNStore">
                        <div class="setting-hint">Hiển thị ở logo, tiêu đề trang, footer.</div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Chế Độ Bảo Trì</div>
                        <select name="maintenance_mode" class="form-select mt-1" style="max-width:200px">
                            <option value="0" {{ ($settings['maintenance_mode'] ?? '0') == '0' ? 'selected' : '' }}>Hoạt động bình thường</option>
                            <option value="1" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'selected' : '' }}>Đang bảo trì</option>
                        </select>
                        <div class="setting-hint">Khi bật, hiển thị trang bảo trì cho khách.</div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Hiển Thị Review Mẫu</div>
                        <select name="show_fake_reviews" class="form-select mt-1" style="max-width:200px">
                            <option value="1" {{ ($settings['show_fake_reviews'] ?? '1') == '1' ? 'selected' : '' }}>Hiện review mẫu khi chưa có review thật</option>
                            <option value="0" {{ ($settings['show_fake_reviews'] ?? '1') == '0' ? 'selected' : '' }}>Không hiện</option>
                        </select>
                        <div class="setting-hint">Nếu sản phẩm chưa có review thật, có hiện review mẫu không?</div>
                    </div>
                </div>
            </div>

            {{-- ===================== LIÊN HỆ & HỖ TRỢ ===================== --}}
            <div class="admin-card setting-section" id="tab-contact">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-telephone text-primary"></i> Liên Hệ & Hỗ Trợ</div>
                </div>
                <div class="admin-card-body">
                    <div class="setting-row">
                        <div class="setting-label">Email Liên Hệ</div>
                        <input type="email" name="contact_email" class="form-control mt-1" value="{{ $settings['contact_email'] ?? '' }}" placeholder="email@example.com">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Telegram Username</div>
                        <div class="input-group mt-1" style="max-width:320px">
                            <span class="input-group-text">@</span>
                            <input type="text" name="telegram_support" class="form-control" value="{{ ltrim($settings['telegram_support'] ?? 'specademy', '@') }}" placeholder="specademy">
                        </div>
                        <div class="setting-hint">Không cần nhập dấu @</div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">URL Telegram (để chat trực tiếp)</div>
                        <input type="text" name="telegram_url" class="form-control mt-1" value="{{ $settings['telegram_url'] ?? 'https://t.me/specademy' }}" placeholder="https://t.me/username">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Số Zalo 1</div>
                        <input type="text" name="zalo_support" class="form-control mt-1" value="{{ $settings['zalo_support'] ?? '' }}" placeholder="0708910952" style="max-width:260px">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">URL Zalo 1</div>
                        <input type="text" name="zalo_url_1" class="form-control mt-1" value="{{ $settings['zalo_url_1'] ?? '' }}" placeholder="https://zalo.me/0708910952">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Số Zalo 2 (tùy chọn)</div>
                        <input type="text" name="zalo_support_2" class="form-control mt-1" value="{{ $settings['zalo_support_2'] ?? '' }}" placeholder="0569012134" style="max-width:260px">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">URL Zalo 2</div>
                        <input type="text" name="zalo_url_2" class="form-control mt-1" value="{{ $settings['zalo_url_2'] ?? '' }}" placeholder="https://zalo.me/0569012134">
                    </div>
                </div>
            </div>

            {{-- ===================== THANH TOÁN ===================== --}}
            <div class="admin-card setting-section" id="tab-payment">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-credit-card text-primary"></i> Thông Tin Thanh Toán</div>
                </div>
                <div class="admin-card-body">
                    <div class="alert alert-info" style="border-radius:10px; font-size:13px">
                        <i class="bi bi-info-circle me-1"></i>
                        Thông tin tài khoản ngân hàng dùng để tạo QR VietQR trên trang thanh toán.
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Mã Ngân Hàng (Bank Code)</div>
                        <input type="text" name="bank_code" class="form-control mt-1" value="{{ $settings['bank_code'] ?? 'OCB' }}" placeholder="OCB" style="max-width:200px">
                        <div class="setting-hint">Ví dụ: OCB, MB, VCB, TCB, ACB... (tên viết tắt ngân hàng)</div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Mã BIN (để tạo QR VietQR)</div>
                        <input type="text" name="bank_bin" class="form-control mt-1" value="{{ $settings['bank_bin'] ?? '' }}" placeholder="970212" style="max-width:200px">
                        <div class="setting-hint">Tra cứu BIN ngân hàng tại <a href="https://vietqr.io/danh-sach-api/bank-code" target="_blank">vietqr.io</a>. OCB = 970212</div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Số Tài Khoản</div>
                        <input type="text" name="bank_account_number" class="form-control mt-1" value="{{ $settings['bank_account_number'] ?? '' }}" placeholder="0772698113" style="max-width:280px">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Tên Chủ Tài Khoản</div>
                        <input type="text" name="bank_account_name" class="form-control mt-1" value="{{ $settings['bank_account_name'] ?? '' }}" placeholder="NGUYEN VAN A" style="max-width:320px">
                        <div class="setting-hint">Viết in hoa, không dấu, đúng theo ngân hàng.</div>
                    </div>

                    @if(!empty($settings['bank_bin']) && !empty($settings['bank_account_number']))
                    <div class="setting-row">
                        <div class="setting-label">Preview QR VietQR</div>
                        <div class="mt-2">
                            <img src="https://img.vietqr.io/image/{{ $settings['bank_code'] }}-{{ $settings['bank_account_number'] }}-compact2.png?accountName={{ urlencode($settings['bank_account_name'] ?? '') }}"
                                 style="border-radius:12px; max-width:220px; border:2px solid var(--admin-border)"
                                 alt="QR Preview">
                            <div class="setting-hint mt-1">Preview QR hiện tại. Sau khi lưu cài đặt, trang checkout sẽ dùng QR này.</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ===================== KHUYẾN MÃI ===================== --}}
            <div class="admin-card setting-section" id="tab-discount">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-percent text-primary"></i> Cài Đặt Khuyến Mãi</div>
                </div>
                <div class="admin-card-body">
                    <div class="setting-row">
                        <div class="setting-label">Ngưỡng Tự Động Giảm Giá (Auto Discount Threshold)</div>
                        <div class="input-group mt-1" style="max-width:280px">
                            <input type="number" name="auto_discount_threshold" class="form-control" value="{{ $settings['auto_discount_threshold'] ?? 500000 }}" min="0" step="10000">
                            <span class="input-group-text">đ</span>
                        </div>
                        <div class="setting-hint">Đơn hàng vượt ngưỡng này sẽ tự động được giảm giá thêm.</div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Tỷ Lệ Giảm Tự Động (%)</div>
                        <div class="input-group mt-1" style="max-width:180px">
                            <input type="number" name="auto_discount_rate" class="form-control" value="{{ $settings['auto_discount_rate'] ?? 5 }}" min="0" max="100" step="0.5">
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="setting-hint">Ví dụ: 5 = giảm 5% cho đơn vượt ngưỡng.</div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">
                            <i class="bi bi-tags me-1"></i>Quản Lý Mã Coupon
                        </div>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-primary btn-sm mt-1 rounded-pill px-3">
                            <i class="bi bi-arrow-right-circle me-1"></i>Đến Trang Quản Lý Coupon
                        </a>
                        <div class="setting-hint">Thêm, sửa, xóa mã giảm giá VPNVN10, VIP20, SALE15... tại trang coupon.</div>
                    </div>
                </div>
            </div>

            {{-- ===================== GIAO DIỆN ===================== --}}
            <div class="admin-card setting-section" id="tab-ui">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-palette text-primary"></i> Văn Bản Giao Diện</div>
                </div>
                <div class="admin-card-body">
                    <div class="setting-row">
                        <div class="setting-label">Topbar Text (dòng thông báo đầu trang)</div>
                        <input type="text" name="topbar_text" class="form-control mt-1" value="{{ $settings['topbar_text'] ?? 'Bảo hành 30 ngày · Hỗ trợ 24/7 · Thanh toán an toàn' }}">
                        <div class="setting-hint">Văn bản hiển thị ở thanh màu trên cùng.</div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Footer Copyright</div>
                        <input type="text" name="footer_copyright" class="form-control mt-1" value="{{ $settings['footer_copyright'] ?? 'VPNStore. Tất cả quyền được bảo lưu.' }}">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Favicon (Biểu tượng trang web)</div>
                        <div class="d-flex align-items-center gap-3 mt-2">
                            <div class="favicon-preview border p-2 rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--admin-hover);">
                                <img src="{{ !empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico') }}" alt="Favicon" style="max-width: 100%; max-height: 100%; object-fit: contain;" id="favicon-preview-img">
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="favicon" class="form-control" accept="image/x-icon,image/png,image/jpeg,image/gif,image/svg+xml,image/webp" id="favicon-input">
                                <div class="setting-hint">Hỗ trợ các định dạng .ico, .png, .jpg, .jpeg, .gif, .svg, .webp. Dung lượng tối đa 2MB.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== SẢN PHẨM ===================== --}}
            <div class="admin-card setting-section" id="tab-product">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-box-seam text-primary"></i> Cài Đặt Sản Phẩm</div>
                </div>
                <div class="admin-card-body">
                    <div class="setting-row">
                        <div class="setting-label">Features Mặc Định (khi tạo sản phẩm mới)</div>
                        <input type="text" name="default_product_features" class="form-control mt-1" value="{{ $settings['default_product_features'] ?? 'Mã bản quyền chính hãng,Bảo mật tuyệt đối,Hỗ trợ 24/7' }}">
                        <div class="setting-hint">Ngăn cách bằng dấu phẩy. Ví dụ: <code>Feature A,Feature B,Feature C</code></div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Rating Mặc Định Sản Phẩm Mới</div>
                        <div class="input-group mt-1" style="max-width:160px">
                            <input type="number" name="default_product_rating" class="form-control" value="{{ $settings['default_product_rating'] ?? 4.8 }}" min="0" max="5" step="0.1">
                            <span class="input-group-text">/ 5</span>
                        </div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Số Review Mặc Định Sản Phẩm Mới</div>
                        <input type="number" name="default_product_reviews" class="form-control mt-1" value="{{ $settings['default_product_reviews'] ?? 120 }}" min="0" style="max-width:160px">
                        <div class="setting-hint">Con số hiển thị như "120 đánh giá" trên sản phẩm mới.</div>
                    </div>
                </div>
            </div>

            {{-- ===================== DASHBOARD ===================== --}}
            <div class="admin-card setting-section" id="tab-dashboard">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-speedometer2 text-primary"></i> Cài Đặt Dashboard</div>
                </div>
                <div class="admin-card-body">
                    <div class="setting-row">
                        <div class="setting-label">Số Ngày Tối Đa Lọc Doanh Thu</div>
                        <div class="input-group mt-1" style="max-width:200px">
                            <input type="number" name="dashboard_max_days" class="form-control" value="{{ $settings['dashboard_max_days'] ?? 60 }}" min="7" max="365">
                            <span class="input-group-text">ngày</span>
                        </div>
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Số Đơn Hàng Mỗi Trang (Dashboard)</div>
                        <input type="number" name="dashboard_orders_per_page" class="form-control mt-1" value="{{ $settings['dashboard_orders_per_page'] ?? 10 }}" min="5" max="100" style="max-width:160px">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Số Khách Hàng Mới Hiển Thị</div>
                        <input type="number" name="dashboard_recent_users_count" class="form-control mt-1" value="{{ $settings['dashboard_recent_users_count'] ?? 5 }}" min="1" max="20" style="max-width:160px">
                    </div>
                    <div class="setting-row">
                        <div class="setting-label">Số Top Sản Phẩm Bán Chạy</div>
                        <input type="number" name="dashboard_top_products_count" class="form-control mt-1" value="{{ $settings['dashboard_top_products_count'] ?? 5 }}" min="3" max="20" style="max-width:160px">
                    </div>
                </div>
            </div>

            {{-- ===================== SEO ===================== --}}
            <div class="admin-card setting-section" id="tab-seo">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-search text-primary"></i> SEO & Meta Description</div>
                </div>
                <div class="admin-card-body">
                    <div class="setting-row">
                        <div class="setting-label">Meta Description Mặc Định</div>
                        <textarea name="meta_description" class="form-control mt-1" rows="3" maxlength="300">{{ $settings['meta_description'] ?? '' }}</textarea>
                        <div class="setting-hint d-flex justify-content-between">
                            <span>Mô tả hiển thị khi chia sẻ link trên mạng xã hội và Google. Tối đa 160 ký tự.</span>
                            <span id="metaCharCount" class="text-muted">0/160</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="d-flex gap-3 mt-3">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-600">
                    <i class="bi bi-save me-2"></i>Lưu Cài Đặt
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Tab switching
document.querySelectorAll('.settings-tabs .nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.settings-tabs .nav-link').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.setting-section').forEach(s => s.classList.remove('active'));
        this.classList.add('active');
        const tab = this.dataset.tab;
        const section = document.getElementById('tab-' + tab);
        if (section) section.classList.add('active');
    });
});

// Meta char counter
const metaArea = document.querySelector('[name="meta_description"]');
const metaCount = document.getElementById('metaCharCount');
if (metaArea && metaCount) {
    const update = () => metaCount.textContent = metaArea.value.length + '/160';
    metaArea.addEventListener('input', update);
    update();
}

// Favicon live preview
const faviconInput = document.getElementById('favicon-input');
const faviconPreviewImg = document.getElementById('favicon-preview-img');
if (faviconInput && faviconPreviewImg) {
    faviconInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                faviconPreviewImg.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
}
</script>
@endsection
