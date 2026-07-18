@extends('admin.layouts.admin')
@section('title', 'Lịch Sử Bàn Giao Key')
@section('page_title', 'Lịch Sử Bàn Giao Key')
@section('breadcrumb', 'Admin / Lịch Sử Bàn Giao')

@section('content')

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px; font-size:13.5px">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="admin-stat-card" style="--card-color:var(--admin-primary)">
            <div class="stat-icon" style="background:rgba(79, 70, 229, 0.1); color:var(--admin-primary)">
                <i class="bi bi-send-fill"></i>
            </div>
            <div>
                <div class="stat-label">Tổng Bàn Giao</div>
                <div class="stat-value" style="font-size: 20px;">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="admin-stat-card" style="--card-color:var(--admin-success)">
            <div class="stat-icon" style="background:rgba(16, 185, 129, 0.1); color:var(--admin-success)">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Bàn Giao Thành Công</div>
                <div class="stat-value" style="font-size: 20px;">{{ number_format($stats['used']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="admin-stat-card" style="--card-color:var(--admin-danger)">
            <div class="stat-icon" style="background:rgba(239, 68, 68, 0.1); color:var(--admin-danger)">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Lỗi Mail (Thất Bại)</div>
                <div class="stat-value" style="font-size: 20px;">{{ number_format($stats['available']) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Pending Orders Card --}}
<div class="admin-card mb-4">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="bi bi-envelope-exclamation-fill text-warning"></i>
            Đơn Hàng Chờ Xử Lý & Bàn Giao Key
            <span class="admin-badge admin-badge-warning ms-2">{{ $pendingOrders->count() }} đơn</span>
        </div>
    </div>
    <div class="admin-card-body p-0">
        @if($pendingOrders->count() > 0)
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách Hàng</th>
                            <th>Sản Phẩm</th>
                            <th>Tổng Tiền</th>
                            <th>Ngày Đặt</th>
                            <th class="text-end">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingOrders as $order)
                            @php
                                $productsList = $order->product_name . ' (SL: ' . $order->quantity . ')';
                            @endphp
                            <tr>
                                <td class="fw-bold text-primary">{{ $order->order_code }}</td>
                                <td>
                                    <div class="fw-bold">{{ $order->customer_name }}</div>
                                    <div class="text-muted" style="font-size:12px;">{{ $order->customer_email }}</div>
                                </td>
                                <td style="max-width:250px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $productsList }}">
                                    {{ $productsList }}
                                </td>
                                <td class="fw-bold" style="font-family:var(--admin-font-mono);">{{ number_format($order->total, 0, '.', '.') }}đ</td>
                                <td class="text-muted" style="font-size:12px;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end" style="padding-right: 24px;">
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3"
                                            onclick="openEmailModal('{{ $order->id }}', '{{ $order->order_code }}', '{{ addslashes($order->customer_name) }}', '{{ $order->customer_email }}', '{{ addslashes($productsList) }}')">
                                        <i class="bi bi-send-fill me-1"></i> Gửi Key & Hoàn Thành
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="bi bi-emoji-smile text-success me-1"></i> Không có đơn hàng nào đang chờ cấp key!
            </div>
        @endif
    </div>
</div>

{{-- Handover History Card --}}
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="admin-card-title"><i class="bi bi-clock-history text-primary"></i> Lịch Sử Bàn Giao Email</div>
        
        {{-- Search & Filters --}}
        <form method="GET" class="d-flex gap-2 align-items-center">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Tìm đơn, email, nội dung..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Tìm kiếm</button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.licenses.index') }}" class="btn btn-sm btn-outline-danger">Xóa lọc</a>
            @endif
        </form>
    </div>
    
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Khách Hàng / Đơn Hàng</th>
                        <th>Sản Phẩm</th>
                        <th>Tiêu Đề Email</th>
                        <th>Nội Dung Bàn Giao</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Bàn Giao</th>
                        <th class="text-end">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($licenses as $license)
                    <tr>
                        <td>
                            @if($license->order)
                                <span class="fw-bold text-primary">{{ $license->order->order_code }}</span>
                                <div class="text-muted" style="font-size:12px;">{{ $license->order->customer_email }}</div>
                            @else
                                <span class="text-muted">— Không có đơn —</span>
                            @endif
                        </td>
                        <td class="fw-bold" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $license->product->name }}
                        </td>
                        <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $license->type }}">
                            {{ $license->type }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size:12px;" onclick="viewHandoverDetails('{{ $license->order?->order_code ?? 'N/A' }}', '{{ $license->order?->customer_email ?? 'N/A' }}', '{{ addslashes($license->type) }}', '{{ rawurlencode($license->license_key) }}')">
                                <i class="bi bi-eye-fill me-1"></i> Xem Nội Dung
                            </button>
                        </td>
                        <td>
                            @if($license->is_used)
                                <span class="badge bg-success-subtle text-success fw-bold" style="font-size:11px; border-radius:6px; padding: 4px 8px;"><i class="bi bi-check-circle-fill me-1"></i> Thành công</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger fw-bold" style="font-size:11px; border-radius:6px; padding: 4px 8px;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Lỗi Mail</span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size:12px;">
                            {{ $license->assigned_at?->format('d/m/Y H:i') ?? '—' }}
                        </td>
                        <td class="text-end" style="padding-right: 24px;">
                            <form action="{{ route('admin.licenses.destroy', $license->id) }}" method="POST" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm p-1 px-2" onclick="return confirm('Bạn chắc chắn muốn xóa bản ghi lịch sử này?')" style="border-radius:8px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có lịch sử bàn giao nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($licenses->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.01);">
            <div style="font-size: 13px; color: var(--admin-muted);">
                Hiển thị {{ $licenses->firstItem() }}–{{ $licenses->lastItem() }} / {{ $licenses->total() }} bản ghi
            </div>
            <div>
                {{ $licenses->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Email Dispatch Modal --}}
<div id="email-dispatch-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--admin-surface); border:1px solid var(--admin-border); border-radius:16px; max-width:650px; width:100%; max-height:90vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.12); animation:fade-in-up 0.3s ease;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--admin-border); display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1.1rem; font-weight:800; color:var(--admin-text); margin-bottom:0;"><i class="bi bi-envelope-fill text-primary" style="margin-right:6px;"></i> Soạn Email Bàn Giao</h3>
            <button onclick="closeEmailModal()" style="background:none; border:none; color:var(--admin-text); font-size:1.5rem; cursor:pointer;">✕</button>
        </div>
        <form action="{{ route('admin.licenses.send_email') }}" method="POST" style="display:flex; flex-direction:column; flex:1; overflow:hidden; margin:0;">
            @csrf
            <input type="hidden" name="order_id" id="modal-order-id">
            <div style="padding:24px; overflow-y:auto; display:flex; flex-direction:column; gap:16px; flex:1;">
                <div class="form-group">
                    <label class="form-label fw-bold">Khách Hàng (Email) <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="modal-customer-email" class="form-control" required placeholder="Nhập email khách hàng...">
                    <div style="font-size:11px; color:var(--admin-muted); margin-top:4px;">
                        <i class="bi bi-info-circle-fill text-primary"></i> Bạn có thể sửa trực tiếp email nhận của khách hàng nếu họ điền sai trước khi nhấn gửi.
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label fw-bold">Tiêu Đề Email <span class="text-danger">*</span></label>
                    <input type="text" name="subject" id="modal-email-subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label fw-bold">Nội Dung Soạn Sẵn <span class="text-danger">*</span></label>
                    <textarea name="content" id="modal-email-content" class="form-control" rows="10" required style="font-family:inherit; font-size:13px;"></textarea>
                    <div style="font-size:11.5px; color:var(--admin-muted); margin-top:4px;">
                        <i class="bi bi-info-circle-fill text-primary"></i> Bạn có thể chỉnh sửa trực tiếp tài khoản/mật khẩu hoặc key của khách hàng vào ô soạn thảo trước khi nhấn gửi.
                    </div>
                </div>
            </div>
            <div style="padding:16px 24px; border-top:1px solid var(--admin-border); display:flex; justify-content:flex-end; gap:12px; background:var(--admin-bg);">
                <button type="button" onclick="closeEmailModal()" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:20px;">Hủy</button>
                <button type="submit" class="btn btn-sm btn-primary px-3" style="border-radius:20px;"><i class="bi bi-send-fill me-1"></i> Gửi Email & Hoàn Thành Đơn</button>
            </div>
        </form>
    </div>
</div>

{{-- Email Content View Modal --}}
<div id="email-view-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--admin-surface); border:1px solid var(--admin-border); border-radius:16px; max-width:650px; width:100%; max-height:85vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.12); animation:fade-in-up 0.3s ease;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--admin-border); display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1.1rem; font-weight:800; color:var(--admin-text); margin-bottom:0;" id="view-email-modal-title"><i class="bi bi-envelope-open-fill text-primary" style="margin-right:6px;"></i> Chi Tiết Email Bàn Giao</h3>
            <button onclick="closeEmailViewModal()" style="background:none; border:none; color:var(--admin-text); font-size:1.5rem; cursor:pointer;">✕</button>
        </div>
        <div style="padding:24px; overflow-y:auto; display:flex; flex-direction:column; gap:16px; flex:1;">
            <div class="form-group">
                <label class="form-label fw-bold">Khách Hàng (Email)</label>
                <input type="text" id="view-customer-email" class="form-control" readonly style="opacity:0.8;">
            </div>
            <div class="form-group">
                <label class="form-label fw-bold">Tiêu Đề Email</label>
                <input type="text" id="view-email-subject" class="form-control" readonly style="opacity:0.8;">
            </div>
            <div class="form-group">
                <label class="form-label fw-bold">Nội Dung Chi Tiết</label>
                <textarea id="view-email-content" class="form-control" rows="12" readonly style="font-family:inherit; font-size:13px; opacity:0.9; background:var(--admin-bg);"></textarea>
            </div>
        </div>
        <div style="padding:16px 24px; border-top:1px solid var(--admin-border); display:flex; justify-content:flex-end; background:var(--admin-bg);">
            <button type="button" onclick="closeEmailViewModal()" class="btn btn-sm btn-outline-secondary px-4" style="border-radius:20px;">Đóng</button>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
function openEmailModal(orderId, orderCode, customerName, customerEmail, productsList) {
    const modal = document.getElementById('email-dispatch-modal');
    const orderIdInput = document.getElementById('modal-order-id');
    const customerEmailInput = document.getElementById('modal-customer-email');
    const subjectInput = document.getElementById('modal-email-subject');
    const contentTextarea = document.getElementById('modal-email-content');

    if (modal && orderIdInput && customerEmailInput && subjectInput && contentTextarea) {
        orderIdInput.value = orderId;
        customerEmailInput.value = customerEmail;
        subjectInput.value = `[VPN Store Pro] Ban giao tai khoan/key don hang ${orderCode}`;
        
        // Define default template
        contentTextarea.value = `Xin chao ${customerName},

Cam on ban da tin tuong va mua sam tai VPN Store Pro!

Duoi day la thong tin tai khoan / license key cho don hang ${orderCode} cua ban:
--------------------------------------------------
San pham: ${productsList}
Thong tin chi tiet:
[NHAP THONG TIN TAI KHOAN:MAT KHAU HOAC KEY TAi DAY]
--------------------------------------------------

Huong dan su dung:
- Vui long dang nhap hoac kich hoat license key theo mo ta san pham tren website.
- Neu can bat ky su tro giup nao, dung ngan ngai nhan tin ho tro qua Telegram: @vpnstorepro de duoc ky thuat vien xu ly trong 5 phut.

Chuc ban co nhung trai nghiem tuyet voi!
VPN Store Pro`;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeEmailModal() {
    const modal = document.getElementById('email-dispatch-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function viewHandoverDetails(orderCode, customerEmail, subject, encodedContent) {
    const modal = document.getElementById('email-view-modal');
    const title = document.getElementById('view-email-modal-title');
    const emailInput = document.getElementById('view-customer-email');
    const subjectInput = document.getElementById('view-email-subject');
    const contentArea = document.getElementById('view-email-content');

    if (modal && emailInput && subjectInput && contentArea) {
        title.innerHTML = `<i class="bi bi-envelope-open-fill text-primary" style="margin-right:6px;"></i> Chi Tiết Bàn Giao: ${orderCode}`;
        emailInput.value = customerEmail;
        subjectInput.value = subject;
        contentArea.value = decodeURIComponent(encodedContent);
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeEmailViewModal() {
    const modal = document.getElementById('email-view-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

document.getElementById('email-dispatch-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEmailModal();
});

document.getElementById('email-view-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEmailViewModal();
});
</script>
@endsection