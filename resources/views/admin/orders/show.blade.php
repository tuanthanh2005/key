@extends('admin.layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng')
@section('page_title', 'Chi Tiết Đơn Hàng #' . $order->order_code)
@section('breadcrumb', 'Admin / Đơn Hàng / #' . $order->order_code)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Order Info -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-receipt text-primary"></i>
                    Thông Tin Đơn Hàng
                </div>
                <span class="admin-badge admin-badge-{{ $order->getStatusBadge() }} fs-6">
                    {{ $order->getStatusLabel() }}
                </span>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--admin-border)">
                            <div class="text-muted mb-1" style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Mã Đơn Hàng</div>
                            <div class="fw-800 text-primary font-poppins" style="font-size:22px">#{{ $order->order_code }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--admin-border)">
                            <div class="text-muted mb-1" style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Ngày Đặt</div>
                            <div class="fw-700" style="font-size:15px">{{ $order->created_at->format('H:i — d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--admin-border)">
                            <div class="text-muted mb-1" style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Sản Phẩm</div>
                            <div class="fw-700">{{ $order->product_name }}</div>
                            <div class="text-muted" style="font-size:12.5px">{{ $order->brand }} · {{ ucfirst($order->plan) }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--admin-border)">
                            <div class="text-muted mb-1" style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Thanh Toán</div>
                            <div class="fw-700">{{ $order->getPaymentMethodLabel() }}</div>
                            <span class="admin-badge admin-badge-{{ $order->getPaymentBadge() }}" style="font-size:11px">
                                {{ match($order->payment_status){'pending'=>'Chờ TT','paid'=>'Đã TT','failed'=>'Thất Bại','refunded'=>'Hoàn Tiền',default=>$order->payment_status} }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="mt-4 p-3 rounded-3" style="background:linear-gradient(135deg,#dbeafe,#ede9fe);border:1px solid #bfdbfe">
                    <div class="d-flex justify-content-between mb-2" style="font-size:13.5px">
                        <span>Tạm tính ({{ $order->quantity }}x)</span>
                        <span class="fw-600">{{ number_format($order->price) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:13.5px">
                        <span>Giảm giá</span>
                        <span class="fw-600 text-success">-{{ number_format($order->discount) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between fw-800 border-top pt-2 mt-2" style="font-size:17px">
                        <span>Tổng Cộng</span>
                        <span class="text-primary">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>

                <!-- License Key -->
                @if($order->license_key)
                <div class="mt-4 p-3 rounded-3" style="background:#dcfce7;border:1px solid #bbf7d0">
                    <div class="fw-700 text-success mb-2" style="font-size:13px">
                        <i class="bi bi-key-fill me-2"></i>Key VPN Đã Giao
                    </div>
                    <code style="font-size:13px;color:#166534;word-break:break-all">{{ $order->license_key }}</code>
                </div>
                @endif
            </div>
        </div>

        <!-- Update Status Form -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-pencil-fill text-primary"></i>
                    Cập Nhật Đơn Hàng
                </div>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:12.5px">Trạng Thái Đơn</label>
                            <select name="order_status" class="form-select" style="border-radius:10px">
                                @foreach(['pending'=>'Chờ Xử Lý','processing'=>'Đang Xử Lý','completed'=>'Hoàn Tất','cancelled'=>'Đã Hủy'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ $order->order_status==$val?'selected':'' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:12.5px">Trạng Thái Thanh Toán</label>
                            <select name="payment_status" class="form-select" style="border-radius:10px">
                                @foreach(['pending'=>'Chờ Thanh Toán','paid'=>'Đã Thanh Toán','failed'=>'Thất Bại','refunded'=>'Hoàn Tiền'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ $order->payment_status==$val?'selected':'' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-size:12.5px">Key VPN (gửi cho khách)</label>
                            <textarea name="license_key" class="form-control" rows="3" placeholder="Nhập key VPN để giao cho khách..." style="border-radius:10px;font-family:monospace">{{ $order->license_key }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600">
                                <i class="bi bi-check-circle me-2"></i>Cập Nhật Đơn Hàng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-person-fill text-primary"></i>
                    Thông Tin Khách Hàng
                </div>
            </div>
            <div class="admin-card-body">
                <div class="text-center mb-4">
                    <div style="width:60px;height:60px;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:24px;margin:0 auto 12px">
                        {{ strtoupper(substr($order->customer_name,0,1)) }}
                    </div>
                    <div class="fw-800" style="font-size:16px">{{ $order->customer_name }}</div>
                </div>
                @foreach([['bi-envelope','Email',$order->customer_email],['bi-telephone','Điện thoại',$order->customer_phone??'—'],['bi-chat-dots','Ghi chú',$order->note??'Không có']] as [$icon,$label,$val])
                <div class="d-flex gap-3 mb-3 pb-3 border-bottom" style="border-color:var(--admin-border)!important">
                    <i class="bi {{ $icon }} text-primary mt-1"></i>
                    <div>
                        <div style="font-size:11.5px;color:var(--admin-muted);font-weight:600">{{ $label }}</div>
                        <div style="font-size:13.5px;font-weight:600;word-break:break-all">{{ $val }}</div>
                    </div>
                </div>
                @endforeach
                <div class="d-grid gap-2 mt-3">
                    <a href="mailto:{{ $order->customer_email }}" class="btn btn-outline-primary rounded-pill btn-sm fw-600">
                        <i class="bi bi-envelope me-2"></i>Gửi Email
                    </a>
                    @if($order->customer_phone)
                    <a href="tel:{{ $order->customer_phone }}" class="btn btn-outline-success rounded-pill btn-sm fw-600">
                        <i class="bi bi-telephone me-2"></i>Gọi Điện
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-arrow-left-right text-primary"></i>
                    Thao Tác Nhanh
                </div>
            </div>
            <div class="admin-card-body d-grid gap-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm fw-600">
                    <i class="bi bi-arrow-left me-2"></i>Về Danh Sách
                </a>
                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Xóa đơn hàng #{{ $order->order_code }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger rounded-pill btn-sm fw-600 w-100">
                        <i class="bi bi-trash me-2"></i>Xóa Đơn Hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
