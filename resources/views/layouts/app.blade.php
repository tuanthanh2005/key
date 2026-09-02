<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(!empty($settings['google_site_verification']))
    <meta name="google-site-verification" content="{{ $settings['google_site_verification'] }}">
    @endif

    {{-- SEO Meta --}}
    @hasSection('title')
        @php
            $storeName = $settings['store_name'] ?? 'VPNStore';
            $yieldedTitle = trim(View::yieldContent('title'));
            $cleanTitle = preg_replace('/\s*[\|—–\-]\s*(' . preg_quote($storeName, '/') . '|VPNStore|VPN Store Pro)$/i', '', $yieldedTitle);
        @endphp
        <title>{{ $cleanTitle }} | {{ $storeName }}</title>
    @else
        <title>{{ !empty($settings['seo_title']) ? $settings['seo_title'] : ($settings['store_name'] ?? 'VPN Store Pro') }}</title>
    @endif
    <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? 'Cửa hàng tài khoản bản quyền giá rẻ uy tín #1 Việt Nam. Mua tài khoản ChatGPT, TikTok, Canva, Netflix, CapCut, Spotify, VPN chính hãng - Bảo hành trọn gói 1 đổi 1.')">
    <meta name="keywords" content="@yield('meta_keywords', $settings['meta_keywords'] ?? 'tai khoan gia re, tai khoan tiktok gia re, mua tai khoan chatgpt, tai khoan canva pro, tai khoan netflix gia re, tai khoan vpn gia re, phan mem ban quyen gia re, bao hanh tron goi')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    @php
        $ogImage = trim(View::yieldContent('og_image'));
        if (empty($ogImage)) {
            if (!empty($settings['logo_path'])) {
                $ogImage = asset($settings['logo_path']);
            } elseif (!empty($settings['favicon_path'])) {
                $ogImage = asset($settings['favicon_path']);
            } else {
                $ogImage = asset('images/og-banner.png');
            }
        }
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', !empty($settings['seo_title']) ? $settings['seo_title'] : ($settings['store_name'] ?? 'VPN Store Pro'))">
    <meta property="og:description" content="@yield('meta_description', $settings['meta_description'] ?? 'Mua phần mềm bản quyền giá tốt nhất')">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="{{ $settings['store_name'] ?? 'VPN Store Pro' }}">
    <meta property="og:locale" content="vi_VN">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', !empty($settings['seo_title']) ? $settings['seo_title'] : ($settings['store_name'] ?? 'VPN Store Pro'))">
    <meta name="twitter:description" content="@yield('meta_description', $settings['meta_description'] ?? 'Mua phần mềm bản quyền giá tốt nhất')">
    <meta name="twitter:image" content="{{ $ogImage }}">

    {{-- Favicon --}}
    @if(!empty($settings['favicon_path']))
        <link rel="icon" href="{{ asset($settings['favicon_path']) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%237c3aed'><path d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'/></svg>">
    @endif

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('head')
    @yield('json_ld')

    {{-- Global Structured Data --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "OnlineStore",
                "@@id": "{{ url('/') }}/#store",
                "name": "{{ $settings['store_name'] ?? 'VPNStore' }}",
                "description": "{{ $settings['meta_description'] ?? 'Cửa hàng phần mềm bản quyền uy tín - VPN Premium & Proxy' }}",
                "url": "{{ url('/') }}",
                "logo": "{{ !empty($settings['logo_path']) ? asset($settings['logo_path']) : asset('images/logo.png') }}",
                "currenciesAccepted": "VND",
                "priceRange": "10000VND - 5000000VND"
            },
            {
                "@@type": "WebSite",
                "@@id": "{{ url('/') }}/#website",
                "url": "{{ url('/') }}",
                "name": "{{ $settings['store_name'] ?? 'VPNStore' }}",
                "publisher": { "@@id": "{{ url('/') }}/#store" },
                "potentialAction": {
                    "@@type": "SearchAction",
                    "target": "{{ route('search') }}?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            }
        ]
    }
    </script>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar" id="main-navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        @if(!empty($settings['logo_path']))
            <div class="brand-icon" style="background:none; box-shadow:none;"><img src="{{ asset($settings['logo_path']) }}" alt="Logo" style="max-width:100%; max-height:100%; object-fit:contain;"></div>
        @else
            <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
        @endif
        <span>{{ $settings['store_name'] ?? 'VPNStore' }}</span>
    </a>

    <ul class="navbar-nav" id="nav-links">
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="bi bi-house-door"></i> Trang Chủ</a></li>
        <li class="dropdown" id="navProductDropdown">
            <a href="{{ route('products') }}" class="dropdown-toggle {{ request()->routeIs('products') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Sản Phẩm <i class="bi bi-chevron-down ms-1" style="font-size: 0.75rem;"></i>
            </a>
            <div class="dropdown-menu mega-menu-panel" style="width: 860px; max-width: 92vw; padding: 20px; left: 50%; transform: translateX(-50%); border-radius: 16px; background: var(--bg-elevated); border: 1px solid var(--border); box-shadow: 0 16px 40px rgba(0,0,0,0.4);">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--border);">
                    <a href="{{ route('products') }}" style="font-weight:700; color:var(--primary-light); text-decoration:none; font-size:0.92rem; display:inline-flex; align-items:center; gap:8px;">
                        <i class="bi bi-grid-fill"></i> Xem Tất Cả Sản Phẩm ({{ count($sharedCategories) }} danh mục)
                    </a>
                    <input type="text" id="megaMenuSearchInput" onkeyup="filterMegaMenuItems()" placeholder="Tìm nhanh danh mục..." style="padding:6px 14px; font-size:0.8rem; border-radius:20px; border:1px solid var(--border); background:var(--bg-input); color:var(--text-primary); outline:none; width:200px;">
                </div>
                <div class="mega-menu-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px 8px; max-height: 380px; overflow-y: auto; padding-right: 6px;" id="megaMenuList">
                    @foreach($sharedCategories as $cat)
                        @php
                            $icon = 'bi-box-seam-fill';
                            $slug = strtolower($cat->slug);
                            $type = strtolower($cat->type);
                            if (str_contains($slug, 'vpn') || $type === 'vpn') $icon = 'bi-shield-lock-fill';
                            elseif (str_contains($slug, 'chat') || str_contains($slug, 'gpt') || str_contains($slug, 'ai') || str_contains($slug, 'gemini') || str_contains($slug, 'grok') || str_contains($slug, 'claude') || str_contains($slug, 'cursor') || str_contains($slug, 'deepseek') || str_contains($slug, 'perplexity') || str_contains($slug, 'elevenlabs') || str_contains($slug, 'runway') || str_contains($slug, 'lovable') || str_contains($slug, 'qwen') || str_contains($slug, 'genspark') || str_contains($slug, 'kling') || str_contains($slug, 'krea') || str_contains($slug, 'openart') || str_contains($slug, 'leonardo') || str_contains($slug, 'heygen')) $icon = 'bi-cpu-fill';
                            elseif (str_contains($slug, 'canva') || str_contains($slug, 'design') || str_contains($slug, 'adobe') || str_contains($slug, 'photoshop') || str_contains($slug, 'figma') || str_contains($slug, 'freepik') || str_contains($slug, 'meitu') || str_contains($slug, 'xingtu')) $icon = 'bi-palette-fill';
                            elseif (str_contains($slug, 'youtube') || str_contains($slug, 'capcut') || str_contains($slug, 'netflix') || str_contains($slug, 'video') || str_contains($slug, 'phim') || str_contains($slug, 'tv-360') || str_contains($slug, 'galaxy') || str_contains($slug, 'tiktok') || str_contains($slug, 'spotify')) $icon = 'bi-play-btn-fill';
                            elseif (str_contains($slug, 'code') || str_contains($slug, 'intellij') || str_contains($slug, 'jetbrains') || str_contains($slug, 'replit') || str_contains($slug, 'gpm') || str_contains($slug, 'aws')) $icon = 'bi-code-slash';
                        @endphp
                        <a href="{{ route('products', ['category' => $cat->slug]) }}" class="dropdown-item mega-item" data-name="{{ strtolower($cat->name) }}" style="padding: 6px 8px; display: flex; align-items: center; gap: 8px; border-radius: 6px; font-size: 0.83rem; color:var(--text-secondary); text-decoration:none;">
                            @if(!empty($cat->image_url) && strlen($cat->image_url) > 5)
                                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" style="width: 16px; height: 16px; object-fit: contain; border-radius: 2px; flex-shrink: 0;" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline-block';">
                                <i class="bi {{ $icon }} text-primary" style="display:none; flex-shrink: 0;"></i>
                            @else
                                <i class="bi {{ $icon }} text-primary" style="flex-shrink: 0;"></i>
                            @endif
                            <span style="white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">{{ $cat->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </li>
        <li><a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}"><i class="bi bi-newspaper"></i> Bài Viết</a></li>
        <li><a href="{{ route('order.check') }}" class="{{ request()->routeIs('order.check') ? 'active' : '' }}"><i class="bi bi-search"></i> Tra Cứu Mã Đơn</a></li>
        <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i class="bi bi-envelope"></i> Liên Hệ</a></li>
        @if(auth()->check() && auth()->user()->isAdmin())
            <li><a href="{{ route('admin.dashboard') }}" style="color: var(--primary-light);"><i class="bi bi-speedometer2"></i> Admin</a></li>
        @endif
    </ul>

    <div class="navbar-actions">
        {{-- Search Input Pill --}}
        <form action="{{ route('search') }}" method="GET" class="nav-search-pill" onclick="if(window.innerWidth <= 768) { openSearchModal(); return false; }">
            <i class="bi bi-search search-icon"></i>
            <input type="text" name="q" placeholder="Tìm kiếm nhanh..." autocomplete="off">
        </form>

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
            <span class="cart-btn-text">Giỏ Hàng</span>
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
            <div class="auth-buttons-desktop">
                <a href="{{ route('auth.login') }}" class="btn btn-ghost btn-sm">Đăng Nhập</a>
                <a href="{{ route('auth.register') }}" class="btn btn-primary btn-sm">Đăng Ký</a>
            </div>
            <a href="{{ route('auth.login') }}" class="auth-button-mobile" title="Đăng Nhập / Đăng Ký">
                <i class="bi bi-person" style="font-size: 1.3rem;"></i>
            </a>
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
        <a href="{{ route('posts.index') }}" class="dropdown-item"><i class="bi bi-file-text" style="margin-right:8px;"></i> Bài Viết</a>
        <a href="{{ route('order.check') }}" class="dropdown-item"><i class="bi bi-search" style="margin-right:8px;"></i> Tra Cứu Mã Đơn</a>
        <a href="{{ route('contact') }}" class="dropdown-item"><i class="bi bi-envelope" style="margin-right:8px;"></i> Liên Hệ</a>
        <div class="dropdown-divider"></div>
        @if(auth()->check())
            <a href="{{ route('order.history') }}" class="dropdown-item"><i class="bi bi-clock-history" style="margin-right:8px;"></i> Lịch Sử Đơn Hàng</a>
            <a href="{{ route('wishlist.index') }}" class="dropdown-item"><i class="bi bi-heart" style="margin-right:8px;"></i> Yêu Thích</a>
        @else
            <a href="{{ route('auth.login') }}" class="dropdown-item"><i class="bi bi-box-arrow-in-right" style="margin-right:8px;"></i> Đăng Nhập</a>
            <a href="{{ route('auth.register') }}" class="dropdown-item"><i class="bi bi-person-plus" style="margin-right:8px;"></i> Đăng Ký</a>
        @endif
        <div class="dropdown-divider"></div>
        <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; padding:4px 10px 0; letter-spacing:0.5px;">Danh Mục Sản Phẩm</div>
        @foreach($sharedCategories as $cat)
            @php
                $icon = 'bi-shield-lock-fill';
                $slug = strtolower($cat->slug);
                $type = strtolower($cat->type);
                if (str_contains($slug, 'vpn') || $type === 'vpn') $icon = 'bi-shield-lock-fill';
                elseif (str_contains($slug, 'ai') || str_contains($slug, 'code')) $icon = 'bi-cpu-fill';
                elseif (str_contains($slug, 'design') || str_contains($slug, 'adobe') || str_contains($slug, 'canva')) $icon = 'bi-palette-fill';
                elseif (str_contains($slug, 'phim') || str_contains($slug, 'film') || str_contains($slug, 'movie') || str_contains($slug, 'youtube')) $icon = 'bi-play-btn-fill';
                elseif (str_contains($slug, 'proxy') || $type === 'proxy') $icon = 'bi-hdd-network-fill';
                else $icon = 'bi-folder-fill';
            @endphp
            <a href="{{ route('products', ['category' => $cat->slug]) }}" class="dropdown-item" style="display: flex; align-items: center; gap: 8px;">
                @if($cat->image_path)
                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" style="width: 16px; height: 16px; object-fit: contain; border-radius: 2px;">
                @else
                    <i class="bi {{ $icon }}"></i>
                @endif
                {{ $cat->name }}
            </a>
        @endforeach
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
<footer class="footer" itemscope itemtype="https://schema.org/WPFooter">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="navbar-brand" style="margin-bottom:0;">
                    @if(!empty($settings['logo_path']))
                        <div class="brand-icon" style="background:none; box-shadow:none;"><img src="{{ asset($settings['logo_path']) }}" alt="Logo {{ $settings['store_name'] ?? 'VPNStore' }}" style="max-width:100%; max-height:100%; object-fit:contain;"></div>
                    @else
                        <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
                    @endif
                    <span class="gradient-text" style="font-size:1.2rem; font-weight:800;">{{ $settings['store_name'] ?? 'VPNStore' }}</span>
                </div>
                <p>Cửa hàng tài khoản bản quyền giá rẻ uy tín #1 Việt Nam. Cung cấp tài khoản ChatGPT, TikTok, Canva, Netflix, CapCut, Spotify, VPN chính hãng với giá rẻ nhất và giao hàng tự động 24/7.</p>
                <div class="footer-social">
                    @if(!empty($settings['telegram_support']))
                    <a href="{{ $settings['telegram_url'] ?? 'https://t.me/' . ltrim($settings['telegram_support'],'@') }}" target="_blank" rel="noopener noreferrer" class="social-btn" title="Hỗ trợ qua Telegram" aria-label="Telegram"><i class="bi bi-telegram" style="font-size: 1.1rem;"></i></a>
                    @endif
                    @if(!empty($settings['zalo_support']))
                    <a href="{{ $settings['zalo_url_1'] ?? 'https://zalo.me/' . $settings['zalo_support'] }}" target="_blank" rel="noopener noreferrer" class="social-btn" title="Hỗ trợ qua Zalo" aria-label="Zalo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24" style="display:inline-block; vertical-align:middle;">
                            <path d="M12.49 10.2722v-.4496h1.3467v6.3218h-.7704a.576.576 0 01-.5763-.5729l-.0006.0005a3.273 3.273 0 01-1.9372.6321c-1.8138 0-3.2844-1.4697-3.2844-3.2823 0-1.8125 1.4706-3.2822 3.2844-3.2822a3.273 3.273 0 011.9372.6321l.0006.0005zM6.9188 7.7896v.205c0 .3823-.051.6944-.2995 1.0605l-.03.0343c-.0542.0615-.1815.206-.2421.2843L2.024 14.8h4.8948v.7682a.5764.5764 0 01-.5767.5761H0v-.3622c0-.4436.1102-.6414.2495-.8476L4.8582 9.23H.1922V7.7896h6.7266zm8.5513 8.3548a.4805.4805 0 01-.4803-.4798v-7.875h1.4416v8.3548H15.47zM20.6934 9.6C22.52 9.6 24 11.0807 24 12.9044c0 1.8252-1.4801 3.306-3.3066 3.306-1.8264 0-3.3066-1.4808-3.3066-3.306 0-1.8237 1.4802-3.3044 3.3066-3.3044zm-10.1412 5.253c1.0675 0 1.9324-.8645 1.9324-1.9312 0-1.065-.865-1.9295-1.9324-1.9295s-1.9324.8644-1.9324 1.9295c0 1.0667.865 1.9312 1.9324 1.9312zm10.1412-.0033c1.0737 0 1.945-.8707 1.945-1.9453 0-1.073-.8713-1.9436-1.945-1.9436-1.0753 0-1.945.8706-1.945 1.9436 0 1.0746.8697 1.9453 1.945 1.9453z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>

            <div>
                <h4 class="footer-heading">Quảng Cáo & Hợp Tác</h4>
                <ul class="footer-links" style="display:flex; flex-direction:column; gap:10px;">
                    <li>
                        <a href="javascript:void(0)" onclick="openFooterModal('adv')" title="Liên hệ đặt banner & quảng cáo">
                            <i class="bi bi-megaphone-fill text-warning me-2"></i> Liên Hệ Quảng Cáo
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" onclick="openFooterModal('partner')" title="Liên hệ hợp tác gán sản phẩm">
                            <i class="bi bi-box-arrow-in-right text-success me-2"></i> Liên Hệ Gán Sản Phẩm
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" onclick="openFooterModal('hours')" title="Xem giờ mở cửa & hỗ trợ">
                            <i class="bi bi-clock-fill text-info me-2"></i> Giờ Mở Cửa (24/24)
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Hỗ Trợ Khách Hàng</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('order.check') }}" title="Tra cứu mã đơn hàng tự động 24/7">Tra Cứu Đơn Hàng</a></li>
                    <li><a href="{{ route('posts.index') }}" title="Đọc tin tức & hướng dẫn VPN">Tin Tức & Hướng Dẫn</a></li>
                    <li><a href="{{ route('about') }}" title="Giới thiệu cửa hàng VPNStore">Giới Thiệu</a></li>
                    <li><a href="{{ route('contact') }}" title="Liên hệ kênh hỗ trợ trực tuyến">Liên Hệ Hỗ Trợ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Thông Tin Liên Hệ</h4>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    @if(!empty($settings['contact_email']))
                    <div style="display:flex; align-items:center; gap:10px; font-size:0.875rem; color:var(--text-muted);">
                        <i class="bi bi-envelope-fill" style="color: var(--primary-light); font-size: 1rem;"></i>
                        <a href="mailto:{{ $settings['contact_email'] }}" style="color:var(--text-muted); text-decoration:none;">{{ $settings['contact_email'] }}</a>
                    </div>
                    @endif
                    @if(!empty($settings['telegram_support']))
                    <div style="display:flex; align-items:center; gap:10px; font-size:0.875rem; color:var(--text-muted);">
                        <i class="bi bi-telegram" style="color: var(--primary-light); font-size: 1rem;"></i>
                        <a href="{{ $settings['telegram_url'] ?? 'https://t.me/' . ltrim($settings['telegram_support'],'@') }}" target="_blank" rel="noopener noreferrer" style="color:var(--text-muted); text-decoration:none;">Telegram: {{ '@' . ltrim($settings['telegram_support'],'@') }}</a>
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
                        <div style="padding:6px 12px; background:var(--bg-card); border:1px solid var(--border); border-radius:6px; font-size:0.75rem; color:var(--text-secondary);">ATM / Chuyển Khoản</div>
                        <div style="padding:6px 12px; background:var(--bg-card); border:1px solid var(--border); border-radius:6px; font-size:0.75rem; color:var(--text-secondary);">Momo</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copy">© {{ date('Y') }} <span>{{ $settings['store_name'] ?? 'VPNStore' }}</span>. Cửa Hàng VPN & Proxy Bản Quyền Số 1 Việt Nam.</p>
            <div style="display:flex; gap:20px;">
                <a href="#" onclick="event.preventDefault(); openSeoModal('terms')" style="font-size:0.8rem; color:var(--text-muted); transition:var(--transition);" title="Điều khoản dịch vụ">Điều Khoản</a>
                <a href="#" onclick="event.preventDefault(); openSeoModal('privacy')" style="font-size:0.8rem; color:var(--text-muted); transition:var(--transition);" title="Chính sách bảo mật">Bảo Mật</a>
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
    hamburger.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (mobileNav.style.display === 'flex') {
            mobileNav.style.display = 'none';
        } else {
            mobileNav.style.display = 'flex';
        }
    });
    if (closeNav) {
        closeNav.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            mobileNav.style.display = 'none';
        });
    }
    mobileNav.addEventListener('click', (e) => {
        if (e.target === mobileNav) {
            mobileNav.style.display = 'none';
        }
    });
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

