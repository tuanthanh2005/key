@extends('layouts.app')

@section('title', 'VPNStore - Cửa Hàng VPN Chính Hãng Số 1 Việt Nam')
@section('meta_description', 'Mua VPN chính hãng: NordVPN, ExpressVPN, Surfshark, HMA, CyberGhost với giá tốt nhất. Bảo hành 30 ngày, hỗ trợ 24/7.')
@section('meta_keywords', 'vpn gia re, mua vpn, tai khoan nordvpn, tai khoan expressvpn, mua surfshark, key hma vpn, cyberghost gia re, gia vpn')

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "Organization",
      "@@id": "{{ route('home') }}#organization",
      "name": "VPNStore",
      "url": "{{ route('home') }}",
      "logo": "{{ !empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico') }}",
      "sameAs": [
        "https://t.me/specademy"
      ],
      "contactPoint": [
        {
          "@@type": "ContactPoint",
          "telephone": "+84708910952",
          "contactType": "customer support",
          "areaServed": "VN",
          "availableLanguage": "Vietnamese"
        }
      ]
    },
    {
      "@@type": "WebSite",
      "@@id": "{{ route('home') }}#website",
      "url": "{{ route('home') }}",
      "name": "VPNStore",
      "description": "Mua VPN chính hãng: NordVPN, ExpressVPN, Surfshark, HMA, CyberGhost với giá tốt nhất. Bảo hành 30 ngày, hỗ trợ 24/7.",
      "publisher": {
        "@@id": "{{ route('home') }}#organization"
      },
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ route('search') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
  ]
}
</script>
@endsection

@section('content')

<!-- CHÍNH SÁCH BẢO HÀNH BAR -->
<div class="warranty-bar" style="background:linear-gradient(90deg,#1e3a8a 0%,#2563eb 50%,#1d4ed8 100%);color:#fff;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.1)">
    <div class="container">
        <div class="row g-2 align-items-center justify-content-center">
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <div style="width:34px;height:34px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-arrow-repeat" style="font-size:15px;color:#4ade80"></i>
                    </div>
                    <div>
                        <div style="font-size:12.5px;font-weight:800;line-height:1.2">Bảo Hành 30 Ngày</div>
                        <div style="font-size:10.5px;color:rgba(255,255,255,.65);font-weight:400">Hoàn tiền 100% nếu lỗi</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <div style="width:34px;height:34px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-patch-check-fill" style="font-size:15px;color:#fbbf24"></i>
                    </div>
                    <div>
                        <div style="font-size:12.5px;font-weight:800;line-height:1.2">100% Chính Hãng</div>
                        <div style="font-size:10.5px;color:rgba(255,255,255,.65);font-weight:400">Key bản quyền, không crack</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <div style="width:34px;height:34px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-headset" style="font-size:15px;color:#60a5fa"></i>
                    </div>
                    <div>
                        <div style="font-size:12.5px;font-weight:800;line-height:1.2">Hỗ Trợ 24/7</div>
                        <div style="font-size:10.5px;color:rgba(255,255,255,.65);font-weight:400">Telegram · Zalo · Hotline</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <div style="width:34px;height:34px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-lightning-charge-fill" style="font-size:15px;color:#f472b6"></i>
                    </div>
                    <div>
                        <div style="font-size:12.5px;font-weight:800;line-height:1.2">Giao Key Tức Thì</div>
                        <div style="font-size:10.5px;color:rgba(255,255,255,.65);font-weight:400">Nhận key trong 5–15 phút</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left Content -->
            <div class="col-lg-6">
                <div class="hero-badge">
                    <span class="live-dot"></span>
                    Hơn 50,000+ khách hàng tin dùng
                </div>
                <h1 class="hero-title mb-3">
                    Bảo Vệ Quyền Riêng Tư<br>
                    Của Bạn Với <span class="highlight">VPN Chính Hãng</span>
                </h1>
                <p class="hero-desc">
                    Chuyên cung cấp các gói VPN uy tín nhất thế giới với giá tốt nhất.
                    NordVPN, ExpressVPN, Surfshark, HMA và nhiều thương hiệu hàng đầu khác.
                    Bảo hành 30 ngày, hỗ trợ 24/7.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('products') }}" class="btn-hero-primary">
                        <i class="bi bi-shield-check"></i>
                        Mua VPN Ngay
                    </a>
                    <a href="{{ route('pricing') }}" class="btn-hero-secondary">
                        <i class="bi bi-tags"></i>
                        Xem Bảng Giá
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-num">50K+</div>
                        <div class="hero-stat-label">Khách Hàng</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">8+</div>
                        <div class="hero-stat-label">Thương Hiệu</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">99.9%</div>
                        <div class="hero-stat-label">Uptime</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">24/7</div>
                        <div class="hero-stat-label">Hỗ Trợ</div>
                    </div>
                </div>
            </div>

            <!-- Right Visual -->
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="hero-shield-wrap">
                        <i class="bi bi-shield-lock-fill shield-icon"></i>
                    </div>
                    <!-- Floating brand badges -->
                    <div class="hero-brand-float" style="top:15px;right:-20px;border-color:rgba(70,135,255,.2)">
                        <span style="width:10px;height:10px;background:#4687FF;border-radius:50%;display:inline-block"></span>
                        <span style="color:#4687FF;font-weight:800">NordVPN</span>
                    </div>
                    <div class="hero-brand-float" style="top:100px;right:-55px;border-color:rgba(218,57,64,.2)">
                        <span style="width:10px;height:10px;background:#DA3940;border-radius:50%;display:inline-block"></span>
                        <span style="color:#DA3940;font-weight:800">ExpressVPN</span>
                    </div>
                    <div class="hero-brand-float" style="bottom:100px;right:-40px;border-color:rgba(16,185,129,.2)">
                        <span style="width:10px;height:10px;background:#10B981;border-radius:50%;display:inline-block"></span>
                        <span style="color:#10B981;font-weight:800">Surfshark</span>
                    </div>
                    <div class="hero-brand-float" style="bottom:20px;left:-15px;border-color:rgba(245,158,11,.2)">
                        <span style="width:10px;height:10px;background:#F59E0B;border-radius:50%;display:inline-block"></span>
                        <span style="color:#F59E0B;font-weight:800">HMA VPN</span>
                    </div>
                    <div class="hero-brand-float" style="top:50px;left:-35px;border-color:rgba(139,92,246,.2)">
                        <span style="width:10px;height:10px;background:#8B5CF6;border-radius:50%;display:inline-block"></span>
                        <span style="color:#8B5CF6;font-weight:800">CyberGhost</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BRAND STRIP -->
