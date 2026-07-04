@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công - VPNStore')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Success Card -->
            <div class="text-center bg-white rounded-4 p-5 shadow-sm" style="border:1.5px solid var(--gray-200)">
                <div class="mb-4" style="font-size:80px;color:#22c55e;animation:bounceIn .6s ease">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h1 class="font-poppins fw-800 mb-2" style="font-size:28px;color:var(--gray-900)">
                    Đặt Hàng Thành Công! 🎉
                </h1>
                <p class="text-muted mb-4" style="font-size:15px;line-height:1.7">
                    Cảm ơn bạn đã tin tưởng VPNStore.<br>
                    Đơn hàng của bạn đã được xác nhận và đang được xử lý.
                </p>

                <!-- Order ID -->
                <div class="p-3 rounded-3 mb-4 d-inline-flex align-items-center gap-3" style="background:var(--primary-light);border:1.5px solid var(--primary-100)">
                    <div>
                        <div style="font-size:11.5px;color:var(--gray-500);font-weight:600">MÃ ĐƠN HÀNG</div>
                        <div class="fw-800 text-primary font-poppins" style="font-size:20px">{{ request('order', 'VPN12345678') }}</div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="navigator.clipboard.writeText('{{ request('order', 'VPN12345678') }}').then(()=>showToast('Đã sao chép!','success'))">
                        <i class="bi bi-copy me-1"></i>Sao Chép
                    </button>
                </div>

                <!-- Next Steps -->
                <div class="text-start p-4 rounded-3 mb-4" style="background:var(--gray-50);border:1px solid var(--gray-200)">
                    <h6 class="fw-700 mb-3" style="font-size:14px;color:var(--gray-800)">Các Bước Tiếp Theo:</h6>
                    <div class="d-flex gap-3 mb-3">
                        <div style="width:28px;height:28px;background:var(--primary);border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0">1</div>
                        <div>
                            <div class="fw-600" style="font-size:13.5px">Kiểm tra Email</div>
                            <div class="text-muted" style="font-size:12.5px">Key VPN sẽ được gửi về email trong 5–15 phút sau khi xác nhận thanh toán</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div style="width:28px;height:28px;background:var(--success);border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0">2</div>
                        <div>
                            <div class="fw-600" style="font-size:13.5px">Xác Nhận Thanh Toán</div>
                            <div class="text-muted" style="font-size:12.5px">Gửi ảnh chụp màn hình xác nhận CK về Telegram @specademy hoặc Zalo 0708910952 / 0569012134</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div style="width:28px;height:28px;background:var(--warning);border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0">3</div>
                        <div>
                            <div class="fw-600" style="font-size:13.5px">Nhận Key & Kích Hoạt</div>
                            <div class="text-muted" style="font-size:12.5px">Theo hướng dẫn trong email để kích hoạt VPN. Hỗ trợ miễn phí nếu cần!</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('order.check') }}" class="btn btn-primary rounded-pill px-4 fw-600">
                        <i class="bi bi-search me-2"></i>Tra Cứu Đơn Hàng
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-600">
                        <i class="bi bi-house me-2"></i>Về Trang Chủ
                    </a>
                    <a href="https://t.me/specademy" target="_blank" class="btn btn-outline-primary rounded-pill px-4 fw-600">
                        <i class="bi bi-telegram me-2"></i>Telegram Hỗ Trợ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_css')
<style>
@keyframes bounceIn {
    0% { transform: scale(0); opacity:0; }
    60% { transform: scale(1.1); }
    100% { transform: scale(1); opacity:1; }
}
</style>
@endsection
