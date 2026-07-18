<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(!empty($settings['google_site_verification']))
    <meta name="google-site-verification" content="{{ $settings['google_site_verification'] }}">
    @endif

    {{-- SEO Meta --}}
    @hasSection('title')
        <title>@yield('title') | {{ $settings['store_name'] ?? 'VPN Store Pro' }}</title>
    @else
        <title>{{ !empty($settings['seo_title']) ? $settings['seo_title'] : ($settings['store_name'] ?? 'VPN Store Pro') }}</title>
    @endif
    <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? 'VPN Store Pro - Mua VPN Premium, AI Code, Design Software, Xem Phim Premium giá tốt nhất. Giao hàng tự động, hỗ trợ 24/7.')">
    <meta name="keywords" content="@yield('meta_keywords', $settings['meta_keywords'] ?? 'vpn premium, nordvpn, expressvpn, mua vpn, ai code, design software, xem phim premium, phần mềm bản quyền')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', !empty($settings['seo_title']) ? $settings['seo_title'] : ($settings['store_name'] ?? 'VPN Store Pro'))">
    <meta property="og:description" content="@yield('meta_description', $settings['meta_description'] ?? 'Mua phần mềm bản quyền giá tốt nhất')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-banner.png'))">
    <meta property="og:site_name" content="{{ $settings['store_name'] ?? 'VPN Store Pro' }}">
    <meta property="og:locale" content="vi_VN">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', !empty($settings['seo_title']) ? $settings['seo_title'] : ($settings['store_name'] ?? 'VPN Store Pro'))">
    <meta name="twitter:description" content="@yield('meta_description', $settings['meta_description'] ?? 'Mua phần mềm bản quyền giá tốt nhất')">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%237c3aed'><path d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'/></svg>">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=1.01">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('head')

    {{-- Structured Data --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Store",
        "name": "{{ $settings['store_name'] ?? 'VPNStore' }}",
        "description": "{{ $settings['meta_description'] ?? 'Cửa hàng phần mềm bản quyền uy tín - VPN Premium & Proxy' }}",
        "url": "{{ route('home') }}",
        "currenciesAccepted": "VND",
        "priceRange": "$$",
        "areaServed": "VN"
    }
    </script>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar" id="main-navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <span>{{ $settings['store_name'] ?? 'VPNStore' }}</span>
    </a>

    <ul class="navbar-nav" id="nav-links">
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang Chủ</a></li>
        <li class="user-dropdown">
            <a href="#" class="{{ request()->routeIs('products') ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 4px;">
                Sản Phẩm <i class="bi bi-chevron-down" style="font-size:0.75rem;"></i>
            </a>
            <div class="dropdown-menu" style="left:0; right:auto; min-width: 400px; padding: 12px;">
                <a href="{{ route('products') }}" class="dropdown-item" style="margin-bottom: 8px; font-weight: 700;">
                    <i class="bi bi-grid-fill text-primary"></i> Tất Cả Sản Phẩm
                </a>
                <div class="dropdown-divider" style="margin-bottom: 8px;"></div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 4px;">
                    @foreach($sharedCategories as $cat)
                        @php
                            $icon = 'bi-shield-lock-fill';
                            if (str_contains(strtolower($cat->slug), 'proxy') || $cat->type === 'proxy') {
                                $icon = 'bi-hdd-network-fill';
                            }
                        @endphp
                        <a href="{{ route('products', ['category' => $cat->slug]) }}" class="dropdown-item" style="padding: 8px 10px; display: inline-flex; align-items: center; gap: 8px;">
                            @if($cat->image_path)
                                <img src="{{ asset('storage/' . $cat->image_path) }}" alt="{{ $cat->name }}" style="width: 16px; height: 16px; object-fit: contain; border-radius: 2px;">
                            @else
                                <i class="bi {{ $icon }} text-primary"></i>
                            @endif
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </li>
        <li><a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">Bài Viết</a></li>
        <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Liên Hệ</a></li>
        @if(auth()->check() && auth()->user()->isAdmin())
            <li><a href="{{ route('admin.dashboard') }}" style="color: var(--primary-light);"><i class="bi bi-speedometer2" style="margin-right:4px;"></i> Admin</a></li>
        @endif
    </ul>

    <div class="navbar-actions">
        {{-- Search Button --}}
        <button class="cart-btn" style="padding:0; width:38px; height:38px; justify-content:center; align-items:center; border-radius:50%;" onclick="openSearchModal()" title="Tìm kiếm">
            <i class="bi bi-search" style="font-size:1.1rem;"></i>
        </button>

        {{-- Gift Button --}}
        @if(auth()->check() && !empty($userCoupons) && $userCoupons->isNotEmpty())
        <button class="cart-btn text-warning" style="padding:0; width:38px; height:38px; justify-content:center; align-items:center; border-radius:50%;" onclick="openGiftModal()" title="Mã giảm giá dành riêng cho bạn!">
            <i class="bi bi-gift-fill" style="font-size:1.1rem;"></i>
        </button>
        @endif

        {{-- Cart Button --}}
        <a href="{{ route('cart') }}" class="cart-btn" id="cart-btn">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span>Giỏ Hàng</span>
            <span class="cart-badge" id="cart-count" style="display:none">0</span>
        </a>

        {{-- User Area --}}
        @if(auth()->check())
            <div class="user-dropdown">
                <button class="user-btn" type="button">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                    @else
                        <div class="user-avatar" style="background: linear-gradient(135deg, var(--primary), var(--accent)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:0.8rem;">
                            {{ auth()->user()->getAvatarInitial() }}
                        </div>
                    @endif
                    <span>{{ Str::limit(auth()->user()->name, 14) }}</span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('order.history') }}" class="dropdown-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Lịch Sử Đơn Hàng
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="dropdown-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Sản Phẩm Yêu Thích
                    </a>
                    @if(auth()->user()->isAdmin())
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item" style="color: var(--primary-light);">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            Admin Panel
                        </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item danger">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Đăng Xuất
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('auth.login') }}" class="btn btn-ghost btn-sm">Đăng Nhập</a>
            <a href="{{ route('auth.register') }}" class="btn btn-primary btn-sm">Đăng Ký</a>
        @endif

        <button class="hamburger" id="hamburger-btn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- Mobile Nav Overlay --}}
