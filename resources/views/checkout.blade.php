@extends('layouts.app')

@section('title', 'Thanh Toán - VPNStore')

@section('content')

<!-- BREADCRUMB -->
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart') }}">Giỏ Hàng</a></li>
                <li class="breadcrumb-item active">Thanh Toán</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-header">
    <div class="container">
        <h1 class="section-title mb-1">
            <i class="bi bi-credit-card me-3 text-primary"></i>Thanh Toán
        </h1>
        <p class="text-muted mb-0">Hoàn tất đơn hàng của bạn một cách an toàn</p>
    </div>
</div>

<div class="container py-5">
    <!-- Steps -->
    <div class="d-flex align-items-center mb-5 checkout-step">
        <div class="step-dot done" style="min-width:32px"><i class="bi bi-check-lg"></i></div>
        <div class="ms-2 me-3" style="white-space:nowrap"><small class="fw-700 text-success">Giỏ Hàng</small></div>
        <div class="step-line done"></div>
        <div class="step-dot active mx-3" style="min-width:32px"><i class="bi bi-credit-card"></i></div>
        <div class="ms-1 me-3" style="white-space:nowrap"><small class="fw-700 text-primary">Thanh Toán</small></div>
        <div class="step-line"></div>
        <div class="step-dot pending mx-3" style="min-width:32px">3</div>
        <div class="ms-1" style="white-space:nowrap"><small class="fw-600 text-muted">Hoàn Tất</small></div>
    </div>

    <form id="checkoutForm">
        @csrf
    <div class="row g-4">
        <!-- Left: Form -->
        <div class="col-lg-8">

            <!-- Customer Info -->
            <div class="checkout-section">
                <div class="checkout-section-title">
                    <i class="bi bi-person-fill"></i>Thông Tin Khách Hàng
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Nguyễn Văn A" required id="full-name" value="{{ auth()->user()->name ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" placeholder="email@gmail.com" required id="email" value="{{ auth()->user()->email ?? '' }}">
                        <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Key VPN sẽ được gửi về email này</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số Điện Thoại <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" placeholder="0909 999 999" required id="phone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telegram / Zalo <small class="text-muted">(không bắt buộc)</small></label>
                        <input type="text" class="form-control" placeholder="@username hoặc số Zalo" id="telegram">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi Chú <small class="text-muted">(không bắt buộc)</small></label>
                        <textarea class="form-control" rows="2" placeholder="Yêu cầu đặc biệt, ghi chú cho shop..." id="note"></textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="checkout-section">
                <div class="checkout-section-title">
                    <i class="bi bi-wallet2"></i>Phương Thức Thanh Toán
                </div>

                <div class="payment-method-card selected" onclick="selectPayment(this, 'bank_transfer')">
                    <input type="radio" name="payment" value="bank_transfer" checked>
                    <div class="payment-icon" style="background:#e3f2fd">
                        <i class="bi bi-bank text-primary" style="font-size:18px"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-700" style="font-size:14px">Chuyển Khoản Ngân Hàng</div>
                        <div class="text-muted" style="font-size:12.5px">Vietcombank, Techcombank, MB Bank, BIDV...</div>
                    </div>
                    <span class="badge bg-success text-white" style="font-size:11px">Phổ Biến Nhất</span>
                </div>

                <div class="payment-method-card" onclick="selectPayment(this, 'momo')" style="display:none!important">
                    <input type="radio" name="payment" value="momo">
                    <div class="payment-icon" style="background:#f3e5f5">
                        <i class="bi bi-phone-fill" style="color:#9c27b0;font-size:18px"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-700" style="font-size:14px">Ví MoMo</div>
                        <div class="text-muted" style="font-size:12.5px">Quét mã QR hoặc chuyển qua số điện thoại</div>
                    </div>
                </div>

                <div class="payment-method-card" onclick="selectPayment(this, 'zalopay')" style="display:none!important">
                    <input type="radio" name="payment" value="zalopay">
                    <div class="payment-icon" style="background:#e8f5e9">
                        <i class="bi bi-wallet2" style="color:#1565c0;font-size:18px"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-700" style="font-size:14px">ZaloPay</div>
                        <div class="text-muted" style="font-size:12.5px">Thanh toán nhanh qua Zalo</div>
                    </div>
                </div>

                <div class="payment-method-card" onclick="selectPayment(this, 'crypto')" style="display:none!important">
                    <input type="radio" name="payment" value="crypto">
                    <div class="payment-icon" style="background:#fff3e0">
                        <i class="bi bi-currency-bitcoin" style="color:#e65100;font-size:18px"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-700" style="font-size:14px">Cryptocurrency</div>
                        <div class="text-muted" style="font-size:12.5px">Bitcoin, USDT (TRC20), ETH...</div>
                    </div>
                </div>

                <!-- Bank Transfer Details -->
                <div id="bank-transfer-detail" class="mt-3 p-4 rounded-3" style="background:var(--primary-light);border:1.5px solid var(--primary-100)">
                    <h6 class="fw-700 mb-3 text-primary" style="font-size:14px">
                        <i class="bi bi-bank me-2"></i>Thông Tin Chuyển Khoản
                    </h6>
                    <div class="row g-3" style="font-size:13.5px">
                        <div class="col-md-7">
                            <div class="p-3 bg-white rounded-2 mb-2" style="border:1px solid var(--primary-100)">
                                <div class="text-muted small mb-1">Ngân hàng</div>
                                <div class="fw-700">OCB (Ngân hàng Phương Đông)</div>
                            </div>
                            <div class="p-3 bg-white rounded-2 mb-2" style="border:1px solid var(--primary-100)">
                                <div class="text-muted small mb-1">Số tài khoản</div>
                                <div class="fw-700 d-flex align-items-center gap-2">
                                    0772698113
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:11px" onclick="copyText('0772698113')">
                                        <i class="bi bi-copy"></i> Sao Chép
                                    </button>
                                </div>
                            </div>
                            <div class="p-3 bg-white rounded-2 mb-2" style="border:1px solid var(--primary-100)">
                                <div class="text-muted small mb-1">Chủ tài khoản</div>
                                <div class="fw-700">TRAN THANH TUAN</div>
                            </div>
                            <div class="p-3 bg-white rounded-2" style="border:1px solid var(--primary-100)">
                                <div class="text-muted small mb-1">Nội dung CK</div>
                                <div class="fw-700 text-primary d-flex align-items-center gap-2">
                                    <span id="bank-transfer-note">VPN...</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:11px" onclick="copyText(document.getElementById('bank-transfer-note').textContent)">
                                        <i class="bi bi-copy"></i> Sao Chép
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 text-center d-flex flex-column align-items-center justify-content-center">
                            <div class="bg-white p-2 rounded-3 border mb-2" style="width:170px;height:170px;display:flex;align-items:center;justify-content:center">
                                <img id="vietqr-code" src="https://img.vietqr.io/image/OCB-0772698113-compact.png" alt="Mã QR Chuyển Khoản" style="max-width:100%;max-height:100%;object-fit:contain">
                            </div>
                            <div style="font-size:11.5px;color:var(--gray-500);font-weight:600">
                                <i class="bi bi-qr-code-scan me-1"></i> Quét QR để thanh toán nhanh
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 p-2 rounded-2 d-flex gap-2" style="background:#fff8e1;border:1px solid #ffe082">
                        <i class="bi bi-exclamation-triangle-fill text-warning flex-shrink-0 mt-1"></i>
                        <div style="font-size:12.5px;color:var(--gray-600)">
                            Sau khi chuyển tiền gửi ảnh chụp màn hình xác nhận Chuyển Khoản và bấm nút <strong>Xác Nhận Đặt Mua</strong> để hệ thống ghi nhận đơn hàng.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Order Summary -->
        <div class="col-lg-4">
            <div class="cart-summary-card">
                <h5 class="fw-800 mb-4" style="font-size:16px;color:var(--gray-900)">
                    <i class="bi bi-receipt me-2 text-primary"></i>Đơn Hàng Của Bạn
                </h5>

                <!-- Cart Items Summary -->
                <div id="checkout-cart-items" class="mb-3 pb-3 border-bottom">
                    <!-- Rendered by JS -->
                    <div class="text-center text-muted py-2" style="font-size:13px">Đang tải...</div>
                </div>

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
                    <span>Phí vận chuyển</span>
                    <span class="fw-600 text-success">Miễn phí</span>
                </div>

                <div class="cart-summary-total mt-3">
                    <span>Tổng Cộng</span>
                    <span class="price" id="cart-total">0đ</span>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-700 mt-4 rounded-pill" style="font-size:15px" id="checkout-submit">
                    <i class="bi bi-shield-check me-2"></i>Xác Nhận Đặt Mua
                </button>

                <!-- Guarantee -->
                <div class="mt-3 text-center">
                    <div class="d-flex justify-content-center gap-3 flex-wrap" style="font-size:12px;color:var(--gray-500)">
                        <span><i class="bi bi-shield-check text-success me-1"></i>SSL Bảo Mật</span>
                        <span><i class="bi bi-arrow-repeat text-primary me-1"></i>Hoàn tiền 30 ngày</span>
                        <span><i class="bi bi-headset text-warning me-1"></i>Hỗ trợ 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<!-- Confirm Payment Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow" style="border: 1px solid var(--gray-200)!important">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-800 fs-5 text-gray-900" id="confirmPaymentModalLabel">
                    <i class="bi bi-question-circle-fill text-primary me-2"></i>Xác Nhận Đặt Mua
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="mb-0 text-muted" style="font-size:14.5px;line-height:1.6">
                    Bạn đã chắc chắn thanh toán chuyển khoản cho đơn hàng này chưa?<br>
                    <small class="text-danger fw-600"><i class="bi bi-info-circle me-1"></i>Vui lòng hoàn tất chuyển khoản trước khi xác nhận để tránh lỗi xử lý đơn hàng.</small>
                </p>
            </div>
            <div class="modal-footer border-top-0 pt-0 d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-600 btn-sm" data-bs-dismiss="modal">Chưa, Để Tôi Xem Lại</button>
                <button type="button" id="confirm-payment-yes" class="btn btn-primary rounded-pill px-4 fw-600 btn-sm">Tôi Đã Thanh Toán</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
window.stockMap = @json($stockMap);
// Generate a unique order code on page load
const orderCode = (function() {
    const chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    let code = 'VPN';
    for (let i = 0; i < 8; i++) {
        code += chars[Math.floor(Math.random() * chars.length)];
    }
    return code;
})();

function selectPayment(card, method) {
    document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    card.querySelector('input[type="radio"]').checked = true;
    const bankDetail = document.getElementById('bank-transfer-detail');
    if (bankDetail) bankDetail.style.display = method === 'bank_transfer' ? 'block' : 'none';
}

function copyText(text) {
    navigator.clipboard.writeText(text).then(() => showToast('Đã sao chép: ' + text, 'success'));
}

// Render cart summary on load
document.addEventListener('DOMContentLoaded', function() {
    const cart = CartManager.getCart();
    const summaryEl = document.getElementById('checkout-cart-items');
    const subtotalEl = document.getElementById('cart-subtotal');
    const discountEl = document.getElementById('cart-discount');
    const totalEl    = document.getElementById('cart-total');

    if (cart.length === 0 && summaryEl) {
        summaryEl.innerHTML = '<p class="text-center text-muted">Giỏ hàng trống</p>';
    } else if (summaryEl) {
        summaryEl.innerHTML = cart.map(item => {
            const stockKey = (item.brand + '_' + item.plan).toLowerCase().replace(/\s+/g, '');
            const isOutOfStock = window.stockMap !== undefined && window.stockMap[stockKey] !== undefined && window.stockMap[stockKey] <= 0;
            return `
            <div class="checkout-cart-item d-flex align-items-center gap-3 mb-3 ${isOutOfStock ? 'opacity-75' : ''}">
                <div class="cart-item-img" style="width:50px;height:50px;background:${item.brandColor}15;color:${item.brandColor};border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-600" style="font-size:13px">
                        ${item.name}
                        ${isOutOfStock ? '<span class="badge bg-danger ms-2" style="font-size:10.5px">Hết Hàng</span>' : ''}
                    </div>
                    <div style="font-size:11.5px;color:var(--gray-400)">Gói: ${item.plan} · SL: ${item.qty}</div>
                </div>
                <div class="fw-700 text-primary" style="font-size:14px">${(item.price * item.qty).toLocaleString('vi-VN')}đ</div>
            </div>
            `;
        }).join('');
    }

    // Lấy discount settings từ server
    const siteSettings = {!! json_encode([
        'auto_discount_threshold' => (int)($settings['auto_discount_threshold'] ?? 500000),
        'auto_discount_rate'      => (float)($settings['auto_discount_rate'] ?? 5),
        'bank_code'               => $settings['bank_code'] ?? 'OCB',
        'bank_account_number'     => $settings['bank_account_number'] ?? '',
        'bank_account_name'       => $settings['bank_account_name'] ?? '',
    ]) !!};

    const subtotal = CartManager.getTotal();
    const autoDiscount = subtotal > siteSettings.auto_discount_threshold
        ? Math.round(subtotal * siteSettings.auto_discount_rate / 100)
        : 0;
    const couponCode = CartManager.getCoupon();
    const couponDiscount = CartManager.getCouponDiscount(subtotal);
    const total = Math.max(0, subtotal - autoDiscount - couponDiscount);

    if (subtotalEl) subtotalEl.textContent = subtotal.toLocaleString('vi-VN') + 'đ';
    if (discountEl) discountEl.textContent = autoDiscount > 0 ? '-' + autoDiscount.toLocaleString('vi-VN') + 'đ' : '0đ';
    
    const couponEl = document.getElementById('cart-coupon');
    if (couponEl) {
        if (couponCode) {
            couponEl.innerHTML = `<span class="badge bg-success-light text-success">${couponCode}</span> -${couponDiscount.toLocaleString('vi-VN')}đ`;
        } else {
            couponEl.textContent = 'Chưa áp dụng';
        }
    }
    
    if (totalEl) totalEl.textContent = total.toLocaleString('vi-VN') + 'đ';

    // Show unique order code as bank transfer note
    const transferNote = document.getElementById('bank-transfer-note');
    if (transferNote) {
        transferNote.textContent = orderCode;
    }

    // Set VietQR code image từ settings
    const qrImage = document.getElementById('vietqr-code');
    if (qrImage && siteSettings.bank_account_number) {
        qrImage.src = `https://img.vietqr.io/image/${siteSettings.bank_code}-${siteSettings.bank_account_number}-compact2.png?amount=${total}&addInfo=${encodeURIComponent(orderCode)}&accountName=${encodeURIComponent(siteSettings.bank_account_name)}`;
    }
});

// Checkout form submit
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const cart = CartManager.getCart();
    if (cart.length === 0) { showToast('Giỏ hàng trống!', 'warning'); return; }

    // Client-side verification: check if any item is out of stock
    let hasOutOfStock = false;
    cart.forEach(item => {
        const stockKey = (item.brand + '_' + item.plan).toLowerCase().replace(/\s+/g, '');
        if (window.stockMap !== undefined && window.stockMap[stockKey] !== undefined && window.stockMap[stockKey] <= 0) {
            hasOutOfStock = true;
        }
    });
    if (hasOutOfStock) {
        showToast('Giỏ hàng của bạn chứa sản phẩm đã hết hàng. Vui lòng quay lại giỏ hàng để loại bỏ trước khi thanh toán!', 'danger');
        return;
    }

    // Show confirm payment modal
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
    confirmModal.show();
});

