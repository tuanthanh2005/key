<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? 'VPNStore - Chuyên cung cấp VPN chính hãng với giá tốt nhất. Bảo hành 30 ngày, hỗ trợ 24/7.')">
    <meta name="keywords" content="@yield('meta_keywords', 'vpn gia re, mua vpn, nordvpn, expressvpn, surfshark, hma vpn, cyberghost, protonvpn, ipvanish, purevpn')">
    <meta name="robots" content="index, follow">
    <title>@yield('title', ($settings['store_name'] ?? 'VPNStore') . ' - Cửa Hàng VPN Chính Hãng')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ !empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ !empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico') }}" type="image/x-icon">

    <!-- Canonical Link -->
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="@yield('title', 'VPNStore - Cửa Hàng VPN Chính Hãng')">
    <meta property="og:description" content="@yield('meta_description', 'VPNStore - Chuyên cung cấp VPN chính hãng: HMA, Surfshark, NordVPN, ExpressVPN với giá tốt nhất. Bảo hành 30 ngày, hỗ trợ 24/7.')">
    <meta property="og:image" content="@yield('og_image', !empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico'))">
    <meta property="og:site_name" content="VPNStore">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="@yield('title', 'VPNStore - Cửa Hàng VPN Chính Hãng')">
    <meta name="twitter:description" content="@yield('meta_description', 'VPNStore - Chuyên cung cấp VPN chính hãng: HMA, Surfshark, NordVPN, ExpressVPN với giá tốt nhất. Bảo hành 30 ngày, hỗ trợ 24/7.')">
    <meta name="twitter:image" content="@yield('og_image', !empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico'))">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=1.22">
    @yield('extra_css')

    <!-- JSON-LD Structured Data -->
    @yield('json_ld')
</head>
<body>