<div id="mobile-nav" style="display:none; position:fixed; inset:0; z-index:999; background:rgba(0,0,0,0.8); backdrop-filter:blur(8px);">
    <div style="background:var(--bg-elevated); width:280px; height:100%; padding:24px; display:flex; flex-direction:column; gap:8px; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <span style="font-weight:800; font-size:1.1rem;" class="gradient-text">Menu</span>
            <button id="close-nav" style="background:none; border:none; color:var(--text-primary); font-size:1.5rem; cursor:pointer;">✕</button>
        </div>
        <a href="{{ route('home') }}" class="dropdown-item"><i class="bi bi-house-door" style="margin-right:8px;"></i> Trang Chủ</a>
        <a href="{{ route('products') }}" class="dropdown-item"><i class="bi bi-box-seam" style="margin-right:8px;"></i> Tất Cả Sản Phẩm</a>
        @foreach($sharedCategories as $cat)
            @php
                $icon = 'bi-shield-lock-fill';
                if (str_contains(strtolower($cat->slug), 'proxy') || $cat->type === 'proxy') {
                    $icon = 'bi-hdd-network-fill';
                }
            @endphp
            <a href="{{ route('products', ['category' => $cat->slug]) }}" class="dropdown-item" style="display: flex; align-items: center; gap: 8px;">
                @if($cat->image_path)
                    <img src="{{ asset('storage/' . $cat->image_path) }}" alt="{{ $cat->name }}" style="width: 16px; height: 16px; object-fit: contain; border-radius: 2px;">
                @else
                    <i class="bi {{ $icon }}"></i>
                @endif
                {{ $cat->name }}
            </a>
        @endforeach
        <a href="{{ route('posts.index') }}" class="dropdown-item"><i class="bi bi-file-text" style="margin-right:8px;"></i> Bài Viết</a>
        <a href="{{ route('contact') }}" class="dropdown-item"><i class="bi bi-envelope" style="margin-right:8px;"></i> Liên Hệ</a>
        <div class="dropdown-divider"></div>
        @if(auth()->check())
            <a href="{{ route('order.history') }}" class="dropdown-item"><i class="bi bi-clock-history" style="margin-right:8px;"></i> Lịch Sử Đơn Hàng</a>
            <a href="{{ route('wishlist.index') }}" class="dropdown-item"><i class="bi bi-heart" style="margin-right:8px;"></i> Yêu Thích</a>
        @else
            <a href="{{ route('auth.login') }}" class="dropdown-item"><i class="bi bi-box-arrow-in-right" style="margin-right:8px;"></i> Đăng Nhập</a>
            <a href="{{ route('auth.register') }}" class="dropdown-item"><i class="bi bi-person-plus" style="margin-right:8px;"></i> Đăng Ký</a>
        @endif
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success') || session('error') || session('warning'))
<div class="toast-container" id="toast-container" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px;">
    @if(session('success'))
        <div class="toast success" id="flash-toast">
            <span style="color:var(--success); font-size:1.1rem; display:inline-flex; align-items:center;"><i class="bi bi-check-circle-fill"></i></span>
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" style="margin-left:auto; background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:1rem;">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="toast error" id="flash-toast">
            <span style="color:var(--danger); font-size:1.1rem; display:inline-flex; align-items:center;"><i class="bi bi-exclamation-triangle-fill"></i></span>
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" style="margin-left:auto; background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:1rem;">✕</button>
        </div>
    @endif