<div class="brand-strip">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
            <a href="{{ route('products', ['brand' => 'nordvpn']) }}" class="brand-strip-item">
                <span class="brand-dot nord"></span> NordVPN
            </a>
            <a href="{{ route('products', ['brand' => 'expressvpn']) }}" class="brand-strip-item">
                <span class="brand-dot express"></span> ExpressVPN
            </a>
            <a href="{{ route('products', ['brand' => 'surfshark']) }}" class="brand-strip-item">
                <span class="brand-dot surf"></span> Surfshark
            </a>
            <a href="{{ route('products', ['brand' => 'hma']) }}" class="brand-strip-item">
                <span class="brand-dot hma"></span> HMA VPN
            </a>
            <a href="{{ route('products', ['brand' => 'cyberghost']) }}" class="brand-strip-item">
                <span class="brand-dot cyber"></span> CyberGhost
            </a>
            <a href="{{ route('products', ['brand' => 'purevpn']) }}" class="brand-strip-item">
                <span class="brand-dot pure"></span> PureVPN
            </a>
            <a href="{{ route('products', ['brand' => 'ipvanish']) }}" class="brand-strip-item">
                <span class="brand-dot ipv"></span> IPVanish
            </a>
            <a href="{{ route('products', ['brand' => 'protonvpn']) }}" class="brand-strip-item">
                <span class="brand-dot proton"></span> ProtonVPN
            </a>
        </div>
    </div>
</div>