{{-- ===== FLOATING ACTION BUBBLES & LIVE CHAT ===== --}}
<style>
.floating-widgets-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9990;
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: flex-end;
}
.float-bubble {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    border: none;
    cursor: pointer;
    outline: none;
}
.float-bubble:hover {
    transform: scale(1.12) translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    color: #fff;
}
.bubble-tooltip {
    position: absolute;
    right: 62px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(15, 23, 42, 0.9);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 6px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.float-bubble:hover .bubble-tooltip {
    opacity: 1;
    transform: translateY(-50%) translateX(-4px);
}
.bubble-zalo {
    background: linear-gradient(135deg, #0068ff, #0043a8);
}
.bubble-telegram {
    background: linear-gradient(135deg, #0088cc, #005580);
}
.bubble-livechat {
    background: linear-gradient(135deg, #7c3aed, #4c1d95);
    animation: livechat-pulse 2s infinite;
}
@keyframes livechat-pulse {
    0% { box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.6); }
    70% { box-shadow: 0 0 0 14px rgba(124, 58, 237, 0); }
    100% { box-shadow: 0 0 0 0 rgba(124, 58, 237, 0); }
}
.livechat-unread-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 800;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
}

#customer-livechat-modal {
    display: none;
    position: fixed;
    bottom: 86px;
    right: 24px;
    width: 360px;
    max-width: calc(100vw - 32px);
    height: 480px;
    max-height: calc(100vh - 120px);
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 20px 48px -12px rgba(0, 0, 0, 0.25), 0 0 1px rgba(0, 0, 0, 0.15);
    z-index: 9995;
    flex-direction: column;
    overflow: hidden;
    animation: floatUp 0.3s ease-out;
}
@keyframes floatUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.chat-modal-header {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #ffffff;
}
.chat-admin-status-dot {
    width: 10px;
    height: 10px;
    background: #22c55e;
    border-radius: 50%;
    box-shadow: 0 0 8px #22c55e;
}
.chat-close-btn {
    background: none;
    border: none;
    color: #ffffff;
    font-size: 1.4rem;
    cursor: pointer;
    line-height: 1;
    opacity: 0.85;
    transition: opacity 0.2s;
}
.chat-close-btn:hover { opacity: 1; }

#customer-chat-feed {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #f8fafc;
}
.chat-system-welcome {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #334155;
    font-size: 0.82rem;
    padding: 12px 14px;
    border-radius: 12px;
    line-height: 1.5;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.chat-msg-row {
    display: flex;
    flex-direction: column;
    max-width: 82%;
}
.chat-msg-row.customer { align-self: flex-end; }
.chat-msg-row.admin { align-self: flex-start; }
.chat-msg-sender {
    font-size: 0.7rem;
    color: #64748b;
    margin-bottom: 3px;
    font-weight: 500;
}
.chat-msg-row.customer .chat-msg-sender { text-align: right; }
.chat-msg-bubble {
    padding: 10px 14px;
    border-radius: 16px;
    font-size: 0.88rem;
    line-height: 1.45;
    word-break: break-word;
}
.chat-msg-row.customer .chat-msg-bubble {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #ffffff;
    border-bottom-right-radius: 3px;
    box-shadow: 0 3px 10px rgba(124, 58, 237, 0.25);
}
.chat-msg-row.admin .chat-msg-bubble {
    background: #ffffff;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 3px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}
.chat-msg-img {
    max-width: 100%;
    max-height: 180px;
    border-radius: 10px;
    margin-top: 6px;
    cursor: pointer;
    border: 1px solid #e2e8f0;
}

#customer-chat-form {
    padding: 12px 14px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
}
.chat-attach-btn {
    background: none;
    border: none;
    color: #64748b;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 4px;
    transition: color 0.2s;
}
.chat-attach-btn:hover { color: #7c3aed; }
#customer-chat-input {
    flex: 1;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 9px 16px;
    color: #0f172a;
    font-size: 0.88rem;
    outline: none;
    transition: all 0.2s ease;
}
#customer-chat-input::placeholder {
    color: #94a3b8;
    opacity: 1;
}
#customer-chat-input:focus {
    background: #ffffff;
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
}
.chat-send-btn {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #ffffff;
    border: none;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(124, 58, 237, 0.3);
    transition: all 0.2s ease;
}
.chat-send-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 5px 14px rgba(124, 58, 237, 0.4);
}

#customer-image-preview-bar {
    padding: 8px 14px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
}
#customer-preview-img {
    max-height: 50px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
}
.remove-img-btn {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ef4444;
    color: #ffffff;
    border: none;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 12px;
    line-height: 1;
    cursor: pointer;
}
</style>

<div class="floating-widgets-container">
    <!-- 1. Zalo Bubble -->
    <a href="https://zalo.me/0569012134" target="_blank" class="float-bubble bubble-zalo" title="Zalo: 0569012134">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.49 10.2722v-.4496h1.3467v6.3218h-.7704a.576.576 0 01-.5763-.5729l-.0006.0005a3.273 3.273 0 01-1.9372.6321c-1.8138 0-3.2844-1.4697-3.2844-3.2823 0-1.8125 1.4706-3.2822 3.2844-3.2822a3.273 3.273 0 011.9372.6321l.0006.0005zM6.9188 7.7896v.205c0 .3823-.051.6944-.2995 1.0605l-.03.0343c-.0542.0615-.1815.206-.2421.2843L2.024 14.8h4.8948v.7682a.5764.5764 0 01-.5767.5761H0v-.3622c0-.4436.1102-.6414.2495-.8476L4.8582 9.23H.1922V7.7896h6.7266zm8.5513 8.3548a.4805.4805 0 01-.4803-.4798v-7.875h1.4416v8.3548H15.47zM20.6934 9.6C22.52 9.6 24 11.0807 24 12.9044c0 1.8252-1.4801 3.306-3.3066 3.306-1.8264 0-3.3066-1.4808-3.3066-3.306 0-1.8237 1.4802-3.3044 3.3066-3.3044zm-10.1412 5.253c1.0675 0 1.9324-.8645 1.9324-1.9312 0-1.065-.865-1.9295-1.9324-1.9295s-1.9324.8644-1.9324 1.9295c0 1.0667.865 1.9312 1.9324 1.9312zm10.1412-.0033c1.0737 0 1.945-.8707 1.945-1.9453 0-1.073-.8713-1.9436-1.945-1.9436-1.0753 0-1.945.8706-1.945 1.9453 0 1.0746.8697 1.9453 1.945 1.9453z"/>
        </svg>
        <span class="bubble-tooltip">Zalo: 0569012134</span>
    </a>

    <!-- 2. Telegram Bubble -->
    <a href="https://t.me/specademy" target="_blank" class="float-bubble bubble-telegram" title="Telegram: @specademy">
        <i class="bi bi-telegram" style="font-size: 1.4rem;"></i>
        <span class="bubble-tooltip">Telegram: @specademy</span>
    </a>

    <!-- 3. Live Chat with Admin -->
    <button class="float-bubble bubble-livechat" id="livechat-trigger-btn" onclick="toggleCustomerLiveChat()" title="Chat trực tiếp với Admin">
        <i class="bi bi-chat-dots-fill" style="font-size: 1.3rem;"></i>
        <span class="livechat-unread-badge" id="livechat-badge" style="display:none;">0</span>
        <span class="bubble-tooltip">Chat Trực Tiếp Admin</span>
    </button>
</div>

<!-- LIVE CHAT MODAL POPUP -->
<div id="customer-livechat-modal">
    <div class="chat-modal-header">
        <div style="display:flex; align-items:center; gap:10px;">
            <div class="chat-admin-status-dot"></div>
            <div>
                <div style="font-weight:700; font-size:0.92rem; color:#fff;">Hỗ Trợ Trực Tuyến 24/7</div>
                <div style="font-size:0.72rem; color:rgba(255,255,255,0.85);">Giải đáp & Hỗ trợ ngay lập tức</div>
            </div>
        </div>
        <button onclick="toggleCustomerLiveChat()" class="chat-close-btn">&times;</button>
    </div>

    <div id="customer-chat-feed">
        <div class="chat-system-welcome">
            👋 Xin chào! Bạn cần hỗ trợ mua hàng hoặc gặp vấn đề gì hãy nhắn tin ở đây nhé, Admin hỗ trợ bạn ngay!
        </div>
    </div>

    <div id="customer-image-preview-bar" style="display:none;">
        <div style="position:relative; display:inline-block;">
            <img id="customer-preview-img" src="" alt="Preview">
            <button type="button" onclick="clearCustomerImage()" class="remove-img-btn">&times;</button>
        </div>
        <span id="customer-preview-filename" style="font-size:0.75rem; color:var(--text-muted,#94a3b8);"></span>
    </div>

    <!-- 5 Messages Limit Notice -->
    <div id="customer-chat-limit-notice" style="display:none; padding:8px 14px; background:#fff1f2; border-top:1px solid #ffe4e6; color:#e11d48; font-size:0.78rem; text-align:center; font-weight:600;">
        <i class="bi bi-exclamation-circle-fill me-1"></i> Bạn đã gửi 5 tin nhắn. Vui lòng chờ Admin phản hồi trước khi nhắn tiếp.
    </div>

    <form id="customer-chat-form" onsubmit="sendCustomerChatMessage(event)">
        <input type="file" id="customer-image-input" accept="image/*" style="display:none;" onchange="handleCustomerImageSelect(this)">
        <button type="button" class="chat-attach-btn" onclick="document.getElementById('customer-image-input').click()" title="Gửi hình ảnh">
            <i class="bi bi-image"></i>
        </button>
        <input type="text" id="customer-chat-input" placeholder="Nhập tin nhắn..." autocomplete="off">
        <button type="submit" class="chat-send-btn" id="customer-send-btn">
            <i class="bi bi-send-fill"></i>
        </button>
    </form>
</div>

<div id="customer-lightbox" onclick="closeCustomerLightbox()" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,0.85); align-items:center; justify-content:center; padding:16px;">
    <img id="customer-lightbox-img" src="" style="max-width:90vw; max-height:85vh; border-radius:8px; box-shadow:0 8px 32px rgba(0,0,0,0.5);">
</div>

<script>
let customerChatSessionId = localStorage.getItem('vpnstore_chat_session_id');
if (!customerChatSessionId) {
    customerChatSessionId = 'sess_' + Math.random().toString(36).substring(2, 11) + Date.now().toString(36);
    localStorage.setItem('vpnstore_chat_session_id', customerChatSessionId);
}

let lastKnownAdminMsgCount = 0;
let isChatOpen = false;
let selectedCustomerImageFile = null;
let isCooldownActive = false;
let lastRenderedHash = '';
let currentPollTimeout = null;

document.addEventListener('DOMContentLoaded', function() {
    pollCustomerMessages();
});

document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        pollCustomerMessages();
    }
});

function scheduleNextPoll() {
    if (currentPollTimeout) clearTimeout(currentPollTimeout);
    if (document.hidden) return; // Pause polling when tab is inactive

    const delay = isChatOpen ? 3000 : 8000;
    currentPollTimeout = setTimeout(pollCustomerMessages, delay);
}

