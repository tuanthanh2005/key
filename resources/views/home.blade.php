@extends('layouts.app')

@section('title', $settings['seo_title'] ?? 'VPNStore - Mua Tài Khoản Bản Quyền, AI, TikTok, Canva Giá Rẻ - Bảo Hành Trọn Gói')
@section('meta_description', $settings['meta_description'] ?? 'Cửa hàng cung cấp tài khoản bản quyền giá rẻ uy tín #1 Việt Nam. Mua tài khoản ChatGPT, TikTok, Canva, Netflix, CapCut, Spotify, VPN chính hãng giá rẻ - Bảo hành trọn gói 1 đổi 1. Giao tự động 24/7.')
@section('meta_keywords', $settings['meta_keywords'] ?? 'tai khoan gia re, tai khoan tiktok gia re, mua tai khoan chatgpt, tai khoan canva pro, tai khoan netflix gia re, tai khoan vpn gia re, phan mem ban quyen gia re, bao hanh tron goi')

@section('content')

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

    <div class="container-fluid" style="width: 100%; padding: 0 32px;">
        <div class="hero-grid-wrap">
            <div class="hero-content animate-fade-up">
                <h1 class="hero-title">
                    Phần Mềm <span class="gradient-text">Bản Quyền</span> Giá Siêu Rẻ
                </h1>

                <p class="hero-desc">
                    Chuyên cung cấp VPN Premium, Proxy & Tài khoản bản quyền chính hãng với giá tốt nhất thị trường.
                </p>

                <div class="hero-actions" style="margin-bottom:0; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                    <a href="{{ route('products') }}" class="btn btn-primary btn-sm" style="padding: 8px 22px; font-size: 0.88rem; font-weight:700;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Mua VPN Ngay
                    </a>
                    <a href="#featured" class="btn btn-outline btn-sm" style="padding: 8px 18px; font-size: 0.88rem;">Xem Nổi Bật</a>

                    <div class="hero-badges" style="display:flex; align-items:center; gap:16px; margin-left:8px; font-size:0.78rem; color:var(--text-secondary); font-weight:600;">
                        <span style="display:inline-flex; align-items:center; gap:4px; white-space:nowrap;"><i class="bi bi-lightning-charge-fill text-warning"></i> Giao Tự Động</span>
                        <span style="display:inline-flex; align-items:center; gap:4px; white-space:nowrap;"><i class="bi bi-shield-check text-success"></i> Bảo Hành 1-Đổi-1</span>
                        <span style="display:inline-flex; align-items:center; gap:4px; white-space:nowrap;"><i class="bi bi-headset text-primary"></i> Hỗ Trợ 24/7</span>
                    </div>
                </div>
            </div>

            {{-- Hero Visual --}}
            <div class="animate-float hero-visual-wrap" style="display:flex; justify-content:center; align-items:center;">
                <div class="hero-visual-inner">
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                        <div style="width:100px; height:100px; background:linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2)); border:1px solid rgba(124,58,237,0.3); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2.2rem; color:var(--primary-light); box-shadow:0 0 30px rgba(124,58,237,0.3);">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                    </div>
                    @php
                        $orbIcons = [
                            ['bi bi-shield-lock-fill', 'top:2px; left:50%; transform:translateX(-50%)', 'VPN', 'var(--primary-light)'],
                            ['bi bi-cpu-fill', 'top:50%; right:-4px; transform:translateY(-50%)', 'AI', 'var(--accent)'],
                            ['bi bi-palette-fill', 'bottom:2px; left:50%; transform:translateX(-50%)', 'Design', '#ec4899'],
                            ['bi bi-code-slash', 'top:50%; left:-4px; transform:translateY(-50%)', 'Dev', 'var(--warning)'],
                        ];
                    @endphp
                    @foreach($orbIcons as $i => $orb)
                    <div style="position:absolute; {{ $orb[1] }}; width:40px; height:40px; background:var(--bg-card); border:1px solid var(--border); border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:1px; box-shadow:var(--shadow-card); backdrop-filter:blur(10px);">
                        <span style="font-size:0.95rem; color:{{ $orb[3] }}; line-height:1;"><i class="{{ $orb[0] }}"></i></span>
                        <span style="font-size:0.48rem; color:var(--text-muted); font-weight:700;">{{ $orb[2] }}</span>
                    </div>
                    @endforeach

                    <div style="position:absolute; inset:12px; border:1px dashed rgba(124,58,237,0.2); border-radius:50%; animation:spin 20s linear infinite;"></div>
                    <div style="position:absolute; inset:32px; border:1px dashed rgba(6,182,212,0.15); border-radius:50%; animation:spin 15s linear infinite reverse;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CATEGORIES SECTION ===== --}}