</div>
@endif

{{-- MAIN CONTENT --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="navbar-brand" style="margin-bottom:0;">
                    <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
                    <span class="gradient-text" style="font-size:1.2rem; font-weight:800;">{{ $settings['store_name'] ?? 'VPNStore' }}</span>
                </div>
                <p>Cửa hàng phần mềm bản quyền uy tín #1 Việt Nam. Cung cấp VPN Premium và Proxy chính hãng với giá tốt nhất và giao hàng tự động 24/7.</p>
                <div class="footer-social">
                    <a href="https://facebook.com" target="_blank" class="social-btn" title="Facebook"><i class="bi bi-facebook" style="font-size: 1.1rem;"></i></a>
                    @if(!empty($settings['telegram_support']))
                    <a href="{{ $settings['telegram_url'] ?? 'https://t.me/' . ltrim($settings['telegram_support'],'@') }}" target="_blank" class="social-btn" title="Telegram"><i class="bi bi-telegram" style="font-size: 1.1rem;"></i></a>
                    @endif
                    @if(!empty($settings['zalo_support']))
                    <a href="{{ $settings['zalo_url_1'] ?? 'https://zalo.me/' . $settings['zalo_support'] }}" target="_blank" class="social-btn" title="Zalo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24" style="display:inline-block; vertical-align:middle;">
                            <path d="M12 2C6.477 2 2 5.923 2 10.778c0 2.505 1.206 4.773 3.16 6.33L4 21l4.225-1.745c1.178.337 2.45.523 3.775.523 5.523 0 10-3.923 10-8.778C22 5.923 17.523 2 12 2zm1.666 12.18h-4.32l4.32-4.88v4.88zm-5.666 0v-6h1.222v4.825l3.82-4.825h1.564l-4.225 5.253 4.542.747v6h-1.222z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>

            <div>
                <h4 class="footer-heading">Sản Phẩm</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('products') }}">Tất Cả Sản Phẩm</a></li>
                    @if(isset($sharedCategories))
                        @foreach($sharedCategories->take(4) as $navCat)
                            <li><a href="{{ route('products', ['brand' => $navCat->slug]) }}">{{ $navCat->name }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Hỗ Trợ</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('order.check') }}">Tra Cứu Đơn Hàng</a></li>
                    <li><a href="{{ route('pricing') }}">Bảng Giá Dịch Vụ</a></li>
                    <li><a href="{{ route('about') }}">Giới Thiệu</a></li>
                    <li><a href="{{ route('contact') }}">Liên Hệ Hỗ Trợ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Liên Hệ</h4>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    @if(!empty($settings['contact_email']))
                    <div style="display:flex; align-items:center; gap:10px; font-size:0.875rem; color:var(--text-muted);">
                        <i class="bi bi-envelope-fill" style="color: var(--primary-light); font-size: 1rem;"></i>
                        <span>{{ $settings['contact_email'] }}</span>
                    </div>
                    @endif
                    @if(!empty($settings['telegram_support']))
                    <div style="display:flex; align-items:center; gap:10px; font-size:0.875rem; color:var(--text-muted);">
                        <i class="bi bi-telegram" style="color: var(--primary-light); font-size: 1rem;"></i>
                        <span>Telegram: {{ '@' . ltrim($settings['telegram_support'],'@') }}</span>
                    </div>
                    @endif
                    <div style="display:flex; align-items:center; gap:10px; font-size:0.875rem; color:var(--text-muted);">
                        <i class="bi bi-clock-fill" style="color: var(--primary-light); font-size: 1rem;"></i>
                        <span>Hỗ trợ 24/7 mọi lúc mọi nơi</span>
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-muted); margin-bottom:10px;">Thanh Toán An Toàn</div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <div style="padding:6px 12px; background:var(--bg-card); border:1px solid var(--border); border-radius:6px; font-size:0.75rem; color:var(--text-secondary);">MB Bank</div>
                        <div style="padding:6px 12px; background:var(--bg-card); border:1px solid var(--border); border-radius:6px; font-size:0.75rem; color:var(--text-secondary);">ATM</div>
                        <div style="padding:6px 12px; background:var(--bg-card); border:1px solid var(--border); border-radius:6px; font-size:0.75rem; color:var(--text-secondary);">Momo</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copy">© {{ date('Y') }} <span>{{ $settings['store_name'] ?? 'VPNStore' }}</span>. Bảo lưu mọi quyền.</p>
            <div style="display:flex; gap:20px;">
                <a href="#" onclick="event.preventDefault(); openSeoModal('terms')" style="font-size:0.8rem; color:var(--text-muted); transition:var(--transition);">Điều Khoản</a>
                <a href="#" onclick="event.preventDefault(); openSeoModal('privacy')" style="font-size:0.8rem; color:var(--text-muted); transition:var(--transition);">Bảo Mật</a>
            </div>
        </div>
    </div>
