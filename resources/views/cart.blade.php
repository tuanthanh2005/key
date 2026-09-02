@extends('layouts.app')

@section('title', 'Giỏ Hàng - ' . ($settings['store_name'] ?? 'VPNStore'))
@section('meta_robots', 'noindex, nofollow')

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
        <div class="cart-steps-container">
            <div class="cart-step active">
                <div class="step-num">1</div>
                <span class="step-label">Giỏ Hàng</span>
            </div>
            <div class="step-line"></div>
            <div class="cart-step">
                <div class="step-num">2</div>
                <span class="step-label">Thanh Toán</span>
            </div>
            <div class="step-line"></div>
            <div class="cart-step">
                <div class="step-num">3</div>
                <span class="step-label">Hoàn Tất</span>
            </div>
        </div>

        <div class="cart-page-layout">
            
            {{-- ===== LEFT: ITEMS CONTAINER ===== --}}
            <div>
                {{-- Items Wrapper --}}
                <div id="cartItemsContainer">
                    <div style="text-align:center; padding:48px 0;">
                        <div class="spinner-border text-primary" style="width:2rem; height:2rem;"></div>
                        <p style="color:var(--text-muted); margin-top:16px;">Đang tải giỏ hàng của bạn...</p>
                    </div>
                </div>

                {{-- Back button --}}
                <div style="margin-top:20px; margin-bottom:28px;">
                    <a href="{{ route('products') }}" style="color:var(--primary-light); font-weight:700; text-decoration:none; font-size:0.9rem; display:inline-flex; align-items:center; gap:6px;">
                        <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>

                {{-- Service Guarantees Card (Desktop Only) --}}
                <div class="card cart-desktop-extra" style="padding:20px; margin-bottom:24px; background:linear-gradient(135deg, rgba(124, 58, 237, 0.05) 0%, rgba(6, 182, 212, 0.05) 100%); border:1px solid rgba(124, 58, 237, 0.15);">
                    <h6 style="font-weight:800; font-size:0.92rem; color:var(--text-primary); margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                        <i class="bi bi-patch-check-fill text-primary"></i> Cam Kết & Dịch Vụ Tại VPNStore
                    </h6>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px;">
                        <div style="display:flex; align-items:flex-start; gap:10px;">
                            <div style="width:32px; height:32px; border-radius:50%; background:rgba(16, 185, 129, 0.12); color:var(--success); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.95rem;">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div>
                                <strong style="font-size:0.82rem; display:block; color:var(--text-primary);">Giao Key Tự Động 30s</strong>
                                <span style="font-size:0.73rem; color:var(--text-muted);">Gửi trực tiếp qua Email ngay sau khi thanh toán</span>
                            </div>
                        </div>

                        <div style="display:flex; align-items:flex-start; gap:10px;">
                            <div style="width:32px; height:32px; border-radius:50%; background:rgba(124, 58, 237, 0.12); color:var(--primary-light); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.95rem;">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <strong style="font-size:0.82rem; display:block; color:var(--text-primary);">Bảo Hành 1 Đổi 1</strong>
                                <span style="font-size:0.73rem; color:var(--text-muted);">Hỗ trợ đổi mới trọn thời gian sử dụng</span>
                            </div>
                        </div>

                        <div style="display:flex; align-items:flex-start; gap:10px;">
                            <div style="width:32px; height:32px; border-radius:50%; background:rgba(6, 182, 212, 0.12); color:var(--info); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.95rem;">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div>
                                <strong style="font-size:0.82rem; display:block; color:var(--text-primary);">Hỗ Trợ Kỹ Thuật 24/7</strong>
                                <span style="font-size:0.73rem; color:var(--text-muted);">Giải đáp thắc mắc qua Zalo & Telegram</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recommended Products Section (Desktop Only) --}}
                @if(isset($recommendedProducts) && count($recommendedProducts) > 0)
                    <div class="cart-desktop-extra" style="margin-top:28px;">
                        <h6 style="font-weight:800; font-size:0.95rem; color:var(--text-primary); margin-bottom:14px; display:flex; align-items:center; gap:8px;">
                            <i class="bi bi-stars text-warning"></i> Có Thể Bạn Cũng Thích
                        </h6>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            @foreach($recommendedProducts as $recProd)
                                @include('partials.product-card', ['product' => $recProd])
                            @endforeach
                        </div>
                    </div>
                @endif
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

                        <div style="display:flex; justify-content:space-between; align-items:baseline; padding-top:8px; margin-bottom:16px;">
                            <strong style="font-size:0.95rem; color:var(--text-primary);">Tổng thanh toán</strong>
                            <strong id="cart-total" style="font-size:1.6rem; color:var(--primary-light); font-family:var(--font-mono);">0đ</strong>
                        </div>
                    </div>

                    {{-- Coupon Code Card --}}
                    <div class="coupon-card" style="margin-bottom:20px; padding:10px; background:var(--bg-elevated); border:1px solid var(--border); border-radius:var(--radius-lg);">
                        <div style="display:flex; align-items:center; gap:8px; flex:1;">
                            <span style="font-size:1.1rem; color:var(--primary-light); flex-shrink:0;"><i class="bi bi-tag"></i></span>
                            <input type="text" id="couponInput" placeholder="Nhập mã giảm giá..." style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:8px 12px; border-radius:var(--radius); font-size:0.82rem; width:100%; outline:none;">
                        </div>
                        <button class="btn btn-primary btn-sm" style="padding:8px 16px; flex-shrink:0; font-size:0.8rem;" onclick="applyCoupon()">Áp Dụng</button>
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
@php
    $catImageMap = \App\Models\Category::all()->mapWithKeys(function($cat) {
        return [$cat->slug => $cat->image_url];
    })->filter()->all();
@endphp
window.categoryImages = @json($catImageMap);

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
