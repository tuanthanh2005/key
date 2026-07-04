@extends('layouts.app')

@section('title', 'Về Chúng Tôi - VPNStore')

@section('content')

<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
                <li class="breadcrumb-item active">Giới Thiệu</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Hero -->
<section style="background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 50%,#ede9fe 100%);padding:80px 0">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-label mb-3 d-inline-block">🏆 Uy Tín Số 1</span>
                <h1 class="section-title mb-3">VPNStore — Chuyên Gia VPN Tại Việt Nam</h1>
                <p class="text-muted mb-4" style="font-size:15px;line-height:1.8">
                    Thành lập từ năm 2020, VPNStore là đơn vị chuyên cung cấp các gói VPN chính hãng
                    từ các thương hiệu hàng đầu thế giới. Chúng tôi cam kết 100% sản phẩm bản quyền,
                    giá tốt nhất và dịch vụ hỗ trợ tận tâm 24/7.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('products') }}" class="btn-hero-primary">
                        <i class="bi bi-shield-check"></i>Xem Sản Phẩm
                    </a>
                    <a href="{{ route('contact') }}" class="btn-hero-secondary">
                        <i class="bi bi-headset"></i>Liên Hệ
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    @php
                    $stats = [
                        ['num'=>'50K+','label'=>'Khách Hàng Tin Dùng','icon'=>'bi-people-fill','color'=>'#2563eb'],
                        ['num'=>'8+','label'=>'Thương Hiệu VPN','icon'=>'bi-grid-3x3-gap-fill','color'=>'#7c3aed'],
                        ['num'=>'5 Năm+','label'=>'Kinh Nghiệm','icon'=>'bi-calendar-check-fill','color'=>'#16a34a'],
                        ['num'=>'99.9%','label'=>'Tỷ Lệ Hài Lòng','icon'=>'bi-star-fill','color'=>'#d97706'],
                    ];
                    @endphp
                    @foreach($stats as $stat)
                    <div class="col-6">
                        <div class="about-stat-card text-center">
                            <div style="font-size:36px;color:{{ $stat['color'] }}" class="mb-2">
                                <i class="bi {{ $stat['icon'] }}"></i>
                            </div>
                            <div class="about-stat-num" style="background:linear-gradient(135deg,{{ $stat['color'] }},{{ $stat['color'] }}88);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{{ $stat['num'] }}</div>
                            <div class="text-muted mt-1" style="font-size:13px;font-weight:600">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label mb-3 d-inline-block">🎯 Sứ Mệnh</span>
            <h2 class="section-title">Cam Kết Của Chúng Tôi</h2>
        </div>
        <div class="row g-4">
            @php
            $values = [
                ['icon'=>'bi-patch-check-fill','color'=>'#2563eb','bg'=>'#eff6ff','title'=>'100% Chính Hãng','desc'=>'Tất cả sản phẩm VPN được mua trực tiếp từ nhà phát triển, đảm bảo bản quyền hợp lệ, không crack, không giả mạo. Bạn hoàn toàn yên tâm khi mua tại VPNStore.'],
                ['icon'=>'bi-shield-fill-check','color'=>'#16a34a','bg'=>'#dcfce7','title'=>'Bảo Mật & Riêng Tư','desc'=>'Chúng tôi không lưu trữ bất kỳ thông tin nhạy cảm nào của khách hàng. Mọi giao dịch được mã hóa SSL, thông tin thanh toán an toàn tuyệt đối.'],
                ['icon'=>'bi-currency-dollar','color'=>'#d97706','bg'=>'#fef3c7','title'=>'Giá Tốt Nhất','desc'=>'VPNStore cam kết mang đến giá VPN tốt nhất thị trường. Nếu bạn tìm thấy giá rẻ hơn cùng sản phẩm, chúng tôi sẽ hoàn tiền phần chênh lệch.'],
                ['icon'=>'bi-headset','color'=>'#7c3aed','bg'=>'#f5f3ff','title'=>'Hỗ Trợ Tận Tâm','desc'=>'Đội ngũ hỗ trợ 24/7 luôn sẵn sàng giúp đỡ bạn qua Telegram, Zalo, Email và Hotline. Không để khách hàng chờ đợi quá 30 phút.'],
                ['icon'=>'bi-arrow-repeat','color'=>'#0ea5e9','bg'=>'#f0f9ff','title'=>'Bảo Hành Rõ Ràng','desc'=>'Bảo hành 30 ngày cho tất cả sản phẩm. Nếu key lỗi do lỗi từ phía shop, đổi key mới ngay lập tức hoặc hoàn tiền 100% trong 24 giờ.'],
                ['icon'=>'bi-lightning-fill','color'=>'#dc2626','bg'=>'#fef2f2','title'=>'Xử Lý Nhanh Chóng','desc'=>'Key VPN được gửi tự động qua email trong 5–15 phút sau thanh toán. Hệ thống xử lý 24/7, không cần chờ đợi ngay cả ban đêm hay ngày lễ.'],
            ];
            @endphp
            @foreach($values as $val)
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon" style="background:{{ $val['bg'] }};color:{{ $val['color'] }}">
                        <i class="bi {{ $val['icon'] }}"></i>
                    </div>
                    <div class="feature-title">{{ $val['title'] }}</div>
                    <div class="feature-desc">{{ $val['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Brands We Carry -->
<section class="section-sm" style="background:var(--gray-50)">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Thương Hiệu Chúng Tôi Phân Phối</h2>
            <p class="section-subtitle mx-auto">Các thương hiệu VPN hàng đầu thế giới, chính hãng 100%</p>
        </div>
        <div class="row g-3 justify-content-center">
            @php
            $brands = [
                ['name'=>'NordVPN','color'=>'#4687FF','year'=>'2012','country'=>'Panama','slug'=>'nordvpn'],
                ['name'=>'ExpressVPN','color'=>'#DA3940','year'=>'2009','country'=>'BVI','slug'=>'expressvpn'],
                ['name'=>'Surfshark','color'=>'#10B981','year'=>'2018','country'=>'Netherlands','slug'=>'surfshark'],
                ['name'=>'HMA VPN','color'=>'#F59E0B','year'=>'2005','country'=>'UK','slug'=>'hma'],
                ['name'=>'CyberGhost','color'=>'#8B5CF6','year'=>'2011','country'=>'Romania','slug'=>'cyberghost'],
                ['name'=>'PureVPN','color'=>'#EF4444','year'=>'2007','country'=>'BVI','slug'=>'purevpn'],
                ['name'=>'IPVanish','color'=>'#0EA5E9','year'=>'2012','country'=>'USA','slug'=>'ipvanish'],
                ['name'=>'ProtonVPN','color'=>'#6D28D9','year'=>'2017','country'=>'Switzerland','slug'=>'protonvpn'],
            ];
            @endphp
            @foreach($brands as $brand)
            <div class="col-6 col-md-3 col-lg-3">
                <a href="{{ route('product.detail', $brand['slug']) }}" class="d-block text-center p-4 bg-white border rounded-3 transition" style="border-color:var(--gray-200)!important;transition:all .25s ease;text-decoration:none"
                   onmouseover="this.style.borderColor='{{ $brand['color'] }}';this.style.boxShadow='0 4px 16px {{ $brand['color'] }}25'"
                   onmouseout="this.style.borderColor='var(--gray-200)';this.style.boxShadow=''">
                    <div style="width:52px;height:52px;background:linear-gradient(135deg,{{ $brand['color'] }},{{ $brand['color'] }}99);border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:24px;color:#fff">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="fw-800" style="font-size:14px;color:{{ $brand['color'] }}">{{ $brand['name'] }}</div>
                    <div class="text-muted mt-1" style="font-size:12px">Thành lập {{ $brand['year'] }}</div>
                    <div class="text-muted" style="font-size:11.5px">{{ $brand['country'] }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
