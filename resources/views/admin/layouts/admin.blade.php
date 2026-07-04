<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin') — {{ $settings['store_name'] ?? 'VPNStore' }} Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=1.12">
</head>
<body class="admin-body">

<!-- SIDEBAR -->
<aside class="admin-sidebar" id="adminSidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div>
            <div class="sidebar-logo-text">{{ $settings['store_name'] ?? 'VPNStore' }}</div>
            <div class="sidebar-logo-sub">Admin Panel</div>
        </div>
        <button class="sidebar-close d-lg-none" onclick="toggleSidebar()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Admin Info -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover">
            @else
                {{ auth()->user()->getAvatarInitial() }}
            @endif
        </div>
        <div class="flex-grow-1 overflow-hidden">
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-role">
                <span class="admin-role-badge">
                    <i class="bi bi-shield-check me-1"></i>Admin
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">TỔNG QUAN</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
            <span class="sidebar-badge badge-blue">Live</span>
        </a>

        <div class="sidebar-section-label mt-2">QUẢN LÝ</div>
        <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check-fill"></i>
            <span>Đơn Hàng</span>
            @php $pendingCount = \App\Models\Order::where('order_status','pending')->count(); @endphp
            @if($pendingCount > 0)
            <span class="sidebar-badge badge-orange">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-shield-fill-check"></i>
            <span>Sản Phẩm VPN</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Người Dùng</span>
        </a>

        <div class="sidebar-section-label mt-2">HỆ THỐNG</div>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i>
            <span>Cài Đặt</span>
        </a>
        <a href="{{ route('admin.coupons.index') }}" class="sidebar-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i>
            <span>Mã Coupon</span>
            @php $activeCoupons = \App\Models\Coupon::valid()->count(); @endphp
            @if($activeCoupons > 0)
            <span class="sidebar-badge badge-blue">{{ $activeCoupons }}</span>
            @endif
        </a>
        <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
            <i class="bi bi-shop"></i>
            <span>Xem Cửa Hàng</span>
            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size:10px;opacity:.5"></i>
        </a>
        <a href="#" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#settingsModal">
            <i class="bi bi-gear-fill"></i>
            <span>Cài Đặt</span>
        </a>

        <div class="mt-auto pt-3 border-top" style="border-color:rgba(255,255,255,.08)!important">
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-100 text-start" style="background:none;border:none;color:rgba(255,255,255,.55);">
                    <i class="bi bi-box-arrow-left" style="color:#f87171"></i>
                    <span>Đăng Xuất</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<!-- SIDEBAR OVERLAY (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- MAIN CONTENT -->
<div class="admin-main">

    <!-- TOP HEADER -->
    <header class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="topbar-menu-btn d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <div class="topbar-title">@yield('page_title', 'Dashboard')</div>
                <div class="topbar-breadcrumb">@yield('breadcrumb', 'Admin / Dashboard')</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <!-- Quick Search -->
            <div class="topbar-search d-none d-md-flex">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Tìm kiếm nhanh...">
            </div>

            <!-- Notifications -->
            <div class="dropdown">
                <button class="topbar-icon-btn" data-bs-toggle="dropdown">
                    <i class="bi bi-bell-fill"></i>
                    <span class="topbar-notif-dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg p-0" style="width:320px;border-radius:16px;border:1px solid var(--admin-border);overflow:hidden">
                    <div class="p-3 border-bottom" style="background:var(--admin-sidebar);color:#fff">
                        <div class="fw-700" style="font-size:14px">Thông Báo</div>
                        <div style="font-size:12px;opacity:.6">3 thông báo mới</div>
                    </div>
                    @foreach([['Đơn hàng #VPN12345 mới','2 phút trước','bi-bag-plus-fill','#2563eb'],['Người dùng mới đăng ký','15 phút trước','bi-person-plus-fill','#10b981'],['Key VPN đã hết hạn cảnh báo','1 giờ trước','bi-exclamation-triangle-fill','#f59e0b']] as [$msg,$time,$icon,$color])
                    <div class="d-flex gap-3 p-3 border-bottom notif-item">
                        <div style="width:36px;height:36px;background:{{ $color }}18;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi {{ $icon }}" style="color:{{ $color }};font-size:15px"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--admin-text)">{{ $msg }}</div>
                            <div style="font-size:11.5px;color:var(--admin-muted)">{{ $time }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- User Menu -->
            <div class="dropdown">
                <button class="topbar-avatar" data-bs-toggle="dropdown">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover">
                    @else
                        {{ auth()->user()->getAvatarInitial() }}
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="border-radius:12px;border:1px solid var(--admin-border);min-width:180px">
                    <li><h6 class="dropdown-header">{{ auth()->user()->name }}</h6></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Hồ Sơ</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-left me-2"></i>Đăng Xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="admin-content">
        @if(session('success'))
        <div class="admin-alert admin-alert-success mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button class="admin-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
        @endif
        @if(session('error'))
        <div class="admin-alert admin-alert-danger mb-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button class="admin-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="modal-content" style="border-radius:16px;border:1px solid var(--admin-border)">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700"><i class="bi bi-gear-fill text-primary me-2"></i>Cài Đặt Hệ Thống</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- General Settings -->
                    <div class="col-md-6 border-end">
                        <h6 class="fw-800 text-primary mb-3">Cấu Hình Chung</h6>
                        <div class="mb-3">
                            <label class="form-label fw-600">Tên Cửa Hàng</label>
                            <input type="text" name="store_name" class="form-control" value="{{ \App\Models\Setting::get('store_name', 'VPNStore') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Email Liên Hệ</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ \App\Models\Setting::get('contact_email', 'tetuongmmovn@gmail.com') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Telegram Support</label>
                            <input type="text" name="telegram_support" class="form-control" value="{{ \App\Models\Setting::get('telegram_support', '@specademy') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Zalo Support</label>
                            <input type="text" name="zalo_support" class="form-control" value="{{ \App\Models\Setting::get('zalo_support', '0708910952') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Chế Độ Bảo Trì</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="maintenance_mode" value="0">
                                <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" id="maintenanceMode" {{ \App\Models\Setting::get('maintenance_mode', '0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="maintenanceMode">Tắt cửa hàng để bảo trì</label>
                            </div>
                        </div>
                    </div>

                    <!-- Fake Sales Settings -->
                    <div class="col-md-6">
                        <h6 class="fw-800 text-primary mb-3">Số Lượt Bán Giả Lập (Fake Sales)</h6>
                        <p class="text-muted small mb-3">Cấu hình hiển thị số lượng đã bán (ví dụ: 500+, 100+) hiển thị trên trang chủ và trang chi tiết sản phẩm.</p>
                        @foreach([
                            'sales_nordvpn' => 'NordVPN',
                            'sales_expressvpn' => 'ExpressVPN',
                            'sales_surfshark' => 'Surfshark',
                            'sales_hma' => 'HMA VPN',
                            'sales_cyberghost' => 'CyberGhost',
                            'sales_purevpn' => 'PureVPN',
                            'sales_ipvanish' => 'IPVanish',
                            'sales_protonvpn' => 'ProtonVPN'
                        ] as $key => $label)
                        <div class="mb-2 row align-items-center">
                            <label class="col-sm-5 col-form-label fw-600 small">{{ $label }}</label>
                            <div class="col-sm-7">
                                <input type="text" name="{{ $key }}" class="form-control form-control-sm" value="{{ \App\Models\Setting::get($key, '50+') }}" placeholder="Ví dụ: 100+">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu Cài Đặt</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/admin.js') }}"></script>
@yield('extra_js')

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
    document.body.classList.toggle('sidebar-open');
}
</script>
</body>
</html>