<section class="home-brands-section" style="background:var(--bg-elevated); padding: 16px 0;">
    <div class="container-fluid" style="width:100%; padding: 0 32px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <h2 style="font-size:1rem; font-weight:700;">Thương Hiệu & Danh Mục</h2>
            <a href="{{ route('products') }}" class="btn btn-ghost btn-sm" style="padding:4px 10px; font-size:0.8rem;">Tất Cả →</a>
        </div>
        <div class="categories-grid" id="categories-grid">
            @foreach($categories as $index => $cat)
            @php
                if (is_object($cat)) {
                    $cSlug = $cat->slug ?? '';
                    $cName = $cat->name ?? '';
                    $cImgPath = $cat->image_path ?? null;
                    $cImgUrl = $cat->image_url ?? '';
                    $cCount = $cat->products_count ?? 0;
                } else if (is_array($cat)) {
                    $cSlug = $cat['slug'] ?? '';
                    $cName = $cat['name'] ?? '';
                    $cImgPath = $cat['image_path'] ?? null;
                    $cImgUrl = $cat['image_url'] ?? '';
                    $cCount = $cat['products_count'] ?? 0;
                } else {
                    $cSlug = (string)$cat;
                    $cName = (string)$cat;
                    $cImgPath = null;
                    $cImgUrl = '';
                    $cCount = 0;
                }

                if (empty($cImgUrl) && !empty($cImgPath)) {
                    $p = trim($cImgPath);
                    if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
                        $cImgUrl = $p;
                    } else if (str_starts_with($p, 'storage/')) {
                        $cImgUrl = asset($p);
                    } else {
                        $cImgUrl = asset('storage/' . $p);
                    }
                }

                $slugLower = strtolower($cSlug . ' ' . $cName);
                if (str_contains($slugLower, 'vpn')) $iconClass = 'bi bi-shield-lock-fill';
                elseif (str_contains($slugLower, 'chat') || str_contains($slugLower, 'gpt') || str_contains($slugLower, 'ai') || str_contains($slugLower, 'gemini') || str_contains($slugLower, 'grok') || str_contains($slugLower, 'claude')) $iconClass = 'bi bi-cpu-fill';
                elseif (str_contains($slugLower, 'canva') || str_contains($slugLower, 'design') || str_contains($slugLower, 'adobe') || str_contains($slugLower, 'photoshop')) $iconClass = 'bi bi-palette-fill';
                elseif (str_contains($slugLower, 'youtube') || str_contains($slugLower, 'capcut') || str_contains($slugLower, 'netflix') || str_contains($slugLower, 'video') || str_contains($slugLower, 'phim')) $iconClass = 'bi bi-play-btn-fill';
                elseif (str_contains($slugLower, 'intellij') || str_contains($slugLower, 'code') || str_contains($slugLower, 'cursor') || str_contains($slugLower, 'jetbrains')) $iconClass = 'bi bi-code-slash';
                elseif (str_contains($slugLower, 'proxy')) $iconClass = 'bi bi-hdd-network-fill';
                else $iconClass = 'bi bi-grid-fill';
            @endphp
            <a href="{{ route('products', ['brand' => $cSlug]) }}"
               class="card animate-on-scroll category-card @if($index >= 2) category-card-hidden-mobile @endif"
               style="cursor:pointer; text-decoration:none;">
                @if(!empty($cImgUrl) && strlen($cImgUrl) > 5)
                    <img src="{{ $cImgUrl }}" alt="{{ $cName }}" width="32" height="32" loading="lazy" decoding="async" style="width:32px; height:32px; object-fit:contain; flex-shrink:0;" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                    <div style="font-size:1.3rem; color:var(--primary-light); flex-shrink:0; display:none; align-items:center; justify-content:center; width:32px; height:32px;"><i class="{{ $iconClass }}"></i></div>
                @else
                    <div style="font-size:1.3rem; color:var(--primary-light); flex-shrink:0; display:flex; align-items:center; justify-content:center; width:32px; height:32px;"><i class="{{ $iconClass }}"></i></div>
                @endif
                <div style="display:flex; flex-direction:column; min-width:0; flex:1;">
                    <div style="font-size:0.8rem; font-weight:700; color:var(--text-primary); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">{{ $cName }}</div>
                    <div style="font-size:0.68rem; color:var(--text-muted);">{{ $cCount }} sản phẩm</div>
                </div>
            </a>
            @endforeach
        </div>

        @if(count(collect($categories)) > 2)
        <div class="toggle-categories-mobile" style="text-align:center; margin-top:10px;">
            <button type="button" class="btn btn-outline btn-sm" id="toggle-cats-btn" onclick="toggleMobileCategories()" aria-label="Xem thêm danh mục" style="border-radius:50%; width:36px; height:36px; padding:0; display:inline-flex; align-items:center; justify-content:center; font-size:0.9rem; background:var(--bg-card);">
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>

