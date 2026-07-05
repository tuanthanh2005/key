@extends('admin.layouts.admin')

@section('title', 'Chi Tiết Người Dùng')
@section('page_title', 'Chi Tiết Tài Khoản')
@section('breadcrumb', 'Admin / Người Dùng / Chi Tiết')

@section('content')
<div class="row g-4">
    <!-- User Info Card -->
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-person-bounding-box text-primary"></i>
                    Thông Tin Tài Khoản
                </div>
            </div>
            <div class="admin-card-body text-center pb-4">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: linear-gradient(135deg, #2563eb, #7c3aed); border-radius: 50%; color: #fff; font-weight: 800; font-size: 36px; overflow: hidden; border: 3px solid var(--admin-border)">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" style="width:100%; height:100%; object-fit:cover">
                    @else
                        {{ $user->getAvatarInitial() }}
                    @endif
                </div>
                <h4 class="fw-800 mb-1 text-dark">{{ $user->name }}</h4>
                <div class="mb-3">
                    @if($user->role === 'admin')
                        <span class="admin-badge admin-badge-primary"><i class="bi bi-shield-check me-1"></i>Admin</span>
                    @else
                        <span class="admin-badge admin-badge-secondary"><i class="bi bi-person me-1"></i>User</span>
                    @endif

                    @if($user->status === 'active')
                        <span class="admin-badge admin-badge-success ms-1"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                    @else
                        <span class="admin-badge admin-badge-danger ms-1"><i class="bi bi-lock-fill me-1"></i>Bị khóa</span>
                    @endif
                </div>

                <hr class="my-3" style="border-color: var(--admin-border);">

                <div class="text-start">
                    <div class="mb-2">
                        <span class="text-muted small d-block">Địa chỉ Email:</span>
                        <span class="fw-600 text-dark" style="font-size: 14.5px;">{{ $user->email }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small d-block">Số Điện Thoại:</span>
                        <span class="fw-600 text-dark" style="font-size: 14.5px;">{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small d-block">Phương Thức Đăng Nhập:</span>
                        <span class="fw-600 text-dark" style="font-size: 14.5px;">
                            @if($user->google_id)
                                <i class="bi bi-google text-danger me-1"></i> Google OAuth
                            @else
                                <i class="bi bi-envelope text-primary me-1"></i> Email & Mật khẩu
                            @endif
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small d-block">Ngày Tham Gia:</span>
                        <span class="fw-600 text-dark" style="font-size: 14.5px;">{{ $user->created_at->format('H:i d/m/Y') }}</span>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary rounded-pill px-4 flex-grow-1 fw-600">
                        <i class="bi bi-pencil me-1"></i>Chỉnh Sửa
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column (Orders & Coupons) -->
    <div class="col-lg-8">
        <!-- Coupons Card -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-gift-fill text-warning"></i>
                    Mã Giảm Giá Đã Gán ({{ $coupons->count() }})
                </div>
            </div>
            <div class="admin-card-body p-0">
                @if($coupons->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-tag" style="font-size:32px;"></i>
                    <p class="mt-2 mb-0" style="font-size: 13px;">Chưa gán mã giảm giá nào cho người dùng này.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="admin-table table-sm">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Mức Giảm</th>
                                <th>Hạn Sử Dụng</th>
                                <th>Số Lần Dùng</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                            <tr>
                                <td>
                                    <span class="coupon-badge" style="font-size:12px; padding: 2px 6px;">{{ $coupon->code }}</span>
                                    @if($coupon->description)
                                    <div class="text-muted mt-1" style="font-size: 11px;">{{ $coupon->description }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="coupon-type-badge coupon-type-{{ $coupon->discount_type }}" style="font-size: 10px;">
                                        @if($coupon->discount_type === 'percent')
                                            {{ $coupon->discount_value }}%
                                        @else
                                            {{ number_format($coupon->discount_value) }}đ
                                        @endif
                                    </span>
                                </td>
                                <td style="font-size:12px;">
                                    @if($coupon->expires_at)
                                        <span class="{{ $coupon->expires_at->isPast() ? 'text-danger fw-600' : 'text-muted' }}">
                                            {{ $coupon->expires_at->format('d/m/Y H:i') }}
                                        </span>
                                        @if($coupon->expires_at->isPast())
                                            <span class="badge bg-danger ms-1" style="font-size: 9px; padding: 2px 4px;">Hết hạn</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Không hạn</span>
                                    @endif
                                </td>
                                <td style="font-size:12px;">
                                    {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-{{ $coupon->active && ($coupon->expires_at === null || !$coupon->expires_at->isPast()) ? 'success' : 'secondary' }}">
                                        {{ $coupon->active && ($coupon->expires_at === null || !$coupon->expires_at->isPast()) ? 'Active' : 'Tắt' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- Orders Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-cart-fill text-success"></i>
                    Lịch Sử Mua Hàng ({{ $orders->count() }})
                </div>
            </div>
            <div class="admin-card-body p-0">
                @if($orders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-cart-x" style="font-size:40px;"></i>
                    <p class="mt-2 mb-0" style="font-size: 13px;">Người dùng này chưa thực hiện đơn hàng nào.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Mã Đơn</th>
                                <th>Sản Phẩm</th>
                                <th>Tổng Tiền</th>
                                <th>Thanh Toán</th>
                                <th>Đơn Hàng</th>
                                <th>Ngày Mua</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="fw-700 text-decoration-none text-primary">
                                        {{ $order->order_code }}
                                    </a>
                                </td>
                                <td style="font-size:12.5px; max-width: 200px;" class="text-truncate" title="{{ $order->product_name }}">
                                    {{ $order->product_name }}
                                </td>
                                <td class="fw-600 text-dark">{{ number_format($order->total) }}đ</td>
                                <td>
                                    <span class="admin-badge admin-badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ $order->payment_status === 'paid' ? 'Đã trả' : 'Chưa trả' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $oStatusClasses = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $oStatusClass = $oStatusClasses[$order->order_status] ?? 'secondary';
                                    @endphp
                                    <span class="admin-badge admin-badge-{{ $oStatusClass }}">
                                        {{ $order->order_status }}
                                    </span>
                                </td>
                                <td style="font-size:12px; color: var(--admin-muted);">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
