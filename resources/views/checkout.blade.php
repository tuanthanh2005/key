@extends('layouts.app')

@section('title', 'Thanh Toán - VPN Store Pro')

@section('content')
<section class="section">
    <div class="container" style="max-width:1100px;">
        <a href="{{ route('cart') }}" style="display:inline-flex; align-items:center; gap:8px; font-size:0.875rem; color:var(--text-muted); margin-bottom:24px; transition:var(--transition); text-decoration:none;"
           onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-muted)'">
            <i class="bi bi-arrow-left" style="margin-right:6px;"></i> Quay Lại Giỏ Hàng
        </a>

        <h1 style="font-size:1.75rem; font-weight:800; margin-bottom:8px;"><i class="bi bi-credit-card-2-front-fill text-primary" style="margin-right:8px;"></i> Thanh Toán</h1>
        <p style="color:var(--text-muted); margin-bottom:36px;">Chuyển khoản ngân hàng - Giao hàng tự động</p>

        <form id="checkoutForm">
            @csrf
            <div class="checkout-layout">
                {{-- ===== LEFT: Payment & Inputs Info ===== --}}
                <div style="display:flex; flex-direction:column; gap:20px;">
                    
                    {{-- 1. Info Form --}}
                    <div class="card" style="padding:24px;">
                        <h2 style="font-size:1rem; font-weight:700; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                            <i class="bi bi-person-fill text-primary" style="font-size:1.2rem; margin-right:8px;"></i> 1. Thông Tin Nhận Hàng
                        </h2>
                        <div class="checkout-form-grid">
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Họ và Tên <span style="color:var(--danger);">*</span></label>
                                <input type="text" id="full-name" placeholder="Nguyễn Văn A" required value="{{ auth()->user()->name ?? '' }}" style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 14px; border-radius:var(--radius); font-size:0.85rem; outline:none;">
                            </div>
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Email Nhận Key <span style="color:var(--danger);">*</span></label>
                                <input type="email" id="email" placeholder="example@gmail.com" required value="{{ auth()->user()->email ?? '' }}" style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 14px; border-radius:var(--radius); font-size:0.85rem; outline:none;">
                            </div>
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Số Điện Thoại <span style="color:var(--danger);">*</span></label>
                                <input type="tel" id="phone" placeholder="0909 999 999" required style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 14px; border-radius:var(--radius); font-size:0.85rem; outline:none;">
                            </div>
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Telegram / Zalo <span style="color:var(--text-muted); font-weight:normal;">(không bắt buộc)</span></label>
                                <input type="text" id="telegram" placeholder="@username hoặc số điện thoại Zalo" style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 14px; border-radius:var(--radius); font-size:0.85rem; outline:none;">
                            </div>
                        </div>
                    </div>

                    {{-- 2. Bank Details Card --}}
                    <div class="card" style="border-color:rgba(6,182,212,0.3); padding:24px;">
                        <h2 style="font-size:1rem; font-weight:700; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                            <i class="bi bi-bank text-accent" style="font-size:1.2rem; margin-right:8px;"></i> 2. Thông Tin Chuyển Khoản
                        </h2>

                        {{-- QR Code Display --}}
                        <div style="display:flex; gap:24px; align-items:flex-start; flex-wrap:wrap; margin-bottom:20px;" class="qr-details-layout">
                            <div class="payment-qr-box" style="flex-shrink:0; text-align:center;">
                                <div style="padding:8px; background:#fff; border-radius:12px; display:inline-block; border:1px solid var(--border);">
                                    <img src="" alt="QR Chuyển Khoản" id="vietqr-code" style="width:200px; height:auto; display:block;">
                                </div>
                                <div style="margin-top:10px; font-size:0.7rem; color:var(--text-muted);"><i class="bi bi-qr-code-scan"></i> Quét mã QR để chuyển khoản nhanh</div>
                            </div>

                            <div style="flex:1; min-width:200px;">
                                <div class="bank-info-row">
                                    <span class="bank-info-label">Ngân hàng</span>
                                    <span class="bank-info-value" style="font-size:1rem;"><i class="bi bi-bank" style="margin-right:6px; color:var(--accent);"></i> {{ $settings['bank_name'] ?? 'MB Bank' }}</span>
                                </div>
                                <div class="bank-info-row">
                                    <span class="bank-info-label">Số tài khoản</span>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="bank-info-value" id="account-number">{{ $settings['bank_account_number'] ?? 'Đang cập nhật' }}</span>
                                        <button type="button" class="copy-btn" onclick="copyText('{{ $settings['bank_account_number'] ?? '' }}')"><i class="bi bi-copy" style="margin-right:4px;"></i> Copy</button>
                                    </div>
                                </div>
                                <div class="bank-info-row">
                                    <span class="bank-info-label">Chủ tài khoản</span>
                                    <span class="bank-info-value">{{ strtoupper($settings['bank_account_name'] ?? 'Đang cập nhật') }}</span>
                                </div>
                                <div class="bank-info-row">
                                    <span class="bank-info-label">Số tiền</span>
                                    <span class="bank-info-value" id="bank-transfer-total" style="color:var(--success); font-size:1.1rem;">0đ</span>
                                </div>
                            </div>
                        </div>

                        {{-- Transfer Content --}}
                        <div style="margin-bottom:12px;">
                            <div style="font-size:0.8rem; color:var(--text-muted); margin-bottom:8px; font-weight:600;"><i class="bi bi-chat-left-text-fill" style="margin-right:6px; color:var(--warning);"></i> NỘI DUNG CHUYỂN KHOẢN (BẮT BUỘC)</div>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div class="transfer-content" id="bank-transfer-note" style="flex:1;">VPN...</div>
                                <button type="button" class="copy-btn" onclick="copyText(document.getElementById('bank-transfer-note').textContent)"><i class="bi bi-copy" style="margin-right:4px;"></i> Copy</button>
                            </div>
                            <div style="display:flex; align-items:center; gap:6px; margin-top:8px; font-size:0.775rem; color:var(--warning);">
                                <i class="bi bi-exclamation-triangle-fill" style="margin-right:4px; color:var(--warning);"></i> Nhập đúng nội dung để hệ thống xác nhận tự động
                            </div>
                        </div>
                    </div>

                    {{-- 3. Instructions --}}
                    <div class="card checkout-instructions" style="background:rgba(6,182,212,0.05); border-color:rgba(6,182,212,0.2); padding:24px;">
                        <h3 style="font-size:0.9rem; font-weight:700; margin-bottom:14px; color:var(--accent);"><i class="bi bi-info-circle-fill" style="margin-right:6px; color:var(--accent);"></i> Hướng Dẫn Thanh Toán</h3>
                        <ol style="display:flex; flex-direction:column; gap:10px; list-style:none; counter-reset:steps; padding-left:0;">
                            <li style="display:flex; align-items:flex-start; gap:12px; font-size:0.875rem; color:var(--text-secondary);">
                                <span style="width:24px; height:24px; background:rgba(6,182,212,0.15); color:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; flex-shrink:0;">1</span>
                                Chuyển khoản đúng số tiền và nội dung theo thông tin trên
                            </li>
                            <li style="display:flex; align-items:flex-start; gap:12px; font-size:0.875rem; color:var(--text-secondary);">
                                <span style="width:24px; height:24px; background:rgba(6,182,212,0.15); color:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; flex-shrink:0;">2</span>
                                Nhập thông tin nhận hàng và nhấn nút "Xác Nhận Đặt Mua" bên phải
                            </li>
                            <li style="display:flex; align-items:flex-start; gap:12px; font-size:0.875rem; color:var(--text-secondary);">
                                <span style="width:24px; height:24px; background:rgba(6,182,212,0.15); color:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; flex-shrink:0;">3</span>
                                Đơn hàng sẽ được xử lý kích hoạt tự động trong vòng 5-15 phút
                            </li>
                            <li style="display:flex; align-items:flex-start; gap:12px; font-size:0.875rem; color:var(--text-secondary);">
                                <span style="width:24px; height:24px; background:rgba(6,182,212,0.15); color:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; flex-shrink:0;">4</span>
                                License key / Tài khoản sẽ hiển thị trong trang "Lịch Sử Đơn Hàng"
                            </li>
                        </ol>
                    </div>
                </div>

                {{-- ===== RIGHT: Order Summary ===== --}}
                <div>
                    <div class="card" style="border-color:rgba(124,58,237,0.3); padding:24px; position:sticky; top:88px;">
                        <h3 style="font-size:1rem; font-weight:700; margin-bottom:20px;"><i class="bi bi-box-seam" style="margin-right:6px; color:var(--primary-light);"></i> Chi Tiết Đơn Hàng</h3>

                        {{-- Items Summary --}}
                        <div id="checkout-cart-items" style="max-height:220px; overflow-y:auto; margin-bottom:20px; display:flex; flex-direction:column; gap:12px; padding-bottom:16px; border-bottom:1px solid var(--border);">
                            <div style="text-align:center; padding:20px 0; color:var(--text-muted);">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                            </div>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div style="display:flex; justify-content:space-between; font-size:0.875rem; color:var(--text-muted);">
                                <span>Tạm tính</span>
                                <span id="cart-subtotal" style="font-weight:700; color:var(--text-primary);">0đ</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:0.875rem; color:var(--text-muted);">
                                <span>Mã giảm giá</span>
                                <span id="cart-coupon" style="font-weight:700; color:var(--success);">Chưa áp dụng</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:0.875rem; color:var(--text-muted);">
                                <span>Giảm giá tự động</span>
                                <span id="cart-discount" style="font-weight:700; color:var(--success);">0đ</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:0.875rem; color:var(--text-muted);">
                                <span>Phí giao dịch</span>
                                <span style="color:var(--success); font-weight:700;">Miễn phí</span>
                            </div>
                        </div>

                        <div class="divider"></div>
                        
                        <div style="display:flex; justify-content:space-between; font-weight:900; font-size:1.1rem; margin-bottom:20px;">
                            <span>Tổng Thanh Toán</span>
                            <span id="cart-total" style="font-family:var(--font-mono); color:var(--primary-light);">0đ</span>
                        </div>

                        <div style="margin-top:20px; margin-bottom:20px;">
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Ghi Chú Đơn Hàng <span style="color:var(--text-muted); font-weight:normal;">(tùy chọn)</span></label>
                                <textarea id="note" rows="2" placeholder="Ghi chú yêu cầu đặc biệt..." style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 14px; border-radius:var(--radius); font-size:0.85rem; outline:none; resize:none;"></textarea>
                            </div>
                        </div>

                        <input type="radio" name="payment" value="bank_transfer" checked style="display:none;">

                        <button type="submit" id="checkout-submit" class="btn btn-primary btn-full btn-lg" style="padding:14px; font-weight:700;">
                            <i class="bi bi-shield-check me-2"></i> Xác Nhận Đặt Mua
                        </button>

                        <p style="text-align:center; font-size:0.75rem; color:var(--text-muted); margin-top:12px; line-height:1.5;">
                            Bằng cách đặt hàng, bạn đồng ý với <a href="#" style="color:var(--primary-light); text-decoration:none;">Điều Khoản Dịch Vụ</a> của chúng tôi
                        </p>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

