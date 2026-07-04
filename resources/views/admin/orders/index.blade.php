@extends('admin.layouts.admin')

@section('title', 'Quản Lý Đơn Hàng')
@section('page_title', 'Quản Lý Đơn Hàng')
@section('breadcrumb', 'Admin / Đơn Hàng')

@section('content')

<!-- Stats Row -->
<div class="row g-3 mb-4 row-stat-cards">
    @php
    $orderCards = [
        ['label'=>'Tổng Đơn','value'=>number_format($stats['total']),'icon'=>'bi-bag-fill','color'=>'#2563eb','bg'=>'#dbeafe'],
        ['label'=>'Chờ Xử Lý','value'=>number_format($stats['pending']),'icon'=>'bi-clock-fill','color'=>'#d97706','bg'=>'#fef3c7'],
        ['label'=>'Hoàn Tất','value'=>number_format($stats['completed']),'icon'=>'bi-check-circle-fill','color'=>'#16a34a','bg'=>'#dcfce7'],
        ['label'=>'Doanh Thu','value'=>number_format($stats['revenue']).'đ','icon'=>'bi-cash-stack','color'=>'#7c3aed','bg'=>'#ede9fe'],
    ];
    @endphp
    @foreach($orderCards as $c)
    <div class="col-12 col-sm-6 col-md-3">
        <div class="admin-stat-card p-3" style="--card-color:{{ $c['color'] }}">
            <div class="stat-icon" style="background:{{ $c['bg'] }};color:{{ $c['color'] }};width:42px;height:42px;font-size:20px;border-radius:10px">
                <i class="bi {{ $c['icon'] }}"></i>
            </div>
            <div>
                <div class="stat-label">{{ $c['label'] }}</div>
                <div class="stat-value" style="font-size:20px">{{ $c['value'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <div class="admin-card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-600" style="font-size:12.5px">Tìm Kiếm</label>
                <div class="admin-search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="Mã đơn, tên, email..." value="{{ request('search') }}" style="width:100%">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-600" style="font-size:12.5px">Trạng Thái</label>
                <select name="status" class="form-select form-select-sm" style="border-radius:10px">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Chờ Xử Lý</option>
                    <option value="processing" {{ request('status')=='processing'?'selected':'' }}>Đang Xử Lý</option>
                    <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Hoàn Tất</option>
                    <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Đã Hủy</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-600" style="font-size:12.5px">Thanh Toán</label>
                <select name="payment" class="form-select form-select-sm" style="border-radius:10px">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('payment')=='pending'?'selected':'' }}>Chờ TT</option>
                    <option value="paid" {{ request('payment')=='paid'?'selected':'' }}>Đã TT</option>
                    <option value="failed" {{ request('payment')=='failed'?'selected':'' }}>Thất Bại</option>
                    <option value="refunded" {{ request('payment')=='refunded'?'selected':'' }}>Hoàn Tiền</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-600" style="font-size:12.5px">Thương Hiệu</label>
                <select name="brand" class="form-select form-select-sm" style="border-radius:10px">
                    <option value="">Tất cả</option>
                    @foreach(['NordVPN','ExpressVPN','Surfshark','HMA VPN','CyberGhost','PureVPN','ProtonVPN','IPVanish'] as $brand)
                    <option value="{{ $brand }}" {{ request('brand')==$brand?'selected':'' }}>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 flex-grow-1" style="font-size:13px">
                    <i class="bi bi-funnel me-1"></i>Lọc
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="font-size:13px">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="bi bi-list-ul text-primary"></i>
            Danh Sách Đơn Hàng
            <span class="admin-badge admin-badge-secondary ms-2">{{ $orders->total() ?? 0 }} đơn</span>
        </div>
        <button class="btn btn-sm btn-outline-success rounded-pill px-3" style="font-size:12px">
            <i class="bi bi-download me-1"></i>Xuất Excel
        </button>
    </div>
    <div class="table-responsive">
        @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size:48px;color:var(--admin-border)"></i>
            <p class="mt-3 text-muted">Không có đơn hàng nào</p>
        </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Sản Phẩm</th>
                    <th>Tổng Tiền</th>
                    <th>TT Thanh Toán</th>
                    <th>TT Đơn Hàng</th>
                    <th>Ngày Đặt</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <span class="fw-700 text-primary">#{{ $order->order_code }}</span>
                    </td>
                    <td>
                        <div class="fw-600" style="font-size:13.5px">{{ $order->customer_name }}</div>
                        <div class="text-muted" style="font-size:11.5px">{{ $order->customer_email }}</div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600">{{ $order->product_name }}</div>
                        <div style="font-size:11.5px;color:var(--admin-muted)">{{ ucfirst($order->plan) }}</div>
                    </td>
                    <td><span class="fw-700">{{ number_format($order->total) }}đ</span></td>
                    <td>
                        <span class="admin-badge admin-badge-{{ $order->getPaymentBadge() }}">
                            {{ match($order->payment_status){ 'pending'=>'Chờ TT','paid'=>'Đã TT','failed'=>'Thất Bại','refunded'=>'Hoàn Tiền',default=>$order->payment_status } }}
                        </span>
                    </td>
                    <td>
                        <span class="admin-badge admin-badge-{{ $order->getStatusBadge() }}">
                            {{ $order->getStatusLabel() }}
                        </span>
                    </td>
                    <td style="color:var(--admin-muted);font-size:12.5px">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center" title="Xem chi tiết">
                                <i class="bi bi-eye" style="font-size:12px"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Xóa đơn hàng này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center" title="Xóa">
                                    <i class="bi bi-trash" style="font-size:12px"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @if($orders->hasPages())
    <div class="admin-card-body border-top pt-3 d-flex justify-content-between align-items-center">
        <div style="font-size:13px;color:var(--admin-muted)">
            Hiển thị {{ $orders->firstItem() }}–{{ $orders->lastItem() }} / {{ $orders->total() }} đơn hàng
        </div>
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection
