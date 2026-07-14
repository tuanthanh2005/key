@extends('layouts.app')

@section('title', $settings['seo_title'] ?? 'VPNStore - Cửa Hàng VPN Chính Hãng Số 1 Việt Nam')
@section('meta_description', $settings['meta_description'] ?? 'Mua VPN chính hãng: NordVPN, ExpressVPN, Surfshark, HMA, CyberGhost với giá tốt nhất. Bảo hành 30 ngày, hỗ trợ 24/7.')
@section('meta_keywords', $settings['meta_keywords'] ?? 'vpn gia re, mua vpn, tai khoan nordvpn, tai khoan expressvpn, mua surfshark, key hma vpn, cyberghost gia re, gia vpn')

@php
$faqs = [
    ['q'=>'VPN chính hãng khác gì VPN crack/fake?','a'=>'VPN chính hãng là key mua trực tiếp từ nhà cung cấp, đảm bảo hoạt động ổn định, đầy đủ tính năng và được hỗ trợ cập nhật. VPN crack/fake thường bị block, mất tính năng và tiềm ẩn nguy cơ bảo mật.'],
    ['q'=>'Tôi nhận key như thế nào sau khi thanh toán?','a'=>'Sau khi thanh toán thành công, thông tin kích hoạt tài khoản/key sẽ được gửi tự động qua email trong vòng 1-30 phút. Bạn cũng có thể liên hệ Telegram/Zalo của chúng tôi để nhận hỗ trợ ngay lập tức.'],
    ['q'=>'Có hỗ trợ cài đặt và kích hoạt không?','a'=>'Có! Chúng tôi hỗ trợ hướng dẫn cài đặt và kích hoạt miễn phí qua Telegram, Zalo hoặc email. Đội ngũ hỗ trợ 24/7 sẵn sàng giúp bạn.'],
    ['q'=>'Nếu key lỗi có được đổi không?','a'=>'Có, chúng tôi bảo hành 100% key lỗi. Nếu key không hoạt động do lỗi từ phía chúng tôi, sẽ được đổi key mới ngay lập tức hoặc hoàn tiền trong vòng 24h.'],
    ['q'=>'Có thể dùng VPN cho mấy thiết bị?','a'=>'Tùy theo gói VPN bạn mua. NordVPN cho phép 6 thiết bị, Surfshark không giới hạn thiết bị, ExpressVPN 5 thiết bị. Thông tin chi tiết có trong mỗi sản phẩm.'],
];
@endphp

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "Organization",
      "@@id": "{{ route('home') }}#organization",
      "name": "{{ $settings['store_name'] ?? 'VPNStore' }}",
      "url": "{{ route('home') }}",
      "logo": "{{ !empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico') }}",
      "sameAs": [
        "https://t.me/specademy"
      ],
      "contactPoint": [
        {
          "@@type": "ContactPoint",
          "telephone": "{{ $settings['contact_phone'] ?? '+84708910952' }}",
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
      "name": "{{ $settings['store_name'] ?? 'VPNStore' }}",
      "description": "{{ !empty($settings['meta_description']) ? $settings['meta_description'] : 'VPNStore - Chuyên cung cấp VPN chính hãng với giá tốt nhất. Bảo hành 30 ngày, hỗ trợ 24/7.' }}",
      "publisher": {
        "@@id": "{{ route('home') }}#organization"
      },
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ route('search') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        @foreach($faqs as $fi => $faq)
        {
          "@@type": "Question",
          "name": "{{ $faq['q'] }}",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "{{ $faq['a'] }}"
          }
        }{{ $fi < count($faqs) - 1 ? ',' : '' }}
        @endforeach
      ]
    }
  ]
}
</script>
@endsection

@section('content')

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
                        Xem Bảng Giá
                    </a>
                </div>
            </div>

            <!-- Right Visual -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-visual text-center">
                    <img src="{{ asset('uploads/vpn_security_light.png') }}" alt="VPN Security" class="img-fluid hero-illustration" style="max-height: 400px; mix-blend-mode: multiply; animation: float 6s ease-in-out infinite;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BRAND STRIP -->