<script>
function toggleMobileCategories() {
    const grid = document.getElementById('categories-grid');
    const btn = document.getElementById('toggle-cats-btn');
    if (!grid || !btn) return;

    grid.classList.toggle('expanded');
    const isExpanded = grid.classList.contains('expanded');

    btn.innerHTML = isExpanded
        ? '<i class="bi bi-chevron-up"></i>'
        : '<i class="bi bi-chevron-down"></i>';
}
</script>

{{-- ===== FEATURED PRODUCTS ===== --}}
<section class="section" id="featured" style="padding: 24px 0;">
    <div class="container-fluid" style="width:100%; padding: 0 32px;">
        <div class="section-header" style="margin-bottom: 16px;">
            <div>
                <h2 class="section-title"><i class="bi bi-fire text-warning" style="margin-right:6px;"></i> Sản Phẩm <span>Nổi Bật</span></h2>
            </div>
            <a href="{{ route('products') }}" class="btn btn-outline btn-sm" style="padding: 6px 14px; font-size: 0.82rem;">Xem Tất Cả</a>
        </div>

        <div class="product-grid">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

@if(count(collect($popularProducts)) > 0)
<section class="section" style="padding: 24px 0;">
    <div class="container-fluid" style="width:100%; padding: 0 32px;">
        <div class="section-header" style="margin-bottom: 16px;">
            <div>
                <h2 class="section-title"><i class="bi bi-graph-up-arrow text-primary" style="margin-right: 6px;"></i> Bán Chạy <span>Nhất</span></h2>
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

{{-- ===== WHY US SECTION ===== --}}
<section class="section" style="background:var(--bg-elevated); padding: 28px 0;">
    <div class="container-fluid" style="width:100%; padding: 0 32px;">
        <div class="section-header" style="margin-bottom: 16px;">
            <div>
                <h2 class="section-title">Tại Sao Chọn <span>{{ $settings['store_name'] ?? 'VPNStore' }}?</span></h2>
                <p class="section-subtitle">Cam kết mang đến trải nghiệm mua sắm tốt nhất</p>
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-card animate-on-scroll">
                <span class="feature-icon" style="font-size:1.6rem; line-height:1;"><i class="bi bi-lightning-charge-fill" style="color:var(--warning);"></i></span>
                <h3 class="feature-title">Giao Hàng Tự Động</h3>
                <p class="feature-desc">License key và tài khoản được giao ngay lập tức sau khi thanh toán. Không phải chờ đợi.</p>
            </div>
            <div class="feature-card animate-on-scroll delay-1">
                <span class="feature-icon" style="font-size:1.6rem; line-height:1;"><i class="bi bi-shield-lock-fill" style="color:var(--accent);"></i></span>
                <h3 class="feature-title">Bảo Hành Uy Tín</h3>
                <p class="feature-desc">Cam kết hoàn tiền hoặc đổi sản phẩm nếu có lỗi trong vòng thời gian bảo hành.</p>
            </div>
            <div class="feature-card animate-on-scroll delay-2">
                <span class="feature-icon" style="font-size:1.6rem; line-height:1;"><i class="bi bi-wallet2" style="color:#10b981;"></i></span>
                <h3 class="feature-title">Giá Tốt Nhất</h3>
                <p class="feature-desc">Giá thấp hơn 60-80% so với mua trực tiếp. Cập nhật deal mới mỗi ngày.</p>
            </div>
            <div class="feature-card animate-on-scroll delay-3">
                <span class="feature-icon" style="font-size:1.6rem; line-height:1;"><i class="bi bi-shield-fill-check" style="color:var(--primary-light);"></i></span>
                <h3 class="feature-title">Thanh Toán An Toàn</h3>
                <p class="feature-desc">Thanh toán qua ngân hàng/Momo. Mã hóa SSL 256-bit bảo vệ thông tin của bạn.</p>
            </div>
        </div>
    </div>
</section>

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
