@php
$brandMap = [];
if (isset($sharedCategories) && $sharedCategories->isNotEmpty()) {
    foreach ($sharedCategories as $cat) {
        $brandMap[$cat->slug] = [
            'name' => $cat->name,
            'seo_title' => $cat->seo_title ?: ('Mua Tài Khoản ' . $cat->name . ' Bản Quyền Giá Rẻ')
        ];
    }
} else {
    $brandMap = [
        'nordvpn' => ['name' => 'NordVPN Premium', 'seo_title' => 'Đăng Ký Tài Khoản NordVPN Premium Bản Quyền'],
        'expressvpn' => ['name' => 'ExpressVPN', 'seo_title' => 'Mua Tài Khoản ExpressVPN Chính Hãng Giá Tốt'],
        'surfshark' => ['name' => 'Surfshark VPN', 'seo_title' => 'Dịch Vụ Surfshark VPN Bản Quyền Giá Rẻ'],
        'hma' => ['name' => 'Key HMA VPN', 'seo_title' => 'Mua Key HMA VPN Bản Quyền Kích Hoạt Nhanh'],
        'cyberghost' => ['name' => 'CyberGhost VPN', 'seo_title' => 'Tài Khoản CyberGhost VPN Giá Rẻ Uy Tín'],
        'purevpn' => ['name' => 'PureVPN', 'seo_title' => 'Mua Tài Khoản PureVPN Bản Quyền Giá Tốt'],
        'ipvanish' => ['name' => 'IPVanish VPN', 'seo_title' => 'Đăng Ký IPVanish VPN Chính Hãng Giá Rẻ'],
        'protonvpn' => ['name' => 'ProtonVPN', 'seo_title' => 'Mua Tài Khoản ProtonVPN Giá Rẻ Bảo Hành 1 Đổi 1'],
    ];
}

$selectedBrandSlug = request('brand', '');
$brandInfo = null;
if ($selectedBrandSlug) {
    $normalizedSlug = strtolower($selectedBrandSlug);
    if (isset($brandMap[$normalizedSlug])) {
        $brandInfo = $brandMap[$normalizedSlug];
    } else {
        $displayName = ucwords(str_replace('-', ' ', $selectedBrandSlug));
        $brandInfo = [
            'name' => $displayName,
            'seo_title' => 'Mua Tài Khoản ' . $displayName . ' Bản Quyền Giá Rẻ'
        ];
    }
}

$storeName = $settings['store_name'] ?? 'VPNStore';
if ($brandInfo) {
    $pageTitle = $brandInfo['seo_title'] . ' - ' . $storeName;
    $pageMetaDesc = 'Mua tài khoản ' . $brandInfo['name'] . ' bản quyền chính hãng tại ' . $storeName . '. Cung cấp các gói ' . $brandInfo['name'] . ' giá tốt nhất thị trường, cam kết kích hoạt nhanh, hỗ trợ kỹ thuật trọn đời và bảo hành uy tín 1 đổi 1.';
} else {
    $pageTitle = 'Sản Phẩm - ' . $storeName;
    $pageMetaDesc = 'Danh sách tất cả VPN & Proxy chính hãng: NordVPN, ExpressVPN, Surfshark, HMA, CyberGhost, PureVPN, IPVanish, ProtonVPN giá tốt nhất Việt Nam.';
}
@endphp

@extends('layouts.app')

@section('title', $pageTitle)
@section('meta_description', $pageMetaDesc)

@section('content')

<!-- BREADCRUMB -->
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Trang Chủ</a></li>
                <li class="breadcrumb-item active">Sản Phẩm</li>
            </ol>
        </nav>
    </div>
</div>

