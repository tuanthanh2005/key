@extends('admin.layouts.admin')

@section('title', 'Lập Chỉ Mục Google')
@section('page_title', 'Lập Chỉ Mục Google')
@section('breadcrumb', 'Admin / Lập Chỉ Mục Google')

@section('content')
<div class="container-fluid px-0">
    <!-- Toast Notifications -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:14px; background:#f0fdf4; border-left: 5px solid #16a34a!important;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill text-success" style="font-size:18px"></i>
            <div>
                <strong class="text-success">Thành công!</strong> {{ session('success') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:14px; background:#fffbeb; border-left: 5px solid #d97706!important;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size:18px"></i>
            <div>
                <strong class="text-warning">Cảnh báo!</strong> {{ session('warning') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:14px; background:#fef2f2; border-left: 5px solid #dc2626!important;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-x-circle-fill text-danger" style="font-size:18px"></i>
            <div>
                <strong class="text-danger">Lỗi!</strong> {{ session('error') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('errors_list'))
    <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert" style="border-radius:14px; background:#fef2f2;">
        <h6 class="fw-700 text-danger mb-2"><i class="bi bi-list-task me-2"></i>Chi tiết lỗi xảy ra:</h6>
        <ul class="mb-0 ps-3 text-danger-emphasis" style="font-size:13px; line-height: 1.6;">
            @foreach(session('errors_list') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row g-4">
        <!-- CẤU HÌNH & QUOTA -->
        <div class="col-12 col-xl-4">
            <!-- Quota Card -->
            <div class="admin-card mb-4 border-0 shadow-sm" 
                 style="background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 50%, #701a75 100%); color: #fff; border-radius:20px; overflow: hidden; position: relative;">
                
                <!-- Decor circles -->
                <div style="position:absolute; width:150px; height:150px; background:rgba(255,255,255,0.05); border-radius:50%; top:-50px; right:-50px;"></div>
                <div style="position:absolute; width:100px; height:100px; background:rgba(255,255,255,0.03); border-radius:50%; bottom:-20px; left:-20px;"></div>

                <div class="admin-card-body" style="padding: 28px; position: relative; z-index: 2;">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div style="background: rgba(255,255,255,0.15); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-google" style="font-size: 22px;"></i>
                        </div>
                        <span class="badge bg-white text-dark rounded-pill fw-700 px-3 py-1.5" style="font-size: 11.5px;">DAILY QUOTA</span>
                    </div>

                    <h5 class="fw-800 text-white-50 mb-1" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Hạn ngạch hôm nay</h5>
                    <div class="d-flex align-items-baseline gap-2 mb-3">
                        <span class="fw-900" style="font-size: 42px; line-height: 1;">{{ $quotaUsed }}</span>
                        <span class="text-white-50 fw-600" style="font-size: 18px;">/ 200 URL</span>
                    </div>

                    @php
                        $percentage = min(100, max(0, ($quotaUsed / 200) * 100));
                        $remaining = 200 - $quotaUsed;
                    @endphp

                    <!-- Progress bar -->
                    <div class="progress mb-3" style="height: 10px; background: rgba(255,255,255,0.15); border-radius: 5px; overflow: hidden;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                             style="width: {{ $percentage }}%; background: linear-gradient(90deg, #10b981, #3b82f6);" 
                             aria-valuenow="{{ $quotaUsed }}" aria-valuemin="0" aria-valuemax="200"></div>
                    </div>

                    <div class="d-flex justify-content-between" style="font-size: 12.5px;">
                        <span class="text-white-50">Đã dùng: <strong class="text-white">{{ number_format($percentage, 1) }}%</strong></span>
                        <span class="text-white-50">Còn lại: <strong class="text-white">{{ $remaining }} URL</strong></span>
                    </div>
                </div>
            </div>

            <!-- Credentials Status Card -->
            <div class="admin-card border-0 shadow-sm" style="border-radius:20px;">
                <div class="admin-card-header border-bottom py-3 px-4" style="background:#fafafa;">
                    <div class="admin-card-title m-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-shield-fill-check text-primary"></i>
                        Thông Tin API Credentials
                    </div>
                </div>
                <div class="admin-card-body" style="padding: 24px;">
                    <div class="d-flex gap-3 align-items-start mb-4">
                        @if($isConfigured)
                            <div class="text-success" style="font-size: 24px; line-height: 1;"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <h6 class="fw-700 mb-1" style="font-size: 13.5px;">Kết nối hoạt động</h6>
                                <p class="text-muted mb-0" style="font-size: 12px; word-break: break-all;">
                                    Đã cấu hình Google Service Account: <br>
                                    <strong class="text-dark">{{ $clientEmail }}</strong>
                                </p>
                            </div>
                        @else
                            <div class="text-danger" style="font-size: 24px; line-height: 1;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            <div>
                                <h6 class="fw-700 mb-1" style="font-size: 13.5px;">Chưa cấu hình</h6>
                                <p class="text-muted mb-0" style="font-size: 12px;">
                                    Vui lòng tải lên file key dịch vụ <code class="text-danger">google-service-account.json</code> vào thư mục <code class="text-dark fw-700">public_html</code> (hoặc thư mục <code class="text-dark fw-700">public</code>).
                                </p>
                            </div>
                        @endif
                    </div>

                    <div style="background:#f8fafc; border-radius:12px; padding: 16px; font-size:12.5px;">
                        <div class="fw-700 text-dark mb-1"><i class="bi bi-info-circle me-1 text-primary"></i>Lưu ý khi sử dụng:</div>
                        <p class="text-muted mb-0" style="line-height:1.6">
                            1. Để Google nhận diện lập chỉ mục, tài khoản email dịch vụ (Client Email) ở trên cần được thêm làm thành viên **Chủ sở hữu (Owner)** hoặc có quyền **Quản trị (Admin)** trong cấu hình tài khoản Google Search Console của tên miền này. <br>
                            2. Tốc độ Google duyệt chỉ mục có thể mất vài tiếng hoặc vài ngày tùy thuộc vào độ uy tín của website.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN FORM -->
        <div class="col-12 col-xl-8">
            <form action="{{ route('admin.indexing.submit') }}" method="POST" id="indexingForm">
                @csrf
                <div class="admin-card border-0 shadow-sm mb-4" style="border-radius:20px;">
                    <div class="admin-card-header border-bottom py-3 px-4 d-flex justify-content-between align-items-center" style="background:#fafafa;">
                        <div class="admin-card-title m-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                            <i class="bi bi-link-45deg text-primary" style="font-size: 18px;"></i>
                            Cấu Hình Danh Sách Lập Chỉ Mục
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <select name="type" class="form-select form-select-sm border-0 fw-600 shadow-sm" style="background:#f1f5f9; border-radius:8px; width: 150px; font-size:12px; padding: 6px 12px;">
                                <option value="URL_UPDATED">Thêm/Sửa URL</option>
                                <option value="URL_DELETED">Xóa URL</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="toggleSelectAll(this)" id="selectAllBtn" style="font-size:11.5px; font-weight:600">
                                <i class="bi bi-check2-all me-1"></i>Chọn Tất Cả
                            </button>
                        </div>
                    </div>
                    
                    <div class="admin-card-body" style="padding: 24px;">
                        
                        <!-- TAB 1: Trang Tĩnh -->
                        <div class="mb-4">
                            <h6 class="fw-800 text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 13.5px;">
                                <i class="bi bi-file-earmark-code text-indigo"></i>
                                Lập Chỉ Mục Trang Tĩnh
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size:10px">{{ count($staticUrls) }} trang</span>
                            </h6>
                            <div class="row g-2">
                                @foreach($staticUrls as $name => $url)
                                <div class="col-12 col-md-6 col-xxl-4">
                                    <div class="p-3 border rounded-3 d-flex align-items-start gap-2 hover-card" style="transition: all 0.2s; border-color:#e2e8f0!important;">
                                        <div class="form-check m-0">
                                            <input class="form-check-input url-checkbox" type="checkbox" name="urls[]" value="{{ $url }}" id="static_{{ $loop->index }}" style="cursor:pointer;">
                                        </div>
                                        <label for="static_{{ $loop->index }}" style="cursor:pointer; flex: 1;">
                                            <div class="fw-700 text-dark" style="font-size:13px">{{ $name }}</div>
                                            <div class="text-muted text-truncate" style="font-size:11.5px; max-width: 180px;">{{ $url }}</div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#e2e8f0;">

                        <!-- TAB 2: Danh Mục / Thương Hiệu -->
                        <div class="mb-4">
                            <h6 class="fw-800 text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 13.5px;">
                                <i class="bi bi-tags text-orange"></i>
                                Lập Chỉ Mục Thương Hiệu (Danh Mục)
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size:10px">{{ count($brandUrls) }} trang</span>
                            </h6>
                            @if(empty($brandUrls))
                                <p class="text-muted mb-0" style="font-size:12.5px">Không tìm thấy thương hiệu hoạt động nào trong cơ sở dữ liệu.</p>
                            @else
                                <div class="row g-2">
                                    @foreach($brandUrls as $brand => $url)
                                    <div class="col-12 col-md-6 col-xxl-4">
                                        <div class="p-3 border rounded-3 d-flex align-items-start gap-2 hover-card" style="transition: all 0.2s; border-color:#e2e8f0!important;">
                                            <div class="form-check m-0">
                                                <input class="form-check-input url-checkbox" type="checkbox" name="urls[]" value="{{ $url }}" id="brand_{{ $loop->index }}" style="cursor:pointer;">
                                            </div>
                                            <label for="brand_{{ $loop->index }}" style="cursor:pointer; flex: 1;">
                                                <div class="fw-700 text-dark" style="font-size:13px">{{ strtoupper($brand) }}</div>
                                                <div class="text-muted text-truncate" style="font-size:11.5px; max-width: 180px;">{{ $url }}</div>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <hr class="my-4" style="border-color:#e2e8f0;">

                        <!-- TAB 3: Lập chỉ mục riêng lẻ -->
                        <div class="mb-2">
                            <h6 class="fw-800 text-dark mb-3 d-flex align-items-center justify-content-between" style="font-size: 13.5px;">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi bi-plus-circle text-success"></i>
                                    Lập Chỉ Mục URL Riêng Lẻ
                                </span>
                                <button type="button" class="btn btn-xs btn-success rounded-pill px-3 py-1 fw-600" onclick="addUrlRow()" style="font-size:11px">
                                    <i class="bi bi-plus me-1"></i>Thêm Hàng
                                </button>
                            </h6>
                            <div id="customUrlsContainer">
                                <div class="d-flex gap-2 mb-2 align-items-center">
                                    <input type="url" name="custom_urls[]" class="form-control" placeholder="https://vpnstore.pro/nhap-url-tuy-chinh-o-day" style="border-radius:10px; font-size:13px;">
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-0" onclick="this.parentElement.remove()" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="modal-footer border-0 px-4 py-3" style="background:#fafafa; border-radius: 0 0 20px 20px; gap: 10px;">
                        <button type="button" class="btn btn-lg btn-outline-violet rounded-pill fw-700 px-4" onclick="submitAll()" style="font-size:13.5px; border-width: 2px;">
                            <i class="bi bi-send-check-fill me-2"></i>INDEX TOÀN BỘ
                        </button>
                        <button type="submit" class="btn btn-lg rounded-pill fw-800 px-5 ms-auto" id="submitCheckedBtn"
                                style="background:linear-gradient(135deg,#2563eb,#7c3aed); color:#fff; border:none; font-size:14px;">
                            <i class="bi bi-google me-2"></i>Gửi Lập Chỉ Mục Đã Chọn
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra_css')
<style>
.hover-card:hover {
    border-color: #2563eb !important;
    background: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37,99,235,0.06);
}
.btn-outline-violet {
    color: #7c3aed;
    border-color: #7c3aed;
}
.btn-outline-violet:hover {
    background: #7c3aed;
    color: #fff;
}
.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: .75rem;
    border-radius: 0.2rem;
}
</style>
@endsection

@section('extra_js')
<script>
// Quota tracking
const maxQuota = 200;
const quotaUsed = {{ $quotaUsed }};
const remainingQuota = maxQuota - quotaUsed;

// Thêm một ô nhập URL riêng lẻ
function addUrlRow() {
    const container = document.getElementById('customUrlsContainer');
    const row = document.createElement('div');
    row.className = 'd-flex gap-2 mb-2 align-items-center';
    row.innerHTML = `
        <input type="url" name="custom_urls[]" class="form-control" placeholder="https://vpnstore.pro/nhap-url-tuy-chinh-o-day" style="border-radius:10px; font-size:13px;">
        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-0" onclick="this.parentElement.remove()" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center;">
            <i class="bi bi-trash"></i>
        </button>
    `;
    container.appendChild(row);
    // Focus vào input mới thêm
    row.querySelector('input').focus();
}

// Bật/Tắt chọn tất cả checkboxes
let allSelected = false;
function toggleSelectAll(btn) {
    const checkboxes = document.querySelectorAll('.url-checkbox');
    allSelected = !allSelected;
    checkboxes.forEach(cb => cb.checked = allSelected);
    
    if (allSelected) {
        btn.innerHTML = '<i class="bi bi-x-circle me-1"></i>Bỏ Chọn';
        btn.classList.replace('btn-outline-primary', 'btn-danger');
    } else {
        btn.innerHTML = '<i class="bi bi-check2-all me-1"></i>Chọn Tất Cả';
        btn.classList.replace('btn-danger', 'btn-outline-primary');
    }
}

// Xử lý gửi toàn bộ URL hệ thống
function submitAll() {
    if (remainingQuota <= 0) {
        alert("Hạn ngạch Google Indexing hôm nay đã hết (200/200 URL). Bạn không thể gửi thêm.");
        return;
    }

    const checkboxes = document.querySelectorAll('.url-checkbox');
    checkboxes.forEach(cb => cb.checked = true);

    // Tính tổng số lượng URL được chọn gửi đi
    const count = checkboxes.length;

    if (count === 0) {
        alert("Không tìm thấy URL nào trong danh sách.");
        return;
    }

    let confirmMsg = `Bạn đang chọn "INDEX TOÀN BỘ". Hệ thống sẽ tự động chọn và gửi toàn bộ ${count} URL tĩnh và thương hiệu của trang web lên Google API.\n\nHạn ngạch còn lại hôm nay: ${remainingQuota} URL.\nBạn có chắc chắn muốn gửi không?`;
    
    if (count > remainingQuota) {
        confirmMsg = `Cảnh báo: Bạn đang chọn gửi ${count} URL nhưng hạn ngạch còn lại hôm nay chỉ còn ${remainingQuota} URL.\nMột số URL vượt quá giới hạn sẽ bị bỏ qua.\n\nBạn vẫn muốn tiếp tục chứ?`;
    }

    if (confirm(confirmMsg)) {
        // Submit form
        document.getElementById('indexingForm').submit();
    }
}

// Kiểm tra trước khi submit form thông thường
document.getElementById('indexingForm').addEventListener('submit', function(e) {
    // Đếm số lượng checkbox được chọn
    const checkedBoxes = document.querySelectorAll('.url-checkbox:checked');
    const customInputs = document.querySelectorAll('input[name="custom_urls[]"]');
    
    let customCount = 0;
    customInputs.forEach(input => {
        if (input.value.trim() !== "") customCount++;
    });

    const totalSelected = checkedBoxes.length + customCount;

    if (totalSelected === 0) {
        e.preventDefault();
        alert("Vui lòng chọn ít nhất một URL hoặc nhập URL tùy chỉnh trước khi gửi.");
        return;
    }

    if (remainingQuota <= 0) {
        e.preventDefault();
        alert("Hạn ngạch Google Indexing hôm nay đã hết (200/200 URL). Bạn không thể gửi thêm.");
        return;
    }

    if (totalSelected > remainingQuota) {
        const confirmSub = confirm(`Bạn đã chọn ${totalSelected} URL để lập chỉ mục, tuy nhiên hạn ngạch hôm nay chỉ còn lại ${remainingQuota} URL.\nMột số URL vượt quá sẽ không thể gửi đi.\n\nBạn vẫn muốn tiếp tục chứ?`);
        if (!confirmSub) {
            e.preventDefault();
        }
    }
});
</script>
@endsection
