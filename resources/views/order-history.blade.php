@extends('layouts.app')

@section('title', 'Lịch Sử Đơn Hàng - ' . ($settings['store_name'] ?? 'VPNStore'))

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<section class="page-header" style="padding: 40px 0; background: var(--bg-elevated); border-bottom: 1px solid var(--border);">
    <div class="container">
        <h1 style="font-size:1.8rem; font-weight:800; color:var(--text-primary);">
            <i class="bi bi-clock-history me-2 text-primary"></i> Đơn Hàng Của Tôi
        </h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:0;">
            Theo dõi trạng thái giao key và lịch sử các gói phần mềm bản quyền đã mua.
        </p>
    </div>
</section>

{{-- ===== MAIN GRID ===== --}}
<section class="section">
    <div class="container" style="max-width: 1000px;">
        @if($orders->isEmpty())
            <div class="card text-center" style="padding: 60px 24px;">
                <div style="font-size:4rem; color:var(--text-muted); margin-bottom:16px;"><i class="bi bi-folder-x"></i></div>
                <h2 style="font-size:1.25rem; font-weight:800; color:var(--text-primary); margin-bottom:8px;">Chưa Có Đơn Hàng Nào</h2>
                <p style="color:var(--text-muted); margin-bottom:24px; font-size:0.9rem;">Bạn chưa thực hiện bất kỳ giao dịch mua hàng nào trên hệ thống.</p>
                <a href="{{ route('products') }}" class="btn btn-primary" style="padding:12px 24px;"><i class="bi bi-cart-plus me-1"></i> Khám Phá Sản Phẩm</a>
            </div>
        @else
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Sản Phẩm</th>
                            <th>Tổng Tiền</th>
                            <th>Ngày Đặt</th>
                            <th>Trạng Thái</th>
                            <th style="text-align:right;">KEY / TÀI KHOẢN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('order.check') }}?order={{ $order->order_code }}" style="font-family:var(--font-mono); font-size:0.85rem; color:var(--primary-light); font-weight:700; text-decoration:none;">
                                    {{ $order->order_code }}
                                </a>
                            </td>
                            <td>
                                <strong style="font-size:0.875rem; color:var(--text-primary);">{{ $order->product_name }}</strong>
                            </td>
                            <td>
                                <span style="font-family:var(--font-mono); font-weight:800; color:var(--text-primary);">{{ number_format($order->total) }}đ</span>
                            </td>
                            <td style="font-size:0.8rem; color:var(--text-muted);">
                                {{ $order->created_at->format('H:i d/m/Y') }}
                            </td>
                            <td>
                                @if($order->order_status === 'completed')
                                    <span class="status-badge status-completed">Hoàn Tất</span>
                                @elseif($order->order_status === 'cancelled')
                                    <span class="status-badge status-cancelled">Đã Hủy</span>
                                @else
                                    <span class="status-badge status-pending">Chờ Xử Lý</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                @if($order->order_status === 'completed')
                                    <div style="display:flex; justify-content:flex-end; gap:8px; align-items:center;">
                                        @if(!$order->is_reviewed)
                                            <a href="{{ route('order.check') }}?order={{ $order->order_code }}" class="btn btn-warning btn-sm" style="padding:6px 12px; font-size:0.8rem; color:#fff; border:none; border-radius:var(--radius); cursor:pointer; background:#f59e0b; text-decoration:none;">
                                                <i class="bi bi-star-fill me-1"></i> Đánh Giá
                                            </a>
                                        @else
                                            <span class="text-success" style="font-size:0.75rem; font-weight:600;"><i class="bi bi-check-circle-fill"></i> Đã đánh giá</span>
                                        @endif

                                        @if(!empty($order->license_key))
                                            <button class="btn btn-primary btn-sm btn-view-license" 
                                                    style="padding:6px 12px; font-size:0.8rem; background:var(--primary); color:#fff; border:none; border-radius:var(--radius); cursor:pointer;"
                                                    data-code="{{ $order->order_code }}"
                                                    data-product="{{ $order->product_name }}"
                                                    data-license="{{ $order->license_key }}">
                                                <i class="bi bi-eye-fill me-1"></i> Xem
                                            </button>
                                        @else
                                            <span style="font-size:0.8rem; color:var(--text-muted);"><i class="bi bi-hourglass-split me-1"></i>Đang tạo key...</span>
                                        @endif
                                    </div>
                                @elseif($order->order_status === 'cancelled')
                                    <span style="font-size:0.8rem; color:var(--danger);"><i class="bi bi-x-circle me-1"></i>Đã Hủy</span>
                                @else
                                    <span style="font-size:0.8rem; color:var(--warning); font-weight:600;"><i class="bi bi-hourglass-split me-1"></i>Chờ xác nhận CK</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>

@endsection

@section('extra_js')
{{-- ===== LICENSE DETAILS MODAL ===== --}}
<div id="licenseModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.7); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--bg-elevated); border:1px solid var(--border); border-radius:var(--radius-xl); max-width:550px; width:100%; padding:24px; box-shadow:var(--shadow-card-hover); text-align:left; display:flex; flex-direction:column; gap:16px;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border); padding-bottom:12px;">
            <h3 style="font-size:1.15rem; font-weight:800; color:var(--text-primary); margin-bottom:0; text-align:left;" id="modal-product-title">Chi Tiết License Key</h3>
            <button type="button" onclick="closeLicenseModal()" style="background:none; border:none; color:var(--text-muted); font-size:1.5rem; cursor:pointer;">✕</button>
        </div>
        
        <div style="text-align:left;">
            <div style="font-size:0.8rem; color:var(--text-muted); margin-bottom:4px;">Mã Đơn Hàng: <strong id="modal-order-code" style="color:var(--primary-light);"></strong></div>
            <div style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:12px;">Thông tin tài khoản hoặc License Key kích hoạt:</div>
            
            <div style="position:relative; background:var(--bg-input); border:1px solid var(--border); border-radius:var(--radius); padding:16px; font-family:var(--font-mono); font-size:0.9rem; color:var(--success); font-weight:500; white-space:pre-wrap; max-height:260px; overflow-y:auto; line-height:1.6;" id="modal-license-content">
            </div>
        </div>
        
        <div style="display:flex; gap:12px; margin-top:8px;">
            <button type="button" class="btn btn-outline" onclick="closeLicenseModal()" style="flex:1; padding:12px;">Đóng</button>
            <button type="button" class="btn btn-primary" onclick="copyModalLicense()" style="flex:1; padding:12px; font-weight:700;"><i class="bi bi-clipboard me-2"></i> Sao Chép Key</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-view-license').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            const product = this.getAttribute('data-product');
            const license = this.getAttribute('data-license');
            openLicenseModal(code, product, license);
        });
    });
});

function openLicenseModal(code, product, license) {
    document.getElementById('modal-product-title').innerText = product;
    document.getElementById('modal-order-code').innerText = '#' + code;
    document.getElementById('modal-license-content').innerText = license;
    document.getElementById('licenseModal').style.display = 'flex';
}

function closeLicenseModal() {
    document.getElementById('licenseModal').style.display = 'none';
}

function copyModalLicense() {
    const text = document.getElementById('modal-license-content').innerText;
    navigator.clipboard.writeText(text).then(() => {
        showToast('Đã sao chép thông tin key thành công!', 'success');
    });
}

// Close when clicking outside modal body
document.getElementById('licenseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLicenseModal();
    }
});
</script>
@endsection