{{-- ===== CONFIRM PAYMENT MODAL (Styled in Cyber theme) ===== --}}
<div id="confirmPaymentModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.7); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--bg-elevated); border:1px solid var(--border); border-radius:var(--radius-xl); max-width:420px; width:100%; padding:24px; box-shadow:var(--shadow-card-hover); animation:fade-in-up 0.3s ease; text-align:center;">
        <div class="text-warning mb-3" style="font-size:3rem;"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <h3 style="font-size:1.15rem; font-weight:800; margin-bottom:10px; color:var(--text-primary);">Xác Nhận Đã Chuyển Khoản?</h3>
        <p style="font-size:0.85rem; color:var(--text-muted); line-height:1.6; margin-bottom:24px;">
            Bạn đã thực hiện chuyển khoản chính xác số tiền và nội dung chuyển khoản? Hệ thống sẽ kiểm tra và kích hoạt key tự động cho bạn ngay lập tức.
        </p>
        <div style="display:flex; gap:12px;">
            <button type="button" class="btn btn-outline" onclick="closeConfirmModal()" style="flex:1; padding:10px;">Xem lại</button>
            <button type="button" id="confirm-payment-yes" class="btn btn-primary" style="flex:1; padding:10px;">Đã Chuyển Khoản</button>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