<!-- WHY VPN SECTION -->
<section class="section-sm" style="background:#fff;border-bottom:1px solid var(--gray-100)">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="feature-card h-100">
                    <div class="feature-icon bg-primary-light" style="color:var(--primary)"><i class="bi bi-shield-check-fill"></i></div>
                    <div class="feature-title">Bảo Mật Tuyệt Đối</div>
                    <div class="feature-desc">Mã hóa AES-256 chuẩn quân đội, không lưu log</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-card h-100">
                    <div class="feature-icon" style="background:#ecfeff;color:#06b6d4"><i class="bi bi-lightning-fill"></i></div>
                    <div class="feature-title">Tốc Độ Nhanh</div>
                    <div class="feature-desc">Băng thông không giới hạn, tốc độ cao nhất</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-card h-100">
                    <div class="feature-icon" style="background:#dcfce7;color:#16a34a"><i class="bi bi-patch-check-fill"></i></div>
                    <div class="feature-title">Hàng Chính Hãng</div>
                    <div class="feature-desc">100% key bản quyền, không cracked, không fake</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-card h-100">
                    <div class="feature-icon" style="background:#fef3c7;color:#d97706"><i class="bi bi-headset"></i></div>
                    <div class="feature-title">Hỗ Trợ 24/7</div>
                    <div class="feature-desc">Đội ngũ chuyên nghiệp, phản hồi nhanh chóng</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOT PRODUCTS SECTION -->
<section class="section" style="background:var(--gray-50)">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">🔥 Bán Chạy</span>
            <h2 class="section-title mt-2">Sản Phẩm Nổi Bật</h2>
            <p class="section-subtitle mx-auto">Những gói VPN được khách hàng tin dùng và đánh giá cao nhất</p>
        </div>

        <div class="row g-4">
            @php
            $hotProducts = array_filter($allProducts ?? [], function($p) {
                return in_array($p['plan'], ['1year', '2year']);
            });
            if (empty($hotProducts)) {
                $hotProducts = array_slice($allProducts ?? [], 0, 6);
            } else {
                $hotProducts = array_slice($hotProducts, 0, 6);
            }
            @endphp

            @foreach($hotProducts as $prod)
            <div class="col-lg-4 col-md-6 product-card-wrap" data-name="{{ strtolower($prod['name']) }}" data-brand="{{ strtolower($prod['brand']) }}" data-price="{{ $prod['price'] }}" data-rating="{{ $prod['rating'] }}">
                <div class="product-card">
                    <!-- Badges -->
                    <div class="product-card-badge">
                        @if($prod['price'] < ($prod['old_price'] ?? 0))<span class="badge-sale">Sale</span>@endif
                        @if($prod['plan'] === '1year' || $prod['plan'] === '2year')<span class="badge-hot"><i class="bi bi-fire"></i> Hot</span>@endif
                    </div>

                    <!-- Image -->
                    <a href="{{ route('product.detail', $prod['slug']) }}" class="product-card-img" style="text-decoration: none; display: flex; justify-content: center; align-items: center;">
                        @if(!empty($prod['image_path']))
                            <img src="{{ asset($prod['image_path']) }}" alt="{{ $prod['name'] }}" style="max-height: 80px; max-width: 80%; object-fit: contain;">
                        @else
                            <div class="product-brand-logo" style="background:linear-gradient(135deg,{{ $prod['color'] }},{{ $prod['color'] }}aa)">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                        @endif
                    </a>

                    <!-- Body -->
                    <div class="product-card-body">
                        <div class="product-brand-tag" style="color:{{ $prod['color'] }}">
                            <span style="width:8px;height:8px;background:{{ $prod['color'] }};border-radius:50%;display:inline-block"></span>
                            {{ $prod['brand'] }}
                        </div>
                        <div class="product-title">
                            <a href="{{ route('product.detail', $prod['slug']) }}" style="color: inherit; text-decoration: none;">
                                {{ $prod['name'] }}
                            </a>
                        </div>
                        <ul class="product-features">
                            @foreach($prod['features'] as $feat)
                            <li><i class="bi bi-check-circle-fill"></i>{{ $feat }}</li>
                            @endforeach
                        </ul>
                        <div class="product-rating">
                            <div class="rating-stars">
                                @for($i=1;$i<=5;$i++)
                                    <i class="bi {{ $i <= floor($prod['rating']) ? 'bi-star-fill' : ($i - $prod['rating'] < 1 ? 'bi-star-half' : 'bi-star') }}"></i>
                                @endfor
                            </div>
                            <span class="fw-600 ms-1" style="font-size:12.5px;color:var(--gray-700)">{{ $prod['rating'] }}</span>
                            <span class="rating-count ms-1">({{ number_format($prod['reviews']) }} đánh giá)</span>
                            <span class="ms-2 text-muted" style="font-size:11.5px">• Đã bán {{ \App\Models\Setting::get('sales_' . strtolower($prod['slug']), '100+') }}</span>
                        </div>
                        <div class="product-price-wrap">
                            <div class="product-price-old">{{ number_format($prod['old_price']) }}đ</div>
                            <div class="d-flex align-items-baseline gap-1">
                                <div class="product-price">{{ number_format($prod['price']) }}đ</div>
                                <span class="product-price-unit">/{{ $prod['plan'] === '1year' ? '1 năm' : '2 năm' }}</span>
                            </div>
                            <div class="text-success small fw-600 mt-1">
                                <i class="bi bi-arrow-down-short"></i>
                                Tiết kiệm {{ number_format($prod['old_price'] - $prod['price']) }}đ
                                ({{ round(($prod['old_price']-$prod['price'])/$prod['old_price']*100) }}%)
                            </div>
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('product.detail', $prod['slug']) }}" class="btn-add-cart" style="text-decoration:none">
                                Xem Chi Tiết
                            </a>
                            @if(($prod['stock'] ?? 0) <= 0)
                            <button class="btn-wishlist" disabled style="background:#cbd5e1; border-color:#cbd5e1; color:#64748b; cursor:not-allowed;" title="Hết Hàng">
                                <i class="bi bi-x-circle"></i>
                            </button>
                            @else
                            <button class="btn-wishlist"
                                data-add-cart
                                data-id="{{ $prod['id'] }}"
                                data-name="{{ $prod['name'] }}"
                                data-brand="{{ $prod['brand'] }}"
                                data-plan="{{ $prod['plan'] }}"
                                data-price="{{ $prod['price'] }}"
                                data-color="{{ $prod['color'] }}"
                                data-slug="{{ $prod['slug'] }}"
                                title="Thêm Vào Giỏ">
                                <i class="bi bi-bag-plus"></i>
                            </button>
                            @endif
                            <button class="btn-wishlist" data-wishlist>
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('products') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                <i class="bi bi-grid me-2"></i>Xem Tất Cả Sản Phẩm
            </a>
        </div>
    </div>
