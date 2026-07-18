@extends('layouts.app')

@section('title', $settings['seo_title'] ?? 'VPNStore - Cửa Hàng VPN & Proxy Chính Hãng Số 1 Việt Nam')
@section('meta_description', $settings['meta_description'] ?? 'Mua VPN Premium, ChatGPT, Adobe, JetBrains, Netflix với giá tốt nhất thị trường. Giao hàng tự động, bảo hành uy tín, hỗ trợ 24/7.')
@section('meta_keywords', $settings['meta_keywords'] ?? 'vpn gia re, proxy gia re, mua vpn, mua proxy, key vpn ban quyen')

@section('content')

@php
    // Query items using Eloquent models directly in Blade to bypass controller constraints
    $featuredProducts = \App\Models\Product::where('status', 'active')->where('is_popular', true)->with('category')
        ->orderBy('id', 'desc')->limit(8)->get();

    $popularProducts = \App\Models\Product::where('status', 'active')
        ->orderBy('sold', 'desc')->limit(6)->get();

    $categories = \App\Models\Category::withCount('products')->get();

    // Query actual completed orders
    $realOrders = \App\Models\Order::where('order_status', 'completed')
        ->orderBy('id', 'desc')
        ->limit(8)
        ->get();
@endphp

{{-- ===== SOCIAL PROOF TICKER ===== --}}
@if($realOrders->isNotEmpty())
<div class="social-proof-bar">
    <div class="ticker-track">
        @php $doubled = $realOrders->concat($realOrders); @endphp
        @foreach($doubled as $order)
        @php
            $nameParts = explode(' ', trim($order->customer_name));
            $displayName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 0, -1)) . ' *' : $order->customer_name;
        @endphp
        <div class="ticker-item">
            <div class="ticker-avatar">{{ mb_substr($order->customer_name, 0, 1) }}</div>
            <span><span class="ticker-name">{{ $displayName }}</span> vừa mua</span>
            <span style="color:var(--text-primary); font-weight:600;">{{ $order->product_name }}</span>
            <span class="ticker-amount">{{ number_format($order->total, 0, ',', '.') }}đ</span>
            <span style="color:var(--text-muted); display: inline-flex; align-items: center; gap: 4px;"><i class="bi bi-clock"></i> {{ $order->created_at->diffForHumans() }}</span>
            <span style="color:var(--border); margin:0 4px;">•</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ===== HERO SECTION ===== --}}
