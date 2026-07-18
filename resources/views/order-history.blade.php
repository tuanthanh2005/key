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
                            <th style="text-align:right;">License / Key Của Bạn</th>
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
                                    @if(!empty($order->license_key))
                                        <div style="display:inline-flex; align-items:center; gap:8px; padding:6px 12px; background:rgba(16, 185, 129, 0.05); border:1px solid rgba(16, 185, 129, 0.15); border-radius:var(--radius); font-family:var(--font-mono); font-size:0.8rem; color:var(--success); font-weight:700;">
                                            <span>{{ $order->license_key }}</span>
                                            <button type="button" class="btn btn-ghost btn-sm" onclick="copyTextDirect('{{ $order->license_key }}')" style="padding:0; min-width:unset; height:auto; color:inherit; background:none; border:none; cursor:pointer;"><i class="bi bi-clipboard"></i></button>
                                        </div>
                                    @else
                                        <span style="font-size:0.8rem; color:var(--text-muted);"><i class="bi bi-hourglass-split me-1"></i>Đang tạo key...</span>
                                    @endif
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
<script>
function copyTextDirect(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Đã sao chép: ' + text, 'success');
    });
}
</script>
@endsection