</section>

<!-- PROMO BANNER 2 -->
<section style="background:linear-gradient(135deg,#1e293b 0%,#1e3a8a 50%,#312e81 100%);padding:60px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-white mb-4 mb-lg-0">
                <div class="badge text-uppercase mb-3 px-3 py-2" style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);font-size:12px;letter-spacing:1px;border-radius:20px;border:1px solid rgba(255,255,255,.2)">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Ưu Đãi Đặc Biệt
                </div>
                <h2 class="font-poppins fw-800 text-white mb-3" style="font-size:32px;line-height:1.2">
                    Mua Gói 2 Năm – Tiết Kiệm<br>
                    <span style="color:#fbbf24">Lên Đến 70%</span> So Với Lẻ
                </h2>
                <p style="color:rgba(255,255,255,.75);font-size:15px;line-height:1.7;max-width:480px">
                    Đặc biệt khi mua gói dài hạn bạn sẽ được tặng thêm 3 tháng miễn phí
                    và hỗ trợ kỹ thuật ưu tiên. Cơ hội không thể bỏ qua!
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                    <a href="{{ route('products') }}" class="btn btn-warning fw-700 px-4 py-3 rounded-pill">
                        <i class="bi bi-bag-fill me-2"></i>Khám Phá Sản Phẩm — Tiết Kiệm 70%
                    </a>
                    <a href="{{ route('pricing') }}" class="btn btn-outline-light fw-600 px-4 py-3 rounded-pill">
                        <i class="bi bi-table me-2"></i>Xem Bảng Giá
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12)">
                            <div class="fw-800 text-white" style="font-size:28px;font-family:'Poppins',sans-serif">-70%</div>
                            <div style="color:rgba(255,255,255,.65);font-size:13px">Gói 2 Năm</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12)">
                            <div class="fw-800 text-warning" style="font-size:28px;font-family:'Poppins',sans-serif">+3 Tháng</div>
                            <div style="color:rgba(255,255,255,.65);font-size:13px">Miễn Phí</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12)">
                            <div class="fw-800 text-white" style="font-size:28px;font-family:'Poppins',sans-serif">30 Ngày</div>
                            <div style="color:rgba(255,255,255,.65);font-size:13px">Hoàn Tiền</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12)">
                            <div class="fw-800 text-success" style="font-size:28px;font-family:'Poppins',sans-serif">24/7</div>
                            <div style="color:rgba(255,255,255,.65);font-size:13px">Hỗ Trợ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="section" style="background:#fff">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">💬 Đánh Giá</span>
            <h2 class="section-title mt-2">Khách Hàng Nói Gì?</h2>
            <p class="section-subtitle mx-auto">Hàng nghìn khách hàng đã tin tưởng và hài lòng với dịch vụ của chúng tôi</p>
        </div>
        <div class="row g-4">
            @php
            $reviews = [
                ['name'=>'Nguyễn Văn Minh','tag'=>'Kỹ sư phần mềm','star'=>5,'vpn'=>'NordVPN','text'=>'Mua NordVPN 1 năm giá siêu rẻ, kích hoạt ngay lập tức. Tốc độ ổn định, không bị drop. Sẽ mua lại lần sau!','init'=>'M'],
                ['name'=>'Trần Thị Lan','tag'=>'Freelancer','star'=>5,'vpn'=>'ExpressVPN','text'=>'ExpressVPN chạy siêu mượt trên MacBook và iPhone. Dùng để làm việc remote rất ổn. Shop hỗ trợ nhiệt tình 24/7.','init'=>'L'],
                ['name'=>'Lê Hoàng Nam','tag'=>'Game thủ','star'=>5,'vpn'=>'Surfshark','text'=>'Surfshark giá cực rẻ mà lại dùng được không giới hạn thiết bị. Cả gia đình dùng chung một tài khoản tiết kiệm lắm!','init'=>'N'],
                ['name'=>'Phạm Thu Hà','tag'=>'Youtuber','star'=>5,'vpn'=>'CyberGhost','text'=>'CyberGhost có máy chủ streaming chuyên dụng, xem Netflix US cực mượt. Recommend cho ae nào cần xem phim nước ngoài.','init'=>'H'],
                ['name'=>'Đỗ Quang Huy','tag'=>'Lập trình viên','star'=>5,'vpn'=>'ProtonVPN','text'=>'ProtonVPN bảo mật nhất market, trụ sở Thụy Sĩ không bị ảnh hưởng pháp lý. Giá shop tốt hơn mua trực tiếp nhiều.','init'=>'Q'],
                ['name'=>'Nguyễn Kim Liên','tag'=>'Nhân viên văn phòng','star'=>4,'vpn'=>'HMA VPN','text'=>'HMA VPN đơn giản dễ dùng, phù hợp người mới. Giá rẻ, kích hoạt nhanh. Hỗ trợ rất nhiệt tình!','init'=>'K'],
            ];
            @endphp
            @foreach($reviews as $i => $rv)
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="testimonial-avatar" style="background:linear-gradient(135deg,{{ ['#4687FF','#DA3940','#10B981','#8B5CF6','#6D28D9','#F59E0B'][$i] }},{{ ['#2563eb','#991b1b','#065f46','#4c1d95','#3b0764','#92400e'][$i] }})">
                            {{ $rv['init'] }}
                        </div>
                        <div>
                            <div class="testimonial-name">{{ $rv['name'] }}</div>
                            <div class="testimonial-tag">{{ $rv['tag'] }} · Dùng {{ $rv['vpn'] }}</div>
                        </div>
                    </div>
                    <div class="rating-stars mb-2">
                        @for($s=1;$s<=$rv['star'];$s++)<i class="bi bi-star-fill"></i>@endfor
                    </div>
                    <p class="testimonial-text">{{ $rv['text'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- SECURITY SECTION -->
<section class="section-sm" style="background:var(--gray-50);border-top:1px solid var(--gray-100)">
    <div class="container">
        <div class="row g-3 justify-content-center text-center">
            <div class="col-6 col-md-2">
                <div class="p-3">
                    <i class="bi bi-shield-fill-check text-primary" style="font-size:32px"></i>
                    <div class="fw-700 mt-2" style="font-size:13px;color:var(--gray-700)">AES-256 Bit</div>
                    <div style="font-size:11.5px;color:var(--gray-400)">Mã hóa</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-3">
                    <i class="bi bi-eye-slash-fill text-success" style="font-size:32px"></i>
                    <div class="fw-700 mt-2" style="font-size:13px;color:var(--gray-700)">No-Log Policy</div>
                    <div style="font-size:11.5px;color:var(--gray-400)">Không lưu log</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-3">
                    <i class="bi bi-globe2" style="font-size:32px;color:var(--accent)"></i>
                    <div class="fw-700 mt-2" style="font-size:13px;color:var(--gray-700)">100+ Quốc Gia</div>
                    <div style="font-size:11.5px;color:var(--gray-400)">Máy chủ toàn cầu</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-3">
                    <i class="bi bi-phone-fill text-warning" style="font-size:32px"></i>
                    <div class="fw-700 mt-2" style="font-size:13px;color:var(--gray-700)">Multi Thiết Bị</div>
                    <div style="font-size:11.5px;color:var(--gray-400)">PC, Mobile, TV</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-3">
                    <i class="bi bi-arrow-repeat" style="font-size:32px;color:var(--danger)"></i>
                    <div class="fw-700 mt-2" style="font-size:13px;color:var(--gray-700)">Hoàn Tiền 30 Ngày</div>
                    <div style="font-size:11.5px;color:var(--gray-400)">Không rủi ro</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-3">
                    <i class="bi bi-patch-check-fill" style="font-size:32px;color:var(--cyber-color)"></i>
                    <div class="fw-700 mt-2" style="font-size:13px;color:var(--gray-700)">100% Chính Hãng</div>
                    <div style="font-size:11.5px;color:var(--gray-400)">Key bản quyền</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ SECTION -->
<section class="section" style="background:#fff">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="section-label">❓ FAQ</span>
                    <h2 class="section-title mt-2">Câu Hỏi Thường Gặp</h2>
                </div>
                <div class="accordion" id="faqAccordion">
                    @php
                    $faqs = [
                        ['q'=>'VPN chính hãng khác gì VPN crack/fake?','a'=>'VPN chính hãng là key mua trực tiếp từ nhà cung cấp, đảm bảo hoạt động ổn định, đầy đủ tính năng và được hỗ trợ cập nhật. VPN crack/fake thường bị block, mất tính năng và tiềm ẩn nguy cơ bảo mật.'],
                        ['q'=>'Tôi nhận key như thế nào sau khi thanh toán?','a'=>'Sau khi thanh toán thành công, key VPN sẽ được gửi tự động qua email trong vòng 5-15 phút. Bạn cũng có thể liên hệ Telegram/Zalo của chúng tôi để nhận hỗ trợ ngay lập tức.'],
                        ['q'=>'Có hỗ trợ cài đặt và kích hoạt không?','a'=>'Có! Chúng tôi hỗ trợ hướng dẫn cài đặt và kích hoạt miễn phí qua Telegram, Zalo hoặc email. Đội ngũ hỗ trợ 24/7 sẵn sàng giúp bạn.'],
                        ['q'=>'Nếu key lỗi có được đổi không?','a'=>'Có, chúng tôi bảo hành 100% key lỗi. Nếu key không hoạt động do lỗi từ phía chúng tôi, sẽ được đổi key mới ngay lập tức hoặc hoàn tiền trong vòng 24h.'],
                        ['q'=>'Có thể dùng VPN cho mấy thiết bị?','a'=>'Tùy theo gói VPN bạn mua. NordVPN cho phép 6 thiết bị, Surfshark không giới hạn thiết bị, ExpressVPN 5 thiết bị. Thông tin chi tiết có trong mỗi sản phẩm.'],
                    ];
                    @endphp
                    @foreach($faqs as $fi => $faq)
                    <div class="accordion-item border border-gray-200 mb-2 rounded" style="border-radius:var(--radius)!important;overflow:hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $fi > 0 ? 'collapsed' : '' }} fw-600" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $fi }}" style="font-size:14.5px;border-radius:var(--radius)!important;background:#fff;color:var(--gray-800)">
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faq{{ $fi }}" class="accordion-collapse collapse {{ $fi === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted" style="font-size:14px;line-height:1.7">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted mb-3">Còn câu hỏi khác? Liên hệ với chúng tôi!</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-headset me-2"></i>Liên Hệ Hỗ Trợ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