function toggleCustomerLiveChat() {
    const modal = document.getElementById('customer-livechat-modal');
    if (!modal) return;

    if (modal.style.display === 'flex') {
        modal.style.display = 'none';
        isChatOpen = false;
        scheduleNextPoll();
    } else {
        modal.style.display = 'flex';
        isChatOpen = true;
        markCustomerMessagesRead();
        scrollFeedToBottom();
        scheduleNextPoll();
    }
}

function pollCustomerMessages() {
    if (!customerChatSessionId || document.hidden) return;

    fetch("{{ route('chat.messages', [], false) }}?session_id=" + encodeURIComponent(customerChatSessionId))
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const adminMsgs = data.messages.filter(m => m.sender_type === 'admin');
                const unreadAdminCount = data.unread_admin_count || 0;

                if (adminMsgs.length > lastKnownAdminMsgCount) {
                    if (lastKnownAdminMsgCount > 0) {
                        playCustomerAudioChime();
                    }
                    lastKnownAdminMsgCount = adminMsgs.length;
                }

                const badge = document.getElementById('livechat-badge');
                if (badge) {
                    if (unreadAdminCount > 0 && !isChatOpen) {
                        badge.textContent = unreadAdminCount;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }

                // Check 5 consecutive messages limit
                const limitNotice = document.getElementById('customer-chat-limit-notice');
                const chatInput = document.getElementById('customer-chat-input');
                const sendBtn = document.getElementById('customer-send-btn');
                const attachBtn = document.querySelector('.chat-attach-btn');

                if (data.can_send === false) {
                    if (limitNotice) limitNotice.style.display = 'block';
                    if (chatInput) {
                        chatInput.disabled = true;
                        chatInput.placeholder = "Đang chờ Admin phản hồi...";
                    }
                    if (sendBtn) sendBtn.disabled = true;
                    if (attachBtn) attachBtn.style.pointerEvents = 'none';
                } else {
                    if (limitNotice) limitNotice.style.display = 'none';
                    if (chatInput && !isCooldownActive) {
                        chatInput.disabled = false;
                        chatInput.placeholder = "Nhập tin nhắn...";
                    }
                    if (sendBtn && !isCooldownActive) sendBtn.disabled = false;
                    if (attachBtn) attachBtn.style.pointerEvents = 'auto';
                }

                renderCustomerChatFeed(data.messages);
            }
            scheduleNextPoll();
        })
        .catch(err => {
            console.error(err);
            scheduleNextPoll();
        });
}

function renderCustomerChatFeed(messages) {
    const feed = document.getElementById('customer-chat-feed');
    if (!feed) return;

    // DOM Render Guard: skip innerHTML rewrite if data hasn't changed
    const newHash = messages.map(m => m.id + '_' + m.is_read + '_' + (m.message || '')).join('|');
    if (newHash === lastRenderedHash) {
        return;
    }
    lastRenderedHash = newHash;

    const isAtBottom = feed.scrollHeight - feed.clientHeight <= feed.scrollTop + 50;

    let html = `<div class="chat-system-welcome">
        👋 Xin chào! Bạn cần hỗ trợ mua hàng hoặc gặp vấn đề gì hãy nhắn tin ở đây nhé, Admin hỗ trợ bạn ngay!
    </div>`;

    messages.forEach(m => {
        const isCustomer = m.sender_type === 'customer';
        const roleClass = isCustomer ? 'customer' : 'admin';
        const senderLabel = isCustomer ? 'Bạn' : (m.sender_name || 'Admin Support');

        let imgHtml = '';
        if (m.image_url) {
            imgHtml = `<div><img src="${m.image_url}" onclick="openCustomerLightbox('${m.image_url}')" class="chat-msg-img"></div>`;
        }

        let textHtml = m.message ? `<div>${escapeHtml(m.message)}</div>` : '';

        html += `
            <div class="chat-msg-row ${roleClass}">
                <div class="chat-msg-sender">${escapeHtml(senderLabel)} • ${m.created_at}</div>
                <div class="chat-msg-bubble">
                    ${textHtml}
                    ${imgHtml}
                </div>
            </div>
        `;
    });

    feed.innerHTML = html;

    if (isAtBottom || isChatOpen) {
        scrollFeedToBottom();
    }
}

function scrollFeedToBottom() {
    const feed = document.getElementById('customer-chat-feed');
    if (feed) feed.scrollTop = feed.scrollHeight;
}

function markCustomerMessagesRead() {
    fetch("{{ route('chat.mark-read', [], false) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ session_id: customerChatSessionId })
    }).then(() => {
        const badge = document.getElementById('livechat-badge');
        if (badge) badge.style.display = 'none';
    });
}

function handleCustomerImageSelect(input) {
    if (input.files && input.files[0]) {
        selectedCustomerImageFile = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('customer-preview-img').src = e.target.result;
            document.getElementById('customer-preview-filename').textContent = selectedCustomerImageFile.name;
            document.getElementById('customer-image-preview-bar').style.display = 'flex';
        };
        reader.readAsDataURL(selectedCustomerImageFile);
    }
}

function clearCustomerImage() {
    selectedCustomerImageFile = null;
    document.getElementById('customer-image-input').value = '';
    document.getElementById('customer-image-preview-bar').style.display = 'none';
}

