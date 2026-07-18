@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công - ' . ($settings['store_name'] ?? 'VPNStore'))

@section('content')
<section class="section">
    <div class="container" style="max-width:680px;">
        <div style="text-align:center; margin-bottom:40px;">
            <div style="width:80px; height:80px; background:rgba(16,185,129,0.15); border:2px solid var(--success); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2.5rem; margin:0 auto 20px; color:var(--success);">
                <i class="bi bi-check-lg"></i>
            </div>
            <h1 style="font-size:2rem; font-weight:800; color:var(--success); margin-bottom:8px;">Đặt Hàng Thành Công! <i class="bi bi-emoji-smile"></i></h1>
            <p style="color:var(--text-secondary);">Cảm ơn bạn đã tin tưởng mua sắm tại {{ $settings['store_name'] ?? 'VPNStore' }}.</p>
        </div>

        {{-- Order ID Badge --}}
        <div class="card text-center" style="padding:24px; margin-bottom:24px; border-color:rgba(124,58,237,0.3);">
            <span style="font-size:0.75rem; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; display:block; margin-bottom:6px;">Mã Đơn Hàng Của Bạn</span>
            <div style="font-size:1.6rem; font-weight:900; color:var(--primary-light); font-family:var(--font-mono); letter-spacing:1px; margin-bottom:12px;">
                {{ request('order', 'VPN12345678') }}
            </div>
            <button class="btn btn-outline btn-sm" onclick="navigator.clipboard.writeText('{{ request('order', 'VPN12345678') }}').then(()=>showToast('Đã sao chép!','success'))" style="margin:0 auto; padding:6px 16px;">
                <i class="bi bi-copy me-2"></i> Sao Chép Mã Đơn
            </button>
        </div>

        {{-- Instructions List --}}
        <div class="card" style="padding:24px; margin-bottom:24px;">
            <h3 style="font-size:1rem; font-weight:800; color:var(--text-primary); margin-bottom:20px;">Các Bước Tiếp Theo:</h3>
            
            <div style="display:flex; flex-direction:column; gap:20px;">
                <div style="display:flex; gap:16px;">
                    <div style="width:32px; height:32px; background:var(--primary); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:700; flex-shrink:0;">1</div>
                    <div>
                        <strong style="font-size:0.95rem; color:var(--text-primary); display:block; margin-bottom:4px;">Chuyển Khoản & Xác Nhận</strong>
                        <span style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6; display:block;">
                            Nếu chưa chuyển khoản, vui lòng thực hiện quét QR thanh toán đúng số tiền và nội dung. Đơn hàng của bạn sẽ được kích hoạt tự động sau khi nhận được thanh toán.
                        </span>
                    </div>
                </div>

                <div style="display:flex; gap:16px;">
                    <div style="width:32px; height:32px; background:var(--success); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:700; flex-shrink:0;">2</div>
                    <div>
                        <strong style="font-size:0.95rem; color:var(--text-primary); display:block; margin-bottom:4px;">Kiểm Tra Email Nhận Key</strong>
                        <span style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6; display:block;">
                            Thông tin tài khoản Premium hoặc License Key kích hoạt sẽ được gửi tự động vào Email của bạn trong vòng 1-30 phút sau khi xác nhận thanh toán thành công.
                        </span>
                    </div>
                </div>

                <div style="display:flex; gap:16px;">
                    <div style="width:32px; height:32px; background:var(--warning); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:700; flex-shrink:0;">3</div>
                    <div>
                        <strong style="font-size:0.95rem; color:var(--text-primary); display:block; margin-bottom:4px;">Hỗ Trợ Kỹ Thuật 24/7</strong>
                        <span style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6; display:block;">
                            Nếu gặp bất kỳ vấn đề gì về kích hoạt hoặc sử dụng, vui lòng liên hệ Telegram / Zalo hỗ trợ của chúng tôi để được xử lý nhanh nhất.
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
            <a href="{{ route('order.check') }}" class="btn btn-primary">
                <i class="bi bi-search me-2"></i> Tra Cứu Đơn Hàng
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline">
                <i class="bi bi-house me-2"></i> Về Trang Chủ
            </a>
            @if(!empty($settings['telegram_support']))
            <a href="{{ $settings['telegram_url'] ?? 'https://t.me/' . ltrim($settings['telegram_support'],'@') }}" target="_blank" class="btn btn-outline" style="color:var(--primary-light); border-color:rgba(124,58,237,0.3);">
                <i class="bi bi-telegram me-2"></i> Hỗ Trợ Telegram
            </a>
            @endif
        </div>

    </div>
</section>
@endsection