</footer>

{{-- SEARCH MODAL --}}
<div id="search-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.7); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--bg-elevated); border:1px solid var(--border); border-radius:var(--radius-xl); max-width:500px; width:100%; padding:24px; box-shadow:var(--shadow-card-hover); animation:fade-in-up 0.3s ease;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size:1.1rem; font-weight:800; color:var(--text-primary);"><i class="bi bi-search me-2 text-primary"></i>Tìm Kiếm</h3>
            <button onclick="closeSearchModal()" style="background:none; border:none; color:var(--text-primary); font-size:1.5rem; cursor:pointer;">✕</button>
        </div>
        <form action="{{ route('search') }}" method="GET">
            <div class="search-bar" style="max-width:100%; margin-bottom:16px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" placeholder="Nhập tên VPN, Proxy..." id="search-input" autocomplete="off" style="width:100%;">
            </div>
            <button type="submit" class="btn btn-primary btn-full" style="padding:12px;">Tìm Kiếm</button>
        </form>
    </div>
</div>

{{-- GIFT COUPON MODAL --}}
@if(auth()->check() && !empty($userCoupons) && $userCoupons->isNotEmpty())
    <div id="gift-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.7); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:16px;">
        <div style="background:var(--bg-elevated); border:1px solid var(--border); border-radius:var(--radius-xl); max-width:500px; width:100%; padding:24px; box-shadow:var(--shadow-card-hover); animation:fade-in-up 0.3s ease; text-align:center;">
            <div style="display:flex; justify-content:flex-end;">
                <button onclick="closeGiftModal()" style="background:none; border:none; color:var(--text-primary); font-size:1.5rem; cursor:pointer;">✕</button>
            </div>
            <div class="text-warning mb-2" style="font-size: 3.5rem;"><i class="bi bi-gift-fill"></i></div>
            <h3 style="font-size:1.2rem; font-weight:800; color:var(--text-primary); margin-bottom:8px;">Mã Giảm Giá Của Bạn!</h3>
            <p style="font-size:0.8rem; color:var(--text-muted); margin-bottom:20px;">Dành riêng cho tài khoản {{ auth()->user()->name }}</p>

            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach($userCoupons as $coupon)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:16px; border: 1.5px dashed var(--warning); background:rgba(245, 158, 11, 0.05); border-radius:12px;">
                    <div style="text-align:left;">
                        <div style="font-weight:700; font-family:var(--font-mono); color:var(--text-primary); font-size:1.1rem; letter-spacing:0.5px;">{{ $coupon->code }}</div>
                        <div style="color:var(--success); font-weight:700; font-size:0.8rem; margin-top:2px;">
                            @if($coupon->discount_type === 'percent')
                                Giảm {{ $coupon->discount_value }}%
                            @else
                                Giảm {{ number_format($coupon->discount_value) }}đ
                            @endif
                            (Đơn tối thiểu: {{ number_format($coupon->min_order) }}đ)
                        </div>
                        @if($coupon->expires_at)
                        <div style="color:var(--danger); font-size:0.7rem; margin-top:4px;">HSD: {{ $coupon->expires_at->format('d/m/Y') }}</div>
                        @endif
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="copyCouponCode('{{ $coupon->code }}', this)">Sao chép</button>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    function copyCouponCode(code, btn) {
        navigator.clipboard.writeText(code).then(() => {
            const orig = btn.textContent;
            btn.textContent = 'Đã chép';
            btn.style.background = 'var(--success)';
            setTimeout(() => {
                btn.textContent = orig;
                btn.style.background = '';
            }, 2000);
        });
    }
    </script>
