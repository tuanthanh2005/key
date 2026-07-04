@extends('layouts.app')

@section('title', 'Giỏ Hàng - VPNStore')

@section('content')

<!-- BREADCRUMB -->
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Trang Chủ</a></li>
                <li class="breadcrumb-item active">Giỏ Hàng</li>
            </ol>
        </nav>
    </div>
</div>

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="container">
        <h1 class="section-title mb-1">
            <i class="bi bi-bag me-3 text-primary"></i>Giỏ Hàng Của Bạn
        </h1>
        <p class="text-muted mb-0">Kiểm tra lại đơn hàng trước khi thanh toán</p>
    </div>
</div>

<div class="container py-5">
    <!-- Checkout Steps -->
    <div class="d-flex align-items-center mb-5 checkout-step">
        <div class="step-dot active" style="min-width:32px"><i class="bi bi-bag"></i></div>
        <div class="ms-2 me-3" style="white-space:nowrap"><small class="fw-700 text-primary">Giỏ Hàng</small></div>
        <div class="step-line"></div>
        <div class="step-dot pending mx-3" style="min-width:32px">2</div>
        <div class="ms-1 me-3" style="white-space:nowrap"><small class="fw-600 text-muted">Thanh Toán</small></div>
        <div class="step-line"></div>
        <div class="step-dot pending mx-3" style="min-width:32px">3</div>
        <div class="ms-1" style="white-space:nowrap"><small class="fw-600 text-muted">Hoàn Tất</small></div>
    </div>

    <div class="row g-4">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <!-- Coupon Input -->
            <div class="bg-white border rounded-3 p-3 mb-4 d-flex gap-2" style="border-color:var(--gray-200)!important">
                <input type="text" class="form-control" placeholder="Nhập mã giảm giá..." id="couponInput" style="max-width:280px">
                <button class="btn btn-outline-primary fw-600 px-4" onclick="applyCoupon()">
                    <i class="bi bi-tag me-1"></i>Áp Dụng
                </button>
                <div class="ms-auto d-none d-md-flex align-items-center gap-2 text-muted" style="font-size:13px">
                    <i class="bi bi-info-circle text-primary"></i>
                @php $firstCoupon = array_key_first($publicCoupons ?? []); @endphp
                @if($firstCoupon)
                <small class="text-muted" style="font-size:11.5px">
                    Dùng mã <strong class="text-primary">{{ $firstCoupon }}</strong> giảm thêm {{ $publicCoupons[$firstCoupon] }}%
                </small>
                @endif
                </div>
            </div>

            <!-- Cart Items Container -->
            <div id="cartItemsContainer">
                <!-- Rendered by JS -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" style="width:2rem;height:2rem"></div>
                    <p class="mt-3 text-muted">Đang tải giỏ hàng...</p>
                </div>
            </div>

            <!-- Continue Shopping -->
            <div class="mt-3">
                <a href="{{ route('products') }}" class="text-primary fw-600" style="font-size:14px">
                    <i class="bi bi-arrow-left me-1"></i>Tiếp Tục Mua Sắm
                </a>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="cart-summary-card">
                <h5 class="fw-800 mb-4" style="font-size:16px;color:var(--gray-900)">
                    <i class="bi bi-receipt me-2 text-primary"></i>Tóm Tắt Đơn Hàng
                </h5>

                <div class="cart-summary-row">
                    <span>Tạm tính</span>
                    <span id="cart-subtotal" class="fw-600">0đ</span>
                </div>
                <div class="cart-summary-row">
                    <span>Giảm giá</span>
                    <span id="cart-discount" class="fw-600 text-success">0đ</span>
                </div>
                <div class="cart-summary-row">
                    <span>Mã giảm giá</span>
                    <span id="cart-coupon" class="fw-600 text-success">Chưa áp dụng</span>
                </div>
                <div class="cart-summary-row">
                    <span>Phí giao hàng</span>
                    <span class="fw-600 text-success"><i class="bi bi-check-circle me-1"></i>Miễn phí</span>
                </div>

                <div class="cart-summary-total mt-3">
                    <span class="fw-800" style="font-size:15px">Tổng Cộng</span>
                    <span class="price" id="cart-total">0đ</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn btn-primary w-100 py-3 fw-700 mt-4 rounded-pill" style="font-size:15px">
                    <i class="bi bi-credit-card me-2"></i>Tiến Hành Thanh Toán
                </a>

                <div class="mt-4">
                    <div class="text-center text-muted mb-3" style="font-size:12.5px">Phương Thức Thanh Toán Chấp Nhận</div>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @foreach([['bi-credit-card','#1565c0','Visa/Master'],['bi-bank','#2e7d32','ATM/Bank'],['bi-phone','#7b1fa2','Momo'],['bi-wallet2','#1a237e','ZaloPay'],['bi-currency-bitcoin','#e65100','Crypto']] as [$ic,$clr,$lbl])
                        <div class="text-center px-2 py-1 rounded-2" style="background:#f8fafc;border:1px solid #e2e8f0;min-width:64px">
                            <i class="bi {{ $ic }}" style="font-size:16px;color:{{ $clr }}"></i>
                            <div style="font-size:9.5px;color:var(--gray-500);margin-top:2px;font-weight:600">{{ $lbl }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="mt-4 p-3 rounded-2 text-center" style="background:var(--success-light);border:1px solid #bbf7d0">
                    <i class="bi bi-shield-fill-check text-success" style="font-size:20px"></i>
                    <div class="fw-700 mt-1" style="font-size:12.5px;color:var(--success)">Thanh toán 100% An Toàn</div>
                    <div style="font-size:11.5px;color:var(--gray-500)">SSL 256-bit · Dữ liệu được mã hóa</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
window.stockMap = @json($stockMap);
// Coupon codes được lấy từ DB qua server-side rendering
const validCouponsFromServer = @json($publicCoupons ?? []);

function applyCoupon() {
    const code = document.getElementById('couponInput').value.trim().toUpperCase();
    if (validCouponsFromServer[code] !== undefined) {
        CartManager.setCoupon(code);
        window.renderCartPage();
        showToast('Áp dụng mã ' + code + ' thành công! Giảm ' + validCouponsFromServer[code] + '%', 'success');
    } else if (code) {
        showToast('Mã giảm giá không hợp lệ hoặc đã hết hạn!', 'danger');
    }
}
</script>
@endsection
