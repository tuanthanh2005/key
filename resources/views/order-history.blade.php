@extends('layouts.app')

@section('title', 'Lịch Sử Đơn Hàng - VPNStore')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 class="section-title mb-2">
            <i class="bi bi-clock-history text-primary me-3"></i>Lịch Sử Đơn Hàng
        </h1>
        <p class="text-muted mb-0">Theo dõi trạng thái và quản lý các key VPN bạn đã mua tại VPNStore.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if($orders->isEmpty())
                <div class="card border-0 shadow-sm text-center p-5 rounded-4" style="background:#fff;border:1px solid var(--gray-200)!important">
                    <div class="mb-4 text-muted">
                        <i class="bi bi-folder-x" style="font-size: 80px;color: var(--gray-200);"></i>
                    </div>
                    <h5 class="fw-800 text-gray-800 mb-2">Bạn Chưa Có Đơn Hàng Nào</h5>
                    <p class="text-muted mb-4" style="font-size: 14.5px;">Hãy khám phá các gói VPN chất lượng cao của chúng tôi và đặt mua ngay hôm nay!</p>
                    <div>
                        <a href="{{ route('products') }}" class="btn btn-primary rounded-pill px-4 fw-600">
                            <i class="bi bi-bag-plus me-2"></i>Mua Gói VPN Ngay
                        </a>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background:#fff;border:1px solid var(--gray-200)!important">
                    <div class="card-header bg-white py-3 px-4 border-bottom-0">
                        <h5 class="fw-800 text-gray-900 mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-list-task text-primary"></i>Danh Sách Đơn Hàng ({{ $orders->count() }})
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                            <thead class="table-light text-muted fw-700" style="font-size: 12px;text-transform: uppercase;letter-spacing: .5px;">
                                <tr>
                                    <th class="px-4 py-3">Mã Đơn</th>
                                    <th class="py-3">Sản Phẩm</th>
                                    <th class="py-3">Tổng Tiền</th>
                                    <th class="py-3">Ngày Đặt</th>
                                    <th class="py-3">Trạng Thái</th>
                                    <th class="px-4 py-3 text-end" style="width: 250px;">Key VPN Của Bạn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('order.check') }}?order={{ $order->order_code }}" class="fw-700 text-primary text-decoration-none">
                                                {{ $order->order_code }}
                                            </a>
                                        </td>
                                        <td class="py-3 fw-600 text-gray-800">
                                            {{ $order->product_name }}
                                        </td>
                                        <td class="py-3 fw-700 text-primary">
                                            {{ number_format($order->total) }}đ
                                        </td>
                                        <td class="py-3 text-muted">
                                            {{ $order->created_at->format('H:i d/m/Y') }}
                                        </td>
                                        <td class="py-3">
                                            @if($order->order_status === 'completed')
                                                <span class="badge text-bg-success px-3 py-2 rounded-pill fw-600">Hoàn Tất</span>
                                            @elseif($order->order_status === 'cancelled')
                                                <span class="badge text-bg-secondary px-3 py-2 rounded-pill fw-600">Đã Hủy</span>
                                            @else
                                                <span class="badge text-bg-warning text-white px-3 py-2 rounded-pill fw-600">Đang Xử Lý</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            @if($order->order_status === 'completed')
                                                @if(!empty($order->license_key))
                                                    <div class="d-inline-flex align-items-center gap-1 bg-success-light text-success border border-success-light px-2 py-1 rounded-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 700; font-size: 13px;">
                                                        <span id="key-{{ $order->id }}">{{ $order->license_key }}</span>
                                                        <button type="button" class="btn btn-sm text-success p-0 ms-1" style="font-size: 11px;" onclick="copyTextDirect('{{ $order->license_key }}')">
                                                            <i class="bi bi-copy"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-muted small italic"><i class="bi bi-hourglass-split me-1"></i>Đang khởi tạo key</span>
                                                @endif
                                            @elseif($order->order_status === 'cancelled')
                                                <span class="text-danger small"><i class="bi bi-x-circle me-1"></i>Đã Hủy</span>
                                            @else
                                                <span class="text-warning small fw-600"><i class="bi bi-hourglass-split me-1"></i>Chờ xác nhận CK</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyTextDirect(text) {
    navigator.clipboard.writeText(text).then(() => {
        if (typeof showToast === 'function') {
            showToast('Đã sao chép key: ' + text, 'success');
        } else {
            alert('Đã sao chép: ' + text);
        }
    });
}
</script>
@endsection