@endif

{{-- JS --}}
<script>
// Mobile nav
const hamburger = document.getElementById('hamburger-btn');
const mobileNav = document.getElementById('mobile-nav');
const closeNav  = document.getElementById('close-nav');
if (hamburger && mobileNav) {
    hamburger.addEventListener('click', () => mobileNav.style.display = 'flex');
    closeNav.addEventListener('click', () => mobileNav.style.display = 'none');
    mobileNav.addEventListener('click', (e) => { if (e.target === mobileNav) mobileNav.style.display = 'none'; });
}

// Search Modal
function openSearchModal() {
    const modal = document.getElementById('search-modal');
    if (modal) {
        modal.style.display = 'flex';
        document.getElementById('search-input')?.focus();
    }
}
function closeSearchModal() {
    const modal = document.getElementById('search-modal');
    if (modal) modal.style.display = 'none';
}
document.getElementById('search-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeSearchModal();
});

// Gift Modal
function openGiftModal() {
    const modal = document.getElementById('gift-modal');
    if (modal) modal.style.display = 'flex';
}
function closeGiftModal() {
    const modal = document.getElementById('gift-modal');
    if (modal) modal.style.display = 'none';
}
document.getElementById('gift-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeGiftModal();
});

// Auto hide toasts
setTimeout(() => {
    document.querySelectorAll('.toast').forEach(t => t.style.opacity = '0');
    setTimeout(() => document.querySelectorAll('.toast').forEach(t => t.remove()), 400);
}, 5000);

// Scroll animation
document.addEventListener("DOMContentLoaded", function() {
    const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -40px 0px' };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target); // Stop observing once visible
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(24px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        setTimeout(() => {
            observer.observe(el);
        }, 50);
    });
});
</script>

{{-- Terms & Privacy Modal --}}
<div id="seo-info-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.7); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--bg-elevated); border:1px solid var(--border); border-radius:var(--radius-xl); max-width:600px; width:100%; max-height:85vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:var(--shadow-card-hover); animation:fade-in-up 0.3s ease;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <h3 id="seo-modal-title" style="font-size:1.2rem; font-weight:800; color:var(--text-primary);" class="gradient-text">Điều Khoản Dịch Vụ</h3>
            <button onclick="closeSeoModal()" style="background:none; border:none; color:var(--text-primary); font-size:1.5rem; cursor:pointer;">✕</button>
        </div>
        <div id="seo-modal-body" style="padding:24px; overflow-y:auto; font-size:0.9rem; color:var(--text-secondary); line-height:1.7; display:flex; flex-direction:column; gap:16px;">
        </div>
        <div style="padding:16px 24px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; background:rgba(0,0,0,0.02);">
            <button onclick="closeSeoModal()" class="btn btn-primary btn-sm">Đóng</button>
        </div>
    </div>
</div>