function start5sCooldown() {
    isCooldownActive = true;
    const btn = document.getElementById('customer-send-btn');
    const input = document.getElementById('customer-chat-input');
    if (!btn) return;

    let seconds = 5;
    btn.disabled = true;
    btn.innerHTML = `<span style="font-size:0.75rem; font-weight:700;">${seconds}s</span>`;
    if (input) input.disabled = true;

    const timer = setInterval(() => {
        seconds--;
        if (seconds > 0) {
            btn.innerHTML = `<span style="font-size:0.75rem; font-weight:700;">${seconds}s</span>`;
        } else {
            clearInterval(timer);
            isCooldownActive = false;
            btn.innerHTML = `<i class="bi bi-send-fill"></i>`;
            pollCustomerMessages();
        }
    }, 1000);
}

function sendCustomerChatMessage(e) {
    e.preventDefault();
    if (isCooldownActive) return;

    const input = document.getElementById('customer-chat-input');
    const text = input.value.trim();

    if (!text && !selectedCustomerImageFile) return;

    const btn = document.getElementById('customer-send-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="width: 1rem; height: 1rem; border-width: 2px;"></span>';

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('session_id', customerChatSessionId);
    if (text) formData.append('message', text);
    if (selectedCustomerImageFile) formData.append('image', selectedCustomerImageFile);

    fetch("{{ route('chat.send', [], false) }}", {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            clearCustomerImage();
            scrollFeedToBottom();
            start5sCooldown();
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-fill"></i>';
            alert(data.message || 'Không thể gửi tin nhắn.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-fill"></i>';
        console.error(err);
    });
}

function openCustomerLightbox(url) {
    document.getElementById('customer-lightbox-img').src = url;
    document.getElementById('customer-lightbox').style.display = 'flex';
}

function closeCustomerLightbox() {
    document.getElementById('customer-lightbox').style.display = 'none';
}

function playCustomerAudioChime() {
    try {
        const AudioContextClass = window.AudioContext || window.webkitAudioContext;
        if (!AudioContextClass) return;
        const ctx = new AudioContextClass();

        const now = ctx.currentTime;

        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(880, now);
        gain1.gain.setValueAtTime(0.25, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(now);
        osc1.stop(now + 0.25);

        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(1174.66, now + 0.12);
        gain2.gain.setValueAtTime(0.3, now + 0.12);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(now + 0.12);
        osc2.stop(now + 0.45);
    } catch (e) {
        console.log("Audio chime error:", e);
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
</script>

<!-- Custom JS -->
<script>
    window.dbWishlist = @json(auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray() : null);
    window.csrfToken = '{{ csrf_token() }}';
    window.publicCoupons = @json($publicCoupons ?? []);
</script>
<script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
@yield('extra_js')
@stack('scripts')

@auth
<script>
    localStorage.removeItem('guest_session_start_time');
</script>
@else
{{-- Khóa Màn Hình Khách Vãng Lai Sau 5 Phút --}}
<div id="guestLockoutOverlay" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15, 23, 42, 0.96); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); z-index:99999999; flex-direction:column; align-items:center; justify-content:center; padding:20px; text-align:center;">
    <div style="max-width:460px; width:100%; background:var(--bg-card, #1e293b); border:1px solid rgba(255,255,255,0.15); border-radius:24px; padding:36px 28px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.7); animation: guestPulse 2s infinite ease-in-out;">
        <div style="width:72px; height:72px; background:rgba(239, 68, 68, 0.15); color:#ef4444; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:2.4rem; margin-bottom:20px; border:2px solid rgba(239, 68, 68, 0.3);">
            <i class="bi bi-lock-fill"></i>
        </div>
        <h3 style="font-weight:800; font-size:1.35rem; color:#ffffff; margin-bottom:12px;">Hết Thời Gian Trải Nghiệm</h3>
        <p style="font-size:0.9rem; color:#94a3b8; line-height:1.6; margin-bottom:24px;">
            Bạn đã sử dụng hết <strong>5 phút trải nghiệm miễn phí</strong> dành cho khách vãng lai. Vui lòng đăng nhập hoặc đăng ký tài khoản để tiếp tục xem sản phẩm và thực hiện giao dịch trên hệ thống!
        </p>

        <div style="display:flex; flex-direction:column; gap:12px;">
            <a href="{{ route('auth.login') }}" class="btn btn-primary btn-lg" style="width:100%; font-weight:700; padding:14px; border-radius:12px; font-size:0.95rem; display:flex; align-items:center; justify-content:center; gap:8px;">
                <i class="bi bi-box-arrow-in-right" style="font-size:1.2rem;"></i> Đăng Nhập Ngay
            </a>
            <a href="{{ route('auth.register') }}" class="btn btn-outline" style="width:100%; font-weight:700; padding:14px; border-radius:12px; font-size:0.95rem; display:flex; align-items:center; justify-content:center; gap:8px; border:1px solid rgba(255,255,255,0.2); color:#ffffff; background:rgba(255,255,255,0.05);">
                <i class="bi bi-person-plus-fill" style="font-size:1.2rem;"></i> Đăng Ký Tài Khoản Mới
            </a>
        </div>
    </div>
</div>

<style>
@keyframes guestPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.015); }
    100% { transform: scale(1); }
}
</style>