<!-- TOP BAR -->
<div class="topbar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="topbar-text">
                    <i class="bi bi-shield-check me-1"></i>
                    {{ $settings['topbar_text'] ?? 'Bảo hành 30 ngày · Hỗ trợ 24/7 · Thanh toán an toàn' }}
                </span>
            </div>
            <div class="col-md-6 text-md-end">
                @if(!empty($settings['zalo_support']))
                <a href="{{ $settings['zalo_url_1'] ?? 'https://zalo.me/' . $settings['zalo_support'] }}" target="_blank" class="topbar-link me-3">
                    <i class="bi bi-telephone me-1"></i> Zalo 1: {{ $settings['zalo_support'] }}
                </a>
                @endif
                @if(!empty($settings['zalo_support_2']))
                <a href="{{ $settings['zalo_url_2'] ?? 'https://zalo.me/' . $settings['zalo_support_2'] }}" target="_blank" class="topbar-link me-3">
                    <i class="bi bi-telephone me-1"></i> Zalo 2: {{ $settings['zalo_support_2'] }}
                </a>
                @endif
                @if(!empty($settings['contact_email']))
                <a href="mailto:{{ $settings['contact_email'] }}" class="topbar-link">
                    <i class="bi bi-envelope me-1"></i> {{ $settings['contact_email'] }}
                </a>
                @endif
            </div>
        </div>
    </div>  
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-main sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="logo-icon me-2">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span class="logo-text">VPN<span class="logo-accent">Store</span></span>
                <small class="logo-sub d-block">Chính hãng · Uy tín</small>
            </div>
        </a>

        <!-- Mobile Quick Icons (visible on mobile, next to toggler) -->
        <div class="d-flex align-items-center gap-2 ms-auto me-2 d-lg-none">
            <!-- Search -->
            <button class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-search"></i>
            </button>
            <!-- Cart -->
            <a href="{{ route('cart') }}" class="btn btn-cart position-relative">
                <i class="bi bi-bag"></i>
                <span class="cart-badge" id="cart-count-mobile">0</span>
            </a>
            <!-- Auth Actions -->
            @auth
                <div class="dropdown">
                    <button class="btn btn-icon dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; margin-top: 10px;">
                        @if(auth()->user()->isAdmin())
                            <li>
                                <a class="dropdown-item fw-600 text-primary" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Trang Admin
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li>
                            <a class="dropdown-item fw-600" href="{{ route('order.history') }}">
                                <i class="bi bi-clock-history me-2 text-muted"></i>Lịch Sử Đơn Hàng
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-600" href="{{ route('wishlist.index') }}">
                                <i class="bi bi-heart me-2 text-danger"></i>Sản Phẩm Yêu Thích
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-600">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng Xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('auth.login') }}" class="btn btn-icon" title="Đăng Nhập">
                    <i class="bi bi-box-arrow-in-right"></i>
                </a>
            @endauth
        </div>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house me-1"></i>Trang Chủ
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('products*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-grid me-1"></i>Sản Phẩm
                    </a>
                    <ul class="dropdown-menu dropdown-menu-vpn">
                        <li><a class="dropdown-item" href="{{ route('products') }}"><i class="bi bi-shield-fill-check text-primary me-2"></i>Tất Cả VPN</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('products', ['brand' => 'nordvpn']) }}">
                            <span class="brand-dot nord me-2"></span>NordVPN</a></li>
                        <li><a class="dropdown-item" href="{{ route('products', ['brand' => 'expressvpn']) }}">
                            <span class="brand-dot express me-2"></span>ExpressVPN</a></li>
                        <li><a class="dropdown-item" href="{{ route('products', ['brand' => 'surfshark']) }}">
                            <span class="brand-dot surf me-2"></span>Surfshark</a></li>
                        <li><a class="dropdown-item" href="{{ route('products', ['brand' => 'hma']) }}">
                            <span class="brand-dot hma me-2"></span>HMA VPN</a></li>
                        <li><a class="dropdown-item" href="{{ route('products', ['brand' => 'cyberghost']) }}">
                            <span class="brand-dot cyber me-2"></span>CyberGhost</a></li>
                        <li><a class="dropdown-item" href="{{ route('products', ['brand' => 'purevpn']) }}">
                            <span class="brand-dot pure me-2"></span>PureVPN</a></li>
                        <li><a class="dropdown-item" href="{{ route('products', ['brand' => 'ipvanish']) }}">
                            <span class="brand-dot ipv me-2"></span>IPVanish</a></li>
                        <li><a class="dropdown-item" href="{{ route('products', ['brand' => 'protonvpn']) }}">
                            <span class="brand-dot proton me-2"></span>ProtonVPN</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}">
                        <i class="bi bi-tags me-1"></i>Bảng Giá
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        <i class="bi bi-info-circle me-1"></i>Giới Thiệu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        <i class="bi bi-headset me-1"></i>Liên Hệ
                    </a>
                </li>
            </ul>

            <!-- Right Side (Desktop only) -->
            <div class="d-none d-lg-flex align-items-center gap-2">
                <!-- Search -->
                <button class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#searchModal" id="search-btn">
                    <i class="bi bi-search"></i>
                </button>
                <!-- Cart -->
                <a href="{{ route('cart') }}" class="btn btn-cart position-relative" id="cart-btn">
                    <i class="bi bi-bag"></i>
                    <span class="cart-badge" id="cart-count">0</span>
                </a>
                <!-- Order Check -->
                <a href="{{ route('order.check') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="bi bi-box-seam me-1"></i>Tra Đơn
                </a>
                
                <!-- Auth Actions -->
                @auth
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span class="text-truncate" style="max-width: 80px;">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; margin-top: 10px;">
                            @if(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item fw-600 text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Trang Admin
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item fw-600" href="{{ route('order.history') }}">
                                    <i class="bi bi-clock-history me-2 text-muted"></i>Lịch Sử Đơn Hàng
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item fw-600" href="{{ route('wishlist.index') }}">
                                    <i class="bi bi-heart me-2 text-danger"></i>Sản Phẩm Yêu Thích
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-600">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng Xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('auth.login') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Đăng Nhập
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- SEARCH MODAL -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content search-modal-content">
            <div class="modal-body p-4">
                <div class="search-modal-header mb-3">
                    <h5 class="mb-1"><i class="bi bi-search me-2 text-primary"></i>Tìm Kiếm Sản Phẩm</h5>
                    <p class="text-muted small mb-0">Tìm kiếm theo tên VPN, tính năng...</p>
                </div>
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group input-group-lg search-input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0"
                            placeholder="Nhập tên VPN (NordVPN, ExpressVPN...)"
                            id="search-input" autocomplete="off">
                        <button class="btn btn-primary px-4" type="submit">Tìm</button>
                    </div>
                </form>
                <div class="mt-3">
                    <p class="small text-muted mb-2">Tìm kiếm phổ biến:</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('search', ['q' => 'nordvpn']) }}" class="badge-search">NordVPN</a>
                        <a href="{{ route('search', ['q' => 'expressvpn']) }}" class="badge-search">ExpressVPN</a>
                        <a href="{{ route('search', ['q' => 'surfshark']) }}" class="badge-search">Surfshark</a>
                        <a href="{{ route('search', ['q' => 'hma']) }}" class="badge-search">HMA VPN</a>
                        <a href="{{ route('search', ['q' => '1 nam']) }}" class="badge-search">Gói 1 năm</a>
                        <a href="{{ route('search', ['q' => 'gia re']) }}" class="badge-search">Giá rẻ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<main id="main-content">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="footer mt-auto">
    <div class="footer-top">
        <div class="container">
            <div class="row g-4">
                <!-- Brand -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="logo-icon-sm me-2">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <span class="footer-logo-text">VPN<span class="text-primary">Store</span></span>
                        </div>
                        <p class="footer-desc">
                            Chuyên cung cấp các gói VPN chính hãng từ các thương hiệu uy tín hàng đầu thế giới.
                            Cam kết 100% bản quyền, giá tốt nhất thị trường.
                        </p>
                        <div class="d-flex gap-2 mt-3">
                            <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-btn"><i class="bi bi-telegram"></i></a>
                            <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
                            <a href="#" class="social-btn"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-title">Liên Kết</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Trang Chủ</a></li>
                        <li><a href="{{ route('products') }}">Sản Phẩm</a></li>
                        <li><a href="{{ route('pricing') }}">Bảng Giá</a></li>
                        <li><a href="{{ route('about') }}">Giới Thiệu</a></li>
                        <li><a href="{{ route('contact') }}">Liên Hệ</a></li>
                    </ul>
                </div>

                <!-- VPN Brands -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-title">Thương Hiệu</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('products', ['brand' => 'nordvpn']) }}">NordVPN</a></li>
                        <li><a href="{{ route('products', ['brand' => 'expressvpn']) }}">ExpressVPN</a></li>
                        <li><a href="{{ route('products', ['brand' => 'surfshark']) }}">Surfshark</a></li>
                        <li><a href="{{ route('products', ['brand' => 'hma']) }}">HMA VPN</a></li>
                        <li><a href="{{ route('products', ['brand' => 'cyberghost']) }}">CyberGhost</a></li>
                        <li><a href="{{ route('products', ['brand' => 'purevpn']) }}">PureVPN</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-lg-4 col-md-6">
                    <h6 class="footer-title">Hỗ Trợ & Liên Hệ</h6>
                    <ul class="footer-contact-list">
                        <li>
                            <i class="bi bi-chat-dots-fill"></i>
                            @if(!empty($settings['zalo_support']))
                            <span>Zalo 1: <a href="{{ $settings['zalo_url_1'] ?? 'https://zalo.me/' . $settings['zalo_support'] }}" target="_blank">{{ $settings['zalo_support'] }}</a></span>
                            @endif
                        </li>
                        <li>
                            <i class="bi bi-chat-dots-fill"></i>
                            @if(!empty($settings['zalo_support_2']))
                            <span>Zalo 2: <a href="{{ $settings['zalo_url_2'] ?? 'https://zalo.me/' . $settings['zalo_support_2'] }}" target="_blank">{{ $settings['zalo_support_2'] }}</a></span>
                            @endif
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            @if(!empty($settings['contact_email']))
                            <span>Email: <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a></span>
                            @endif
                        </li>
                        <li>
                            <i class="bi bi-telegram"></i>
                            @if(!empty($settings['telegram_support']))
                            <span>Telegram: <a href="{{ $settings['telegram_url'] ?? 'https://t.me/' . ltrim($settings['telegram_support'],'@') }}" target="_blank">{{ '@' . ltrim($settings['telegram_support'],'@') }}</a></span>
                            @endif
                        </li>
                        <li>
                            <i class="bi bi-clock-fill"></i>
                            <span>Hỗ trợ: 24/7 mọi lúc mọi nơi</span>
                        </li>
                        <li>
                            <i class="bi bi-shield-check-fill"></i>
                            <span>Bảo hành: 30 ngày đổi trả</span>
                        </li>
                    </ul>
                    <!-- Trust Badges -->
                    <div class="trust-badges mt-3">
                        <span class="trust-badge"><i class="bi bi-patch-check-fill me-1"></i>Chính hãng</span>
                        <span class="trust-badge"><i class="bi bi-lock-fill me-1"></i>Bảo mật</span>
                        <span class="trust-badge"><i class="bi bi-arrow-repeat me-1"></i>30 ngày hoàn</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 small">© {{ date('Y') }} {{ $settings['footer_copyright'] ?? ($settings['store_name'] ?? 'VPNStore') . '. Tất cả quyền được bảo lưu.' }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="payment-methods">
                        <span class="pay-icon"><i class="bi bi-credit-card"></i> Visa/Master</span>
                        <span class="pay-icon"><i class="bi bi-bank"></i> ATM</span>
                        <span class="pay-icon"><i class="bi bi-phone"></i> Momo</span>
                        <span class="pay-icon"><i class="bi bi-wallet2"></i> ZaloPay</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- BACK TO TOP -->
<button class="btn-back-top" id="backToTop" title="Lên đầu trang">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- TOAST NOTIFICATION -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="mainToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                <i class="bi bi-check-circle me-2"></i>Thêm vào giỏ thành công!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script>
    window.dbWishlist = @json(auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray() : null);
    window.csrfToken = '{{ csrf_token() }}';
</script>
<script src="{{ asset('js/app.js') }}?v=1.24"></script>
@yield('extra_js')
</body>
</html>