<!-- PAGE HEADER -->
<div class="page-header pb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                @if($brandInfo)
                    <h1 class="section-title mb-2" style="font-size: 28px;">
                        <i class="bi bi-shield-fill-check text-primary me-3"></i>
                        {{ $brandInfo['seo_title'] }}
                    </h1>
                    <p class="text-muted mb-3" style="font-size: 15px; line-height: 1.6;">
                        {{ $pageMetaDesc }}
                    </p>
                @else
                    <h1 class="section-title mb-2">
                        <i class="bi bi-shield-fill-check text-primary me-3"></i>
                        Tất Cả Sản Phẩm
                    </h1>
                    <p class="text-muted mb-3">
                        Hơn <strong>{{ count($allProducts ?? []) ?: 24 }} sản phẩm</strong> đang có sẵn với giá tốt nhất
                    </p>
                @endif
            </div>
        </div>

        <!-- Tag Cloud of Categories with expand option on mobile -->
        <div class="brand-tags-wrapper" style="position: relative;">
            <div id="brandTagsList" class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top brand-tags-list" style="border-color: rgba(226, 232, 240, 0.6) !important;">
                @foreach($brandMap as $slug => $info)
                    <a href="{{ route('products') }}?brand={{ $slug }}" 
                       class="btn btn-sm rounded-pill px-3 py-2 fw-600 d-inline-flex align-items-center gap-2 transition-all {{ $selectedBrandSlug === $slug ? 'btn-primary shadow-sm' : '' }}" 
                       style="font-size: 13px; text-decoration: none; {{ $selectedBrandSlug !== $slug ? 'background: #fff; color: var(--gray-700); border: 1px solid #e2e8f0;' : '' }}">
                        <span style="width: 7px; height: 7px; background: {{ ['nordvpn'=>'#4687FF','expressvpn'=>'#DA3940','surfshark'=>'#10B981','hma'=>'#F59E0B','cyberghost'=>'#8B5CF6','purevpn'=>'#EF4444','ipvanish'=>'#0EA5E9','protonvpn'=>'#6D28D9'][$slug] ?? '#64748b' }}; border-radius: 50%;"></span>
                        {{ $info['name'] }}
                    </a>
                @endforeach
                @if($selectedBrandSlug)
                    <a href="{{ route('products') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-2 fw-600 d-inline-flex align-items-center gap-1.5" style="font-size: 13px; text-decoration: none;">
                        <i class="bi bi-x-circle-fill"></i> Xóa Bộ Lọc
                    </a>
                @endif
            </div>
            
            <!-- Toggle Button (only on Mobile) -->
            <div class="text-center mt-2 d-md-none" id="brandTagsToggleContainer">
                <button type="button" class="btn btn-link text-primary p-0 fw-700 text-decoration-none d-inline-flex align-items-center gap-1" id="btnToggleBrandTags" onclick="toggleBrandTags()" style="font-size: 12.5px;">
                    Xem thêm <i class="bi bi-chevron-down" id="arrowToggleBrandTags" style="font-size: 11px;"></i>
                </button>
            </div>
        </div>

        <style>
            .brand-tags-list {
                max-height: 88px;
                overflow: hidden;
                transition: max-height 0.3s ease-in-out;
            }
            @media (min-width: 768px) {
                .brand-tags-list {
                    max-height: none !important;
                    overflow: visible !important;
                }
            }
        </style>

        <script>
            function toggleBrandTags() {
                var container = document.getElementById('brandTagsList');
                var btn = document.getElementById('btnToggleBrandTags');
                
                if (container.style.maxHeight === '88px' || container.style.maxHeight === '') {
                    container.style.maxHeight = '1000px';
                    btn.innerHTML = 'Thu gọn <i class="bi bi-chevron-up" id="arrowToggleBrandTags" style="font-size: 11px;"></i>';
                } else {
                    container.style.maxHeight = '88px';
                    btn.innerHTML = 'Xem thêm <i class="bi bi-chevron-down" id="arrowToggleBrandTags" style="font-size: 11px;"></i>';
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                var container = document.getElementById('brandTagsList');
                var toggleContainer = document.getElementById('brandTagsToggleContainer');
                if (container && toggleContainer) {
                    // Hide toggle button if all tags already fit within 2 rows (<= 88px scrollHeight)
                    if (container.scrollHeight <= 90) {
                        toggleContainer.style.display = 'none';
                    }
                }
            });
        </script>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">

        <div class="col-lg-3">
            <button class="btn btn-outline-primary w-100 d-lg-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                <i class="bi bi-funnel me-2"></i>Bộ Lọc & Tìm Kiếm
            </button>
            <div class="collapse d-lg-block" id="filterCollapse">
                <div class="filter-sidebar">
                <div class="filter-header">
                    <h6><i class="bi bi-funnel me-2 text-primary"></i>Bộ Lọc</h6>
                    <button class="btn btn-sm text-primary p-0" style="font-size:12.5px;font-weight:600" onclick="resetFilters()">Đặt lại</button>
                </div>

                <!-- Search -->
                <div class="filter-section">
                    <div class="filter-section-title">Tìm Kiếm</div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="productSearch" class="form-control border-start-0 ps-0" placeholder="Tên sản phẩm...">
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="filter-section">
                    <div class="filter-section-title">Thương Hiệu</div>
                    @php
                    $brands = [];
                    if (isset($sharedCategories) && $sharedCategories->isNotEmpty()) {
                        foreach ($sharedCategories as $cat) {
                            $count = collect($allProducts)->filter(function($p) use ($cat) {
                                return ($p['category_id'] ?? null) == $cat->id || strtolower($p['slug']) == strtolower($cat->slug);
                            })->count();
                            $brands[] = [
                                'slug' => $cat->slug,
                                'name' => $cat->name,
                                'count' => $count,
                                'color' => ['nordvpn'=>'#4687FF','expressvpn'=>'#DA3940','surfshark'=>'#10B981','hma'=>'#F59E0B','cyberghost'=>'#8B5CF6','purevpn'=>'#EF4444','ipvanish'=>'#0EA5E9','protonvpn'=>'#6D28D9'][$cat->slug] ?? '#64748b'
                            ];
                        }
                    } else {
                        $brands = [
                            ['slug'=>'nordvpn','name'=>'NordVPN','count'=>6,'color'=>'#4687FF'],
                            ['slug'=>'expressvpn','name'=>'ExpressVPN','count'=>4,'color'=>'#DA3940'],
                            ['slug'=>'surfshark','name'=>'Surfshark','count'=>4,'color'=>'#10B981'],
                            ['slug'=>'hma','name'=>'HMA VPN','count'=>3,'color'=>'#F59E0B'],
                            ['slug'=>'cyberghost','name'=>'CyberGhost','count'=>3,'color'=>'#8B5CF6'],
                            ['slug'=>'purevpn','name'=>'PureVPN','count'=>2,'color'=>'#EF4444'],
                            ['slug'=>'ipvanish','name'=>'IPVanish','count'=>2,'color'=>'#0EA5E9'],
                            ['slug'=>'protonvpn','name'=>'ProtonVPN','count'=>2,'color'=>'#6D28D9'],
                        ];
                    }
                    $selectedBrand = request('brand','');
                    @endphp
                    @foreach($brands as $brand)
                    <div class="filter-check">
                        <input type="checkbox" id="brand_{{ $brand['slug'] }}"
                            class="brand-filter"
                            data-brand="{{ $brand['slug'] }}"
                            {{ $selectedBrand === $brand['slug'] ? 'checked' : '' }}>
                        <label for="brand_{{ $brand['slug'] }}" class="d-flex align-items-center gap-2">
                            <span style="width:8px;height:8px;background:{{ $brand['color'] }};border-radius:50%;display:inline-block;flex-shrink:0"></span>
                            {{ $brand['name'] }}
                        </label>
                        <span class="count">{{ $brand['count'] }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Duration Filter -->
                <div class="filter-section">
                    <div class="filter-section-title">Thời Hạn Gói</div>
                    @foreach([['1month','1 Tháng',8],['6month','6 Tháng',10],['1year','1 Năm',18],['2year','2 Năm',12]] as [$val,$label,$cnt])
                    <div class="filter-check">
                        <input type="checkbox" id="dur_{{ $val }}" class="duration-filter" data-duration="{{ $val }}">
                        <label for="dur_{{ $val }}">{{ $label }}</label>
                        <span class="count">{{ $cnt }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Price Range -->
                <div class="filter-section">
                    <div class="filter-section-title">Khoảng Giá</div>
                    <input type="range" class="filter-range" id="priceRange" min="50000" max="2000000" value="2000000" step="50000">
                    <div class="filter-price-display">
                        <span>50.000đ</span>
                        <span id="priceDisplay" class="fw-600 text-primary">2.000.000đ trở xuống</span>
                    </div>
                </div>


                </div>
            </div>
        </div>

        <!-- PRODUCTS GRID -->
        <div class="col-lg-9">
            <!-- Sort / View Controls -->
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Hiển thị <strong id="productCount">24</strong> sản phẩm</span>
                    @if(request('brand'))
                    <span class="badge bg-primary-light text-primary border border-primary-light px-2 py-1 rounded-pill" style="font-size:12px">
                        <i class="bi bi-funnel me-1"></i>{{ ucfirst(request('brand')) }}
                        <a href="{{ route('products') }}" class="text-primary ms-1"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" id="sortSelect" style="width:auto;font-size:13.5px;border-color:var(--gray-200)">
                        <option value="popular">Phổ Biến Nhất</option>
                        <option value="price_asc">Giá Tăng Dần</option>
                        <option value="price_desc">Giá Giảm Dần</option>
                        <option value="rating">Đánh Giá Cao</option>
                    </select>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-secondary active" id="viewGrid" title="Xem dạng lưới">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="viewList" title="Xem dạng danh sách">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4" id="productsGrid">
                @php
                // Filter by brand if set
                $filterBrand = request('brand','');
                if ($filterBrand) {
                    $allProducts = array_filter($allProducts ?? [], fn($p) => $p['slug'] === $filterBrand || ($p['category']['slug'] ?? '') === $filterBrand);
                }
                @endphp

                @foreach($allProducts ?? [] as $prod)
                <div class="col-lg-4 col-md-6 product-card-wrap"
                    data-name="{{ strtolower($prod['name']) }}"
                    data-brand="{{ strtolower($prod['brand']) }}"
                    data-price="{{ $prod['price'] }}"
                    data-rating="{{ $prod['rating'] }}"
                    data-slug="{{ !empty($prod['category']) ? $prod['category']['slug'] : $prod['slug'] }}"
                    data-plan="{{ $prod['plan'] }}">
                    <div class="product-card">
                        <div class="product-card-badge">
                            @if(($prod['old_price'] ?? 0) > $prod['price'])<span class="badge-sale">-{{ round((($prod['old_price'] ?? 0) - $prod['price']) / ($prod['old_price'] ?: 1) * 100) }}%</span>@endif
                            @if($prod['plan'] === '1year' || $prod['plan'] === '2year')<span class="badge-hot"><i class="bi bi-fire"></i> Hot</span>@endif
                        </div>
                        <a href="{{ route('product.detail', $prod['slug']) }}" class="product-card-img" style="text-decoration: none; display: flex; justify-content: center; align-items: center;">
                            @if(!empty($prod['image_path']))
                                <img src="{{ asset($prod['image_path']) }}" alt="{{ $prod['name'] }}">
                            @else
                                <div class="product-brand-logo" style="background:linear-gradient(135deg,{{ $prod['color'] }},{{ $prod['color'] }}99)">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                            @endif
                        </a>
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
                                @foreach(array_slice($prod['features'],0,3) as $feat)
                                <li><i class="bi bi-check-circle-fill"></i>{{ $feat }}</li>
                                @endforeach
                            </ul>
                            <div class="product-rating mb-2">
                                <div class="rating-stars">
                                    @for($i=1;$i<=5;$i++)<i class="bi {{ $i <= floor($prod['rating']) ? 'bi-star-fill' : ($i - $prod['rating'] < 1 ? 'bi-star-half' : 'bi-star') }}"></i>@endfor
                                </div>
                                <span class="fw-600 ms-1" style="font-size:12px;color:var(--gray-700)">{{ $prod['rating'] }}</span>
                                <span class="rating-count">({{ number_format($prod['reviews']) }} đánh giá)</span>
                                <span class="ms-2 text-muted" style="font-size:11.5px">• Đã bán {{ \App\Models\Setting::get('sales_' . strtolower($prod['slug']), '100+') }}</span>
                            </div>
                             <div class="product-price-wrap">
                                 @if(($prod['old_price'] ?? 0) > $prod['price'])
                                     <div class="product-price-old">{{ number_format($prod['old_price']) }}đ</div>
                                 @endif
                                 <div class="d-flex align-items-baseline gap-1">
                                     <div class="product-price">{{ number_format($prod['price']) }}đ</div>
                                     <span class="product-price-unit">/{{ \App\Models\Product::formatPlanUnit($prod['plan']) }}</span>
                                 </div>
                             </div>
                            <div class="product-actions">
                                <a href="{{ route('product.detail', $prod['slug']) }}" class="btn-add-cart" style="text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px">
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

            <!-- Pagination Container -->
            <div class="d-flex justify-content-center mt-4" id="paginationContainer"></div>

            <!-- Empty State -->
            <div id="emptyState" class="empty-state d-none">
                <div class="empty-state-icon"><i class="bi bi-search"></i></div>
                <h5>Không tìm thấy sản phẩm</h5>
                <p>Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm của bạn</p>
                <button class="btn btn-primary mt-3 rounded-pill" onclick="resetFilters()">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Xóa Bộ Lọc
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
let currentPage = 1;
const itemsPerPage = 9;

function resetFilters() {
    document.querySelectorAll('.brand-filter, .duration-filter').forEach(cb => cb.checked = false);
    const searchInput = document.getElementById('productSearch');
    if (searchInput) searchInput.value = '';
    const priceRange = document.getElementById('priceRange');
    if (priceRange) priceRange.value = 2000000;
    const priceDisplay = document.getElementById('priceDisplay');
    if (priceDisplay) priceDisplay.textContent = '2.000.000đ trở xuống';
    
    applyFilters(true);
}

function updateCount() {
    const visible = document.querySelectorAll('.product-card-wrap:not([style*="none"])').length;
    const el = document.getElementById('productCount');
    if (el) el.textContent = visible;
    const empty = document.getElementById('emptyState');
    if (empty) empty.classList.toggle('d-none', visible > 0);
}

function applyFilters(resetPage = true) {
    if (resetPage) {
        currentPage = 1;
    }

    const searchQuery = (document.getElementById('productSearch')?.value || '').toLowerCase().trim();
    const selectedBrands = Array.from(document.querySelectorAll('.brand-filter:checked')).map(c => c.dataset.brand);
    const selectedDurations = Array.from(document.querySelectorAll('.duration-filter:checked')).map(c => c.dataset.duration);
    const maxPrice = parseFloat(document.getElementById('priceRange')?.value || 2000000);

    let matchedCount = 0;
    const matchedElements = [];

    document.querySelectorAll('.product-card-wrap').forEach(el => {
        const name = el.dataset.name || '';
        const brand = el.dataset.brand || '';
        const slug = el.dataset.slug || '';
        const plan = el.dataset.plan || '';
        const price = parseFloat(el.dataset.price || 0);

        const matchesSearch = searchQuery === '' || name.includes(searchQuery) || brand.includes(searchQuery);
        const matchesBrand = selectedBrands.length === 0 || selectedBrands.includes(slug);
        const matchesDuration = selectedDurations.length === 0 || selectedDurations.includes(plan);
        const matchesPrice = price <= maxPrice;

        if (matchesSearch && matchesBrand && matchesDuration && matchesPrice) {
            matchedElements.push(el);
            matchedCount++;
        } else {
            el.style.display = 'none';
        }
    });

    const elCount = document.getElementById('productCount');
    if (elCount) elCount.textContent = matchedCount;
    const empty = document.getElementById('emptyState');
    if (empty) empty.classList.toggle('d-none', matchedCount > 0);

    renderPagination(matchedElements);
}

function renderPagination(matchedElements) {
    const totalItems = matchedElements.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    if (currentPage > totalPages) {
        currentPage = Math.max(1, totalPages);
    }

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    matchedElements.forEach((el, index) => {
        if (index >= startIndex && index < endIndex) {
            el.style.display = '';
        } else {
            el.style.display = 'none';
        }
    });

    const container = document.getElementById('paginationContainer');
    if (!container) return;
    container.innerHTML = '';

    if (totalPages <= 1) return;

    let html = '<nav aria-label="Page navigation"><ul class="pagination pagination-custom d-flex gap-2 align-items-center mb-0" style="list-style:none; padding:0; margin:0;">';
    
    // Prev Button
    html += `<li class="page-item">
        <button type="button" class="page-link shadow-sm d-flex align-items-center justify-content-center" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} style="width:36px; height:36px; border-radius:8px; border:1px solid #e2e8f0; background:${currentPage === 1 ? '#f8fafc' : '#fff'}; color:${currentPage === 1 ? '#cbd5e1' : 'var(--gray-700)'}; font-weight:600; cursor:${currentPage === 1 ? 'not-allowed' : 'pointer'};"><i class="bi bi-chevron-left"></i></button>
    </li>`;

    // Page Numbers
    for (let i = 1; i <= totalPages; i++) {
        const isActive = i === currentPage;
        html += `<li class="page-item">
            <button type="button" class="page-link shadow-sm d-flex align-items-center justify-content-center" onclick="changePage(${i})" style="width:36px; height:36px; border-radius:8px; border:1px solid ${isActive ? '#2563eb' : '#e2e8f0'}; background:${isActive ? '#2563eb' : '#fff'}; color:${isActive ? '#fff' : 'var(--gray-700)'}; font-weight:700; cursor:pointer;">${i}</button>
        </li>`;
    }

    // Next Button
    html += `<li class="page-item">
        <button type="button" class="page-link shadow-sm d-flex align-items-center justify-content-center" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} style="width:36px; height:36px; border-radius:8px; border:1px solid #e2e8f0; background:${currentPage === totalPages ? '#f8fafc' : '#fff'}; color:${currentPage === totalPages ? '#cbd5e1' : 'var(--gray-700)'}; font-weight:600; cursor:${currentPage === totalPages ? 'not-allowed' : 'pointer'};"><i class="bi bi-chevron-right"></i></button>
    </li>`;

    html += '</ul></nav>';
    container.innerHTML = html;
}

function changePage(page) {
    currentPage = page;
    applyFilters(false);
    document.getElementById('productsGrid')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Bind events
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('productSearch')?.addEventListener('input', () => applyFilters(true));
    document.querySelectorAll('.brand-filter').forEach(cb => cb.addEventListener('change', () => applyFilters(true)));
    document.querySelectorAll('.duration-filter').forEach(cb => cb.addEventListener('change', () => applyFilters(true)));
    
    const priceRange = document.getElementById('priceRange');
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            const display = document.getElementById('priceDisplay');
            if (display) display.textContent = formatCurrency(parseInt(this.value)) + ' trở xuống';
            applyFilters(true);
        });
    }
    
    // Initial call
    applyFilters(true);
});

// View toggle
document.getElementById('viewGrid')?.addEventListener('click', function() {
    document.getElementById('productsGrid').className = 'row g-4';
    document.querySelectorAll('.product-card-wrap').forEach(el => { el.className = el.className.replace(/col-\w+-\d+/g,''); el.classList.add('col-lg-4','col-md-6'); });
    this.classList.add('active');
    document.getElementById('viewList').classList.remove('active');
});
document.getElementById('viewList')?.addEventListener('click', function() {
    document.getElementById('productsGrid').className = 'row g-3';
    document.querySelectorAll('.product-card-wrap').forEach(el => { el.className = el.className.replace(/col-\w+-\d+/g,''); el.classList.add('col-12'); });
    this.classList.add('active');
    document.getElementById('viewGrid').classList.remove('active');
});
</script>
@endsection