<div class="brand-strip">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 gap-sm-3 gap-md-4">
            <a href="{{ route('products', ['brand' => 'nordvpn']) }}" class="brand-strip-item">NordVPN</a>
            <a href="{{ route('products', ['brand' => 'expressvpn']) }}" class="brand-strip-item">ExpressVPN</a>
            <a href="{{ route('products', ['brand' => 'surfshark']) }}" class="brand-strip-item">Surfshark</a>
            <a href="{{ route('products', ['brand' => 'hma']) }}" class="brand-strip-item">HMA VPN</a>
            <a href="{{ route('products', ['brand' => 'cyberghost']) }}" class="brand-strip-item">CyberGhost</a>
            <a href="{{ route('products', ['brand' => 'purevpn']) }}" class="brand-strip-item">PureVPN</a>
            <a href="{{ route('products', ['brand' => 'ipvanish']) }}" class="brand-strip-item">IPVanish</a>
            <a href="{{ route('products', ['brand' => 'protonvpn']) }}" class="brand-strip-item">ProtonVPN</a>
        </div>
    </div>
</div>

<!-- WHY CHOOSE US SECTION (MERGED & SIMPLIFIED) -->
<section class="section-sm" style="background:#fff;border-bottom:1px solid var(--gray-100)">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                    <div class="feature-title">Bảo Mật Tối Đa</div>
                    <div class="feature-desc">Mã hóa AES-256 quân đội & chính sách không lưu nhật ký (No-Log).</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                    <div class="feature-title">Tốc Độ Vượt Trội</div>
                    <div class="feature-desc">Băng thông không giới hạn, máy chủ tốc độ cao tại 100+ quốc gia.</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-patch-check"></i></div>
                    <div class="feature-title">100% Chính Hãng</div>
                    <div class="feature-desc">Cam kết key bản quyền chính hãng từ nhà phát hành, hoàn tiền nếu lỗi.</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-headset"></i></div>
                    <div class="feature-title">Hỗ Trợ Kỹ Thuật</div>
                    <div class="feature-desc">Đội ngũ kỹ thuật hỗ trợ kích hoạt và hướng dẫn sử dụng 24/7.</div>
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
            $hotProducts = $allProducts ?? [];
            shuffle($hotProducts);
            $hotProducts = array_slice($hotProducts, 0, 6);
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
                            <img src="{{ asset($prod['image_path']) }}" alt="{{ $prod['name'] }}">
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
                            @foreach($prod['features'] as $fi => $feat)
                                @if($fi < 2)
                                    <li><i class="bi bi-check-circle-fill"></i>{{ $feat }}</li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="product-rating">
                            <div class="rating-stars">
                                @for($i=1;$i<=5;$i++)
                                    <i class="bi {{ $i <= floor($prod['rating']) ? 'bi-star-fill' : ($i - $prod['rating'] < 1 ? 'bi-star-half' : 'bi-star') }}"></i>
                                @endfor
                            </div>
                            <span class="ms-2 text-muted" style="font-size:12px">• Đã bán {{ \App\Models\Setting::get('sales_' . strtolower($prod['slug']), '100+') }}</span>
                        </div>
                        <div class="product-price-wrap">
                            @if(($prod['old_price'] ?? 0) > $prod['price'])
                                <div class="product-price-old">{{ number_format($prod['old_price']) }}đ</div>
                            @endif
                            <div class="d-flex align-items-baseline gap-1">
                                <div class="product-price">{{ number_format($prod['price']) }}đ</div>
                                <span class="product-price-unit">/{{ $prod['plan'] === '1year' ? '1 năm' : '2 năm' }}</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('product.detail', $prod['slug']) }}" class="btn-add-cart w-100" style="text-decoration:none">
                                Xem Chi Tiết
                            </a>
                            @if(($prod['stock'] ?? 0) > 0)
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
<section style="background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%); padding: 50px 0; border-radius: var(--radius-lg); margin: 30px 0;">
    <div class="container">
        <div class="text-center text-white py-4">
            <div class="badge text-uppercase mb-3 px-3 py-2" style="background:rgba(255,255,255,.1);color:#fbbf24;font-size:11px;letter-spacing:1px;border-radius:20px;border:1px solid rgba(255,255,255,.15)">
                <i class="bi bi-lightning-charge-fill me-1"></i>Ưu Đãi Đặc Biệt
            </div>
            <h2 class="font-poppins fw-800 mb-3" style="font-size:30px;">
                Mua Gói VPN Bản Quyền — Tiết Kiệm Đến 70%
            </h2>
            <p class="mx-auto text-white-50" style="max-width: 540px; font-size: 14.5px;">
                Nhận tài khoản VPN chính quyền chính hãng với đầy đủ các tính năng bảo mật tối cao và tốc độ không giới hạn.
            </p>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ route('products') }}" class="btn btn-warning fw-700 px-4 py-2.5 rounded-pill">
                    Mua Ngay
                </a>
                <a href="{{ route('pricing') }}" class="btn btn-outline-light fw-600 px-4 py-2.5 rounded-pill">
                    Xem Bảng Giá
                </a>
            </div>
        </div>
    </div>