window.stockMap = @json($stockMap);
window.emailRequireMap = @json($emailRequireMap);

// Generate a unique order code on page load
const orderCode = (function() {
    const chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    let code = 'VPN';
    for (let i = 0; i < 8; i++) {
        code += chars[Math.floor(Math.random() * chars.length)];
    }
    return code;
})();

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
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                <div style="width:40px; height:40px; background:${item.brandColor}15; color:${item.brandColor}; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0;">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:700; font-size:0.8rem; color:var(--text-primary); display:flex; align-items:center;">
                        ${item.name}
                        ${isOutOfStock ? '<span class="badge bg-danger ms-2" style="font-size:9px;">Hết</span>' : ''}
                    </div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">Gói: ${item.plan} · SL: ${item.qty}</div>
                </div>
                <strong style="font-size:0.85rem; color:var(--primary-light); font-family:var(--font-mono);">${(item.price * item.qty).toLocaleString('vi-VN')}đ</strong>
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
    
    // Set bank details amount
    const transferTotal = document.getElementById('bank-transfer-total');
    if (transferTotal) {
        transferTotal.textContent = total.toLocaleString('vi-VN') + 'đ';
    }

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

    // Customize notes label if upgrade email is required
    const requiresEmailInput = cart.some(item => item.requireEmail === true || (window.emailRequireMap && window.emailRequireMap[item.productId] === true));
    if (requiresEmailInput) {
        const noteTextarea = document.getElementById('note');
        const labels = document.querySelectorAll('label');
        let noteLabel = null;
        labels.forEach(l => {
            if (l.textContent.includes('Ghi Chú Đơn Hàng')) {
                noteLabel = l;
            }
        });
        if (noteLabel && noteTextarea) {
            noteLabel.innerHTML = 'Vui lòng nhập tên email nâng cấp <span style="color:var(--danger); font-weight:bold;">*</span>';
            noteTextarea.placeholder = 'Nhập địa chỉ email cần nâng cấp (Ví dụ: account@gmail.com)...';
        }
    }
});

// Checkout form submit
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const cart = CartManager.getCart();
    if (cart.length === 0) { showToast('Giỏ hàng trống!', 'warning'); return; }

    // Verify required upgrade email
    const requiresEmailInput = cart.some(item => item.requireEmail === true || (window.emailRequireMap && window.emailRequireMap[item.productId] === true));
    if (requiresEmailInput) {
        const noteVal = document.getElementById('note').value.trim();
        if (!noteVal) {
            showToast('Vui lòng nhập địa chỉ email cần nâng cấp!', 'danger');
            document.getElementById('note').focus();
            return;
        }
    }

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
    const modalEl = document.getElementById('confirmPaymentModal');
    if (modalEl) modalEl.style.display = 'flex';
});

// Close modal function
window.closeConfirmModal = function() {
    const modalEl = document.getElementById('confirmPaymentModal');
    if (modalEl) modalEl.style.display = 'none';
};

// Confirm Yes button click handler
document.getElementById('confirm-payment-yes')?.addEventListener('click', function() {
    // Hide modal
    closeConfirmModal();

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