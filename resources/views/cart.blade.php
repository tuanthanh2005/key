@extends('layouts.app')

@section('title', 'Giỏ Hàng - ' . ($settings['store_name'] ?? 'VPNStore'))

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<section class="page-header" style="padding: 40px 0; background: var(--bg-elevated); border-bottom: 1px solid var(--border);">
    <div class="container">
        <h1 style="font-size:1.8rem; font-weight:800; color:var(--text-primary);">
            <i class="bi bi-cart3 text-primary me-2"></i> Giỏ Hàng Của Bạn
        </h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:0;">
            Kiểm tra và cập nhật số lượng sản phẩm trước khi tiếp tục thanh toán.
        </p>
    </div>
</section>

{{-- ===== MAIN GRID ===== --}}
<section class="section">
    <div class="container" style="max-width: 1000px;">
        
        {{-- Checkout steps --}}
        <div style="display:flex; align-items:center; justify-content:center; gap:16px; margin-bottom:40px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:28px; height:28px; background:var(--primary); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:700;">1</div>
                <span style="font-weight:700; color:var(--text-primary); font-size:0.85rem;">Giỏ Hàng</span>
            </div>
            <div style="width:60px; height:1px; background:var(--border);"></div>
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:28px; height:28px; background:var(--bg-card); border:1px solid var(--border); color:var(--text-muted); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:700;">2</div>
                <span style="font-weight:600; color:var(--text-muted); font-size:0.85rem;">Thanh Toán</span>
            </div>
            <div style="width:60px; height:1px; background:var(--border);"></div>
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:28px; height:28px; background:var(--bg-card); border:1px solid var(--border); color:var(--text-muted); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:700;">3</div>
                <span style="font-weight:600; color:var(--text-muted); font-size:0.85rem;">Hoàn Tất</span>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 340px; gap:32px; align-items:start;" class="cart-page-layout">
            
            {{-- ===== LEFT: ITEMS CONTAINER ===== --}}
            <div>
                {{-- Coupon Code Card --}}
                <div class="card" style="padding:16px; margin-bottom:20px; display:flex; flex-direction:row; gap:12px; align-items:center; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:8px; flex:1; min-width:200px;">
                        <span style="font-size:1.2rem; color:var(--primary-light);"><i class="bi bi-tag"></i></span>
                        <input type="text" id="couponInput" placeholder="Nhập mã giảm giá (VPNVN10, VIP20...)" style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 14px; border-radius:var(--radius); font-size:0.85rem; width:100%; outline:none;">
                    </div>
                    <button class="btn btn-primary" style="padding:10px 20px;" onclick="applyCoupon()">Áp Dụng</button>
                </div>

                {{-- Items Wrapper --}}
                <div id="cartItemsContainer">
                    <div style="text-align:center; padding:48px 0;">
                        <div class="spinner-border text-primary" style="width:2rem; height:2rem;"></div>
                        <p style="color:var(--text-muted); margin-top:16px;">Đang tải giỏ hàng của bạn...</p>
                    </div>
                </div>

                {{-- Back button --}}
                <div style="margin-top:20px;">
                    <a href="{{ route('products') }}" style="color:var(--primary-light); font-weight:700; text-decoration:none; font-size:0.9rem;">
                        <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            {{-- ===== RIGHT: SUMMARY BOX ===== --}}
            <div>
                <div class="card" style="padding:24px;">
                    <h5 style="font-size:1rem; font-weight:800; color:var(--text-primary); margin-bottom:20px;">
                        <i class="bi bi-receipt me-2 text-primary"></i> Tóm Tắt Đơn Hàng
                    </h5>

                    <div style="display:flex; flex-direction:column; gap:12px;">
                        <div style="display:flex; justify-content:space-between; font-size:0.875rem;">
                            <span style="color:var(--text-secondary);">Tạm tính</span>
                            <span id="cart-subtotal" style="font-weight:700; color:var(--text-primary);">0đ</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.875rem;">
                            <span style="color:var(--text-secondary);">Mã giảm giá</span>
                            <span id="cart-coupon" style="font-weight:700; color:var(--success);">Chưa áp dụng</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.875rem;">
                            <span style="color:var(--text-secondary);">Giảm giá tự động</span>
                            <span id="cart-discount" style="font-weight:700; color:var(--success);">0đ</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.875rem; border-bottom:1px solid var(--border); padding-bottom:16px;">
                            <span style="color:var(--text-secondary);">Phí giao hàng</span>
                            <span style="font-weight:700; color:var(--success);">Miễn phí</span>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:baseline; padding-top:8px; margin-bottom:20px;">
                            <strong style="font-size:0.95rem; color:var(--text-primary);">Tổng thanh toán</strong>
                            <strong id="cart-total" style="font-size:1.6rem; color:var(--primary-light); font-family:var(--font-mono);">0đ</strong>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-full btn-lg" style="padding:14px; font-weight:700;">
                        <i class="bi bi-credit-card me-2"></i> Tiến Hành Thanh Toán
                    </a>

                    {{-- Guarantees --}}
                    <div style="margin-top:24px; padding:16px; background:rgba(16, 185, 129, 0.05); border:1px solid rgba(16, 185, 129, 0.15); border-radius:var(--radius-lg); text-align:center;">
                        <div style="font-size:1.2rem; color:var(--success); margin-bottom:6px;"><i class="bi bi-shield-fill-check"></i></div>
                        <strong style="font-size:0.85rem; color:var(--text-primary); display:block; margin-bottom:2px;">Thanh Toán 100% An Toàn</strong>
                        <span style="font-size:0.75rem; color:var(--text-muted);">SSL 256-bit · Mã hóa bảo mật thông tin</span>
                    </div>

                    <div style="margin-top:20px; text-align:center;">
                        <span style="font-size:0.75rem; color:var(--text-muted); display:block; margin-bottom:10px;">Chấp Nhận Thanh Toán</span>
                        <div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">
                            <div style="padding:4px 8px; border:1px solid var(--border); border-radius:4px; font-size:0.7rem; background:var(--bg-elevated); color:var(--text-secondary);">MB Bank</div>
                            <div style="padding:4px 8px; border:1px solid var(--border); border-radius:4px; font-size:0.7rem; background:var(--bg-elevated); color:var(--text-secondary);">Momo</div>
                            <div style="padding:4px 8px; border:1px solid var(--border); border-radius:4px; font-size:0.7rem; background:var(--bg-elevated); color:var(--text-secondary);">ATM</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
window.stockMap = @json($stockMap);
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