<script>
const seoModalData = {
    terms: {
        title: "Điều Khoản Dịch Vụ - {{ $settings['store_name'] ?? 'VPNStore' }}",
        content: `
            <p>Chào mừng bạn đến với <strong>{{ $settings['store_name'] ?? 'VPNStore' }}</strong>. Khi bạn truy cập và mua sắm tại website của chúng tôi, bạn mặc nhiên đồng ý với các điều khoản dịch vụ dưới đây:</p>
            
            <h4 style="color:var(--text-primary); font-weight:700; margin-top:8px;">1. Quy định về tài khoản & dịch vụ</h4>
            <p>Các tài khoản Premium do chúng tôi cung cấp là tài khoản bản quyền chính hãng. Quý khách có trách nhiệm bảo mật thông tin tài khoản được cấp và sử dụng đúng mục đích theo mô tả sản phẩm.</p>
            
            <h4 style="color:var(--text-primary); font-weight:700; margin-top:8px;">2. Nghiêm cấm hành vi phá hoại</h4>
            <p>Nghiêm cấm mọi hành vi chia sẻ tài khoản công cộng, thay đổi thông tin đăng nhập (trừ các dịch vụ kích hoạt chính chủ), hoặc sử dụng tài khoản cho các hoạt động vi phạm pháp luật. Mọi trường hợp vi phạm sẽ bị khóa tài khoản vĩnh viễn và không được hoàn tiền.</p>
            
            <h4 style="color:var(--text-primary); font-weight:700; margin-top:8px;">3. Quyền sở hữu trí tuệ</h4>
            <p>Tất cả nội dung, hình ảnh, bài viết và mã nguồn trên website thuộc quyền sở hữu của {{ $settings['store_name'] ?? 'VPNStore' }}. Mọi hành vi sao chép không xin phép để mục đích thương mại đều bị nghiên cấm.</p>
        `
    },
    privacy: {
        title: "Chính Sách Bảo Mật - {{ $settings['store_name'] ?? 'VPNStore' }}",
        content: `
            <p><strong>{{ $settings['store_name'] ?? 'VPNStore' }}</strong> cam kết bảo vệ tuyệt đối quyền riêng tư và thông tin cá nhân của khách hàng. Dưới đây là chính sách bảo mật chi tiết:</p>
            
            <h4 style="color:var(--text-primary); font-weight:700; margin-top:8px;">1. Thu thập thông tin cá nhân</h4>
            <p>Chúng tôi chỉ thu thập các thông tin cần thiết phục vụ cho việc đặt hàng và giao dịch, bao gồm: Họ tên, Email, Số điện thoại và Lịch sử giao dịch.</p>
            
            <h4 style="color:var(--text-primary); font-weight:700; margin-top:8px;">2. Bảo mật dữ liệu & mã hóa SSL</h4>
            <p>Mọi giao dịch thanh toán và thông tin truyền tải đều được bảo vệ bằng công nghệ mã hóa bảo mật SSL 256-bit tiên tiến, đảm bảo thông tin không bị đánh cắp bởi bên thứ ba.</p>
            
            <h4 style="color:var(--text-primary); font-weight:700; margin-top:8px;">3. Cam kết không chia sẻ dữ liệu</h4>
            <p>Chúng tôi tuyệt đối không bán, chia sẻ hoặc tiết lộ bất kỳ thông tin cá nhân nào của khách hàng cho bất kỳ tổ chức hay cá nhân bên ngoài nào khác, ngoại trừ trường hợp có yêu cầu chính thức bằng văn bản từ cơ quan pháp luật có thẩm quyền.</p>
        `
    }
};

function openSeoModal(type) {
    const modal = document.getElementById('seo-info-modal');
    const title = document.getElementById('seo-modal-title');
    const body = document.getElementById('seo-modal-body');
    if (modal && title && body && seoModalData[type]) {
        title.textContent = seoModalData[type].title;
        body.innerHTML = seoModalData[type].content;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeSeoModal() {
    const modal = document.getElementById('seo-info-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

document.getElementById('seo-info-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeSeoModal();
    }
});
</script>

<!-- Custom JS -->
<script>
    window.dbWishlist = @json(auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray() : null);
    window.csrfToken = '{{ csrf_token() }}';
</script>
<script src="{{ asset('js/app.js') }}?v=1.24"></script>
@yield('extra_js')
@stack('scripts')
</body>
</html>