<section class="hero">
    <div class="hero-bg">
        <svg width="100%" height="100%" style="position:absolute;inset:0;opacity:0.04;" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="#a78bfa"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dots)"/>
        </svg>
    </div>

    <div class="container">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:center;" class="hero-grid-wrap">
            <div class="hero-content animate-fade-up">
                <div class="hero-eyebrow">
                    <div class="dot"></div>
                    <i class="bi bi-fire text-warning" style="margin-right:6px;"></i> Hơn 50,000+ khách hàng tin dùng
                </div>

                <h1 class="hero-title">
                    Phần Mềm <span class="gradient-text">Bản Quyền</span><br>
                    Giá Siêu Rẻ
                </h1>

                <p class="hero-desc">
                    Chuyên cung cấp các gói VPN Premium & Proxy bản quyền chính hãng với giá tốt nhất. Giao hàng tức thì, bảo hành uy tín, hỗ trợ 24/7.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('products') }}" class="btn btn-primary btn-xl">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Mua VPN Ngay
                    </a>
                    <a href="#featured" class="btn btn-outline btn-xl">Xem Nổi Bật</a>
                </div>

                <div class="hero-stats" style="margin-top: 40px; gap: 32px;">
                    <div class="hero-stat" style="flex-direction: row; align-items: center; gap: 12px; max-width: 220px;">
                        <div style="width: 42px; height: 42px; background: rgba(79, 70, 229, 0.08); color: var(--primary); border: 1px solid rgba(79, 70, 229, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-primary);">Giao Tự Động</span>
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Nhận sản phẩm ngay sau khi mua</span>
                        </div>
                    </div>
                    <div class="hero-stat" style="flex-direction: row; align-items: center; gap: 12px; max-width: 220px;">
                        <div style="width: 42px; height: 42px; background: rgba(16, 185, 129, 0.08); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-primary);">Bảo Hành 1-Đổi-1</span>
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Cam kết bảo hành suốt thời hạn</span>
                        </div>
                    </div>
                    <div class="hero-stat" style="flex-direction: row; align-items: center; gap: 12px; max-width: 220px;">
                        <div style="width: 42px; height: 42px; background: rgba(124, 58, 237, 0.08); color: var(--accent); border: 1px solid rgba(124, 58, 237, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-primary);">Hỗ Trợ 24/7</span>
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Đội ngũ kỹ thuật hỗ trợ tận tình</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hero Visual --}}
            <div class="animate-float hero-visual-wrap" style="display:flex; justify-content:center; align-items:center;">
                <div style="position:relative; width:380px; height:380px;">
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                        <div style="width:200px; height:200px; background:linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2)); border:1px solid rgba(124,58,237,0.3); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:4.5rem; color:var(--primary-light); box-shadow:0 0 60px rgba(124,58,237,0.3);">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                    </div>
                    @php
                        $orbIcons = [
                            ['bi bi-shield-lock-fill', 'top:20px; left:50%; transform:translateX(-50%)', 'VPN', 'var(--primary-light)'],
                            ['bi bi-cpu-fill', 'top:50%; right:10px; transform:translateY(-50%)', 'AI', 'var(--accent)'],
                            ['bi bi-palette-fill', 'bottom:20px; left:50%; transform:translateX(-50%)', 'Design', '#ec4899'],
                            ['bi bi-code-slash', 'top:50%; left:10px; transform:translateY(-50%)', 'Dev', 'var(--warning)'],
                        ];
                    @endphp
                    @foreach($orbIcons as $i => $orb)
                    <div style="position:absolute; {{ $orb[1] }}; width:64px; height:64px; background:var(--bg-card); border:1px solid var(--border); border-radius:16px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:2px; box-shadow:var(--shadow-card); backdrop-filter:blur(10px);">
                        <span style="font-size:1.5rem; color:{{ $orb[3] }}; line-height:1;"><i class="{{ $orb[0] }}"></i></span>
                        <span style="font-size:0.55rem; color:var(--text-muted); font-weight:700; margin-top:4px;">{{ $orb[2] }}</span>
                    </div>
                    @endforeach

                    <div style="position:absolute; inset:30px; border:1px dashed rgba(124,58,237,0.2); border-radius:50%; animation:spin 20s linear infinite;"></div>
                    <div style="position:absolute; inset:60px; border:1px dashed rgba(6,182,212,0.15); border-radius:50%; animation:spin 15s linear infinite reverse;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CATEGORIES SECTION ===== --}}
<section class="section-sm" style="background:var(--bg-elevated);">
    <div class="container">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
            <h2 style="font-size:1.1rem; font-weight:700;">Thương Hiệu & Danh Mục</h2>
            <a href="{{ route('products') }}" class="btn btn-ghost btn-sm">Tất Cả →</a>
        </div>
        <div style="display:flex; justify-content:center; flex-wrap:wrap; gap:16px; margin:0 auto;">
            @foreach($categories as $cat)
            @php
                $catIcons = [
                    'nordvpn' => 'bi bi-shield-lock-fill',
                    'expressvpn' => 'bi bi-shield-lock-fill',
                    'surfshark' => 'bi bi-shield-lock-fill',
                    'hma' => 'bi bi-shield-lock-fill'
                ];
                $iconClass = $catIcons[$cat->slug] ?? 'bi bi-tag-fill';
            @endphp
            <a href="{{ route('products', ['brand' => $cat->slug]) }}"
               class="card animate-on-scroll"
               style="text-align:center; padding:24px 16px; cursor:pointer; text-decoration:none; width:200px; flex-shrink:0; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                @if($cat->image_path)
                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" style="width:50px; height:50px; object-fit:contain; margin-bottom:12px;">
                @else
                    <div style="font-size:2.2rem; margin-bottom:12px; color:var(--primary-light);"><i class="{{ $iconClass }}"></i></div>
                @endif
                <div style="font-size:0.85rem; font-weight:700; color:var(--text-primary); margin-bottom:6px;">{{ $cat->name }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $cat->products_count }} sản phẩm</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FEATURED PRODUCTS ===== --}}