</section>

<!-- PURCHASE PROCESS -->
<section class="section" style="background:#fff">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">⚡ HƯỚNG DẪN MUA HÀNG</span>
            <h2 class="section-title mt-3 font-poppins fw-800" style="font-size: 28px; color: var(--gray-900);">Quy Trình Mua Hàng 3 Bước</h2>
            <p class="section-subtitle mx-auto text-muted mt-2" style="max-width: 500px; font-size: 14.5px;">Sở hữu ngay tài khoản VPN & Proxy bản quyền chỉ với 3 bước cực kỳ đơn giản và nhanh chóng.</p>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-lg-4">
                <div class="text-center p-4 rounded-4" style="background: var(--gray-50); border: 1px solid var(--gray-100); height: 100%;">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: var(--primary-light); border-radius: 50%; color: var(--primary); font-size: 20px; font-weight: 800;">
                        1
                    </div>
                    <h4 class="font-poppins fw-700 mb-3" style="font-size: 16px; color: var(--gray-800);">Chọn Gói Dịch Vụ</h4>
                    <p class="text-muted mb-0" style="font-size: 13.5px; line-height: 1.6;">
                        Khám phá danh sách VPN & Proxy cao cấp, chọn gói cước phù hợp và tiến hành thanh toán.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center p-4 rounded-4" style="background: var(--gray-50); border: 1px solid var(--gray-100); height: 100%;">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: var(--accent-light); border-radius: 50%; color: var(--accent); font-size: 20px; font-weight: 800;">
                        2
                    </div>
                    <h4 class="font-poppins fw-700 mb-3" style="font-size: 16px; color: var(--gray-800);">Quét Mã QR Thanh Toán</h4>
                    <p class="text-muted mb-0" style="font-size: 13.5px; line-height: 1.6;">
                        Quét mã QR thanh toán động đi kèm nội dung chuyển khoản tự động chính xác.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center p-4 rounded-4" style="background: var(--gray-50); border: 1px solid var(--gray-100); height: 100%;">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: var(--warning-light); border-radius: 50%; color: var(--warning); font-size: 20px; font-weight: 800;">
                        3
                    </div>
                    <h4 class="font-poppins fw-700 mb-3" style="font-size: 16px; color: var(--gray-800);">Nhận Key Tự Động</h4>
                    <p class="text-muted mb-0" style="font-size: 13.5px; line-height: 1.6;">
                        Thông tin tài khoản/key kích hoạt sẽ lập tức gửi trực tiếp vào email của bạn sau ít phút.
                    </p>
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