<script>
(function() {
    const LOCK_TIME_SECONDS = 300; // 5 phút = 300 giây
    let startTime = localStorage.getItem('guest_session_start_time');
    
    if (!startTime) {
        startTime = Date.now().toString();
        localStorage.setItem('guest_session_start_time', startTime);
    }
    
    function checkGuestLockout() {
        const elapsedSeconds = Math.floor((Date.now() - parseInt(startTime)) / 1000);
        if (elapsedSeconds >= LOCK_TIME_SECONDS) {
            triggerLockout();
        }
    }
    
    function triggerLockout() {
        const overlay = document.getElementById('guestLockoutOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            document.body.style.pointerEvents = 'none';
            overlay.style.pointerEvents = 'auto';
        }
    }
    
    // Check immediately on load
    checkGuestLockout();
    
    // Check every 1 second
    setInterval(checkGuestLockout, 1000);
    
    // Prevent closing via keyboard (ESC, Tab)
    window.addEventListener('keydown', function(e) {
        const elapsedSeconds = Math.floor((Date.now() - parseInt(startTime)) / 1000);
        if (elapsedSeconds >= LOCK_TIME_SECONDS) {
            if (e.key === 'Escape' || e.key === 'Tab' || e.keyCode === 27 || e.keyCode === 9) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
    }, true);
})();
</script>
@endauth

<!-- Footer Interactive Modals -->
<div id="footerModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.65); backdrop-filter:blur(5px); z-index:999999; align-items:center; justify-content:center; padding:16px;">
    <div style="width:100%; max-width:460px; padding:24px; border-radius:20px; background:var(--bg-card); border:1px solid var(--border); box-shadow:0 20px 50px rgba(0,0,0,0.5); position:relative; animation: modalPop 0.3s ease-out;">
        <button type="button" onclick="closeFooterModal()" style="position:absolute; top:14px; right:16px; background:rgba(255,255,255,0.08); border:none; color:var(--text-muted); font-size:1.2rem; cursor:pointer; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center;">&times;</button>
        <div id="footerModalBody"></div>
    </div>
</div>

<script>
function openFooterModal(type) {
    const modal = document.getElementById('footerModal');
    const body = document.getElementById('footerModalBody');
    if (!modal || !body) return;

    let html = '';
    const teleUrl = "{{ !empty($settings['telegram_support']) ? ($settings['telegram_url'] ?? 'https://t.me/' . ltrim($settings['telegram_support'],'@')) : '#' }}";
    const zaloUrl = "{{ !empty($settings['zalo_support']) ? ($settings['zalo_url_1'] ?? 'https://zalo.me/' . $settings['zalo_support']) : '#' }}";

    if (type === 'adv') {
        html = `
            <div style="text-align:center; margin-bottom:16px;">
                <div style="width:52px; height:52px; border-radius:50%; background:rgba(245, 158, 11, 0.15); color:#f59e0b; display:inline-flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:10px;"><i class="bi bi-megaphone-fill"></i></div>
                <h3 style="font-size:1.2rem; font-weight:700; color:var(--text-primary); margin:0;">Liên Hệ Quảng Cáo</h3>
            </div>
            <p style="font-size:0.875rem; color:var(--text-secondary); line-height:1.6; margin-bottom:20px; text-align:center;">
                Hệ thống VPNStore hỗ trợ hợp tác đặt Banner quảng cáo, PR dịch vụ tài khoản số & sản phẩm phần mềm với hàng chục ngàn lượt truy cập mỗi ngày.
            </p>
            <div style="display:flex; flex-direction:column; gap:10px;">
                <a href="${teleUrl}" target="_blank" class="btn btn-primary btn-full" style="display:flex; align-items:center; justify-content:center; gap:8px; padding:10px; font-weight:700;">
                    <i class="bi bi-telegram" style="font-size:1.1rem;"></i> Liên Hệ Qua Telegram
                </a>
                <a href="${zaloUrl}" target="_blank" class="btn btn-outline btn-full" style="display:flex; align-items:center; justify-content:center; gap:8px; padding:10px; font-weight:700;">
                    <i class="bi bi-chat-dots-fill" style="font-size:1.1rem; color:#0068ff;"></i> Liên Hệ Qua Zalo
                </a>
            </div>
        `;
    } else if (type === 'partner') {
        html = `
            <div style="text-align:center; margin-bottom:16px;">
                <div style="width:52px; height:52px; border-radius:50%; background:rgba(16, 185, 129, 0.15); color:#10b981; display:inline-flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:10px;"><i class="bi bi-box-arrow-in-right"></i></div>
                <h3 style="font-size:1.2rem; font-weight:700; color:var(--text-primary); margin:0;">Liên Hệ Gán Sản Phẩm</h3>
            </div>
            <p style="font-size:0.875rem; color:var(--text-secondary); line-height:1.6; margin-bottom:20px; text-align:center;">
                Bạn là Nhà Cung Cấp (Vendor/Reseller) muốn gán bán sản phẩm tự động trên VPNStore? Kết nối ngay với chúng tôi để phân phối sản phẩm.
            </p>
            <div style="display:flex; flex-direction:column; gap:10px;">
                <a href="${teleUrl}" target="_blank" class="btn btn-primary btn-full" style="display:flex; align-items:center; justify-content:center; gap:8px; padding:10px; font-weight:700; background:#10b981; border-color:#10b981;">
                    <i class="bi bi-telegram" style="font-size:1.1rem;"></i> Hợp Tác Qua Telegram
                </a>
                <a href="${zaloUrl}" target="_blank" class="btn btn-outline btn-full" style="display:flex; align-items:center; justify-content:center; gap:8px; padding:10px; font-weight:700;">
                    <i class="bi bi-chat-dots-fill" style="font-size:1.1rem; color:#0068ff;"></i> Hợp Tác Qua Zalo
                </a>
            </div>
        `;
    } else if (type === 'hours') {
        html = `
            <div style="text-align:center; margin-bottom:16px;">
                <div style="width:52px; height:52px; border-radius:50%; background:rgba(59, 130, 246, 0.15); color:#3b82f6; display:inline-flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:10px;"><i class="bi bi-clock-fill"></i></div>
                <h3 style="font-size:1.2rem; font-weight:700; color:var(--text-primary); margin:0;">Giờ Mở Cửa (24/24)</h3>
            </div>
            <div style="background:var(--bg-elevated); padding:16px; border-radius:12px; margin-bottom:20px; font-size:0.875rem; color:var(--text-secondary); display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span>⚡ Đặt hàng & Giao key:</span>
                    <strong style="color:#10b981; font-weight:700;">Tự động 24/7/365</strong>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span>💬 Hỗ trợ CSKH trực tuyến:</span>
                    <strong style="color:var(--primary-light); font-weight:700;">08:00 - 24:00 mỗi ngày</strong>
                </div>
            </div>
            <button type="button" onclick="closeFooterModal()" class="btn btn-primary btn-full" style="padding:10px; font-weight:700;">Đã Hiểu</button>
        `;
    }

    body.innerHTML = html;
    modal.style.display = 'flex';
}

function closeFooterModal() {
    const modal = document.getElementById('footerModal');
    if (modal) modal.style.display = 'none';
}

function filterMegaMenuItems() {
    const input = document.getElementById('megaMenuSearchInput');
    if (!input) return;
    const filter = input.value.toLowerCase().trim();
    const items = document.querySelectorAll('#megaMenuList .mega-item');
    items.forEach(item => {
        const name = item.dataset.name || '';
        item.style.display = name.includes(filter) ? 'flex' : 'none';
    });
}
</script>
</body>
</html>