<section class="section" id="featured">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title"><i class="bi bi-fire text-warning" style="margin-right:8px;"></i> Sản Phẩm <span>Nổi Bật</span></h2>
                <p class="section-subtitle">Được lựa chọn nhiều nhất - Chất lượng đảm bảo</p>
            </div>
            <a href="{{ route('products') }}" class="btn btn-outline">Xem Tất Cả</a>
        </div>

        <div class="product-grid">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

{{-- ===== WHY US SECTION ===== --}}
<section class="section" style="background:var(--bg-elevated);">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Tại Sao Chọn <span>{{ $settings['store_name'] ?? 'VPNStore' }}?</span></h2>
                <p class="section-subtitle">Cam kết mang đến trải nghiệm mua sắm tốt nhất</p>
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-card animate-on-scroll">
                <span class="feature-icon" style="font-size:2rem; line-height:1;"><i class="bi bi-lightning-charge-fill" style="color:var(--warning);"></i></span>
                <h3 class="feature-title">Giao Hàng Tự Động</h3>
                <p class="feature-desc">License key và tài khoản được giao ngay lập tức sau khi thanh toán. Không phải chờ đợi.</p>
            </div>
            <div class="feature-card animate-on-scroll delay-1">
                <span class="feature-icon" style="font-size:2rem; line-height:1;"><i class="bi bi-shield-lock-fill" style="color:var(--accent);"></i></span>
                <h3 class="feature-title">Bảo Hành Uy Tín</h3>
                <p class="feature-desc">Cam kết hoàn tiền hoặc đổi sản phẩm nếu có lỗi trong vòng thời gian bảo hành.</p>
            </div>
            <div class="feature-card animate-on-scroll delay-2">
                <span class="feature-icon" style="font-size:2rem; line-height:1;"><i class="bi bi-wallet2" style="color:#10b981;"></i></span>
                <h3 class="feature-title">Giá Tốt Nhất</h3>
                <p class="feature-desc">Giá thấp hơn 60-80% so với mua trực tiếp. Cập nhật deal mới mỗi ngày.</p>
            </div>
            <div class="feature-card animate-on-scroll delay-3">
                <span class="feature-icon" style="font-size:2rem; line-height:1;"><i class="bi bi-shield-fill-check" style="color:var(--primary-light);"></i></span>
                <h3 class="feature-title">Thanh Toán An Toàn</h3>
                <p class="feature-desc">Thanh toán qua ngân hàng/Momo. Mã hóa SSL 256-bit bảo vệ thông tin của bạn.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== POPULAR PRODUCTS ===== --}}
@if($popularProducts->count())
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title"><i class="bi bi-graph-up-arrow text-primary" style="margin-right: 8px;"></i> Bán Chạy <span>Nhất</span></h2>
                <p class="section-subtitle">Lựa chọn hàng đầu của hàng nghìn khách hàng</p>
            </div>
        </div>
        <div class="product-grid">
            @foreach($popularProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== CTA SECTION ===== --}}
<section class="section">
    <div class="container">
        <div style="text-align:center; max-width:600px; margin:0 auto;">
            <div style="font-size:3rem; margin-bottom:16px; color:var(--primary-light);"><i class="bi bi-rocket-takeoff-fill"></i></div>
            <h2 class="section-title" style="margin-bottom:16px;">Sẵn Sàng <span>Bắt Đầu?</span></h2>
            <p style="color:var(--text-secondary); margin-bottom:32px; line-height:1.7;">
                Đăng ký miễn phí và khám phá các gói VPN, Proxy bản quyền với mức giá tốt nhất. Giao hàng tự động tức thì.
            </p>
            <div class="hero-actions" style="justify-content:center;">
                @guest
                    <a href="{{ route('auth.register') }}" class="btn btn-primary btn-xl">Đăng Ký Miễn Phí</a>
                    <a href="{{ route('products') }}" class="btn btn-outline btn-xl">Xem Sản Phẩm</a>
                @else
                    <a href="{{ route('products') }}" class="btn btn-primary btn-xl">Mua Ngay</a>
                @endguest
            </div>
        </div>
    </div>
</section>

@endsection