// Confirm Yes button click handler
document.getElementById('confirm-payment-yes')?.addEventListener('click', function() {
    // Hide modal
    const modalEl = document.getElementById('confirmPaymentModal');
    const modalInstance = bootstrap.Modal.getInstance(modalEl);
    modalInstance?.hide();

    // Trigger purchase submission
    submitCheckout();
});

function submitCheckout() {
    const cart = CartManager.getCart();
    const btn = document.getElementById('checkout-submit');
    if (btn) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
        btn.disabled = true;
    }

    const token = document.querySelector('input[name="_token"]')?.value;

    fetch('{{ route("checkout.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            order_code: orderCode,
            customer_name: document.getElementById('full-name').value,
            customer_email: document.getElementById('email').value,
            customer_phone: document.getElementById('phone').value,
            telegram: document.getElementById('telegram').value,
            note: document.getElementById('note').value,
            payment_method: document.querySelector('input[name="payment"]:checked').value,
            coupon: CartManager.getCoupon(),
            cart: cart
        })
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => { throw err; });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            CartManager.clear();
            window.location.href = '/order/success?order=' + data.order_code;
        } else {
            showToast(data.message || 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
            if (btn) {
                btn.innerHTML = '<i class="bi bi-shield-check me-2"></i>Xác Nhận Đặt Mua';
                btn.disabled = false;
            }
        }
    })
    .catch(err => {
        showToast(err.message || 'Có lỗi kết nối xảy ra!', 'danger');
        if (btn) {
            btn.innerHTML = '<i class="bi bi-shield-check me-2"></i>Xác Nhận Đặt Mua';
            btn.disabled = false;
        }
    });
}
</script>
@endsection
