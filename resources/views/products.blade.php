@extends('layouts.app')

@if($selectedCategory)
    @php
        $catTitle = $selectedCategory->seo_title ?: $selectedCategory->name;
        if (!str_contains(mb_strtolower($catTitle), 'giá rẻ')) {
            $catTitle = 'Tài Khoản ' . preg_replace('/^Tài Khoản\s+/i', '', $catTitle) . ' Giá Rẻ - Bảo Hành Trọn Gói';
        }
    @endphp
    @section('title', $catTitle)
    @section('meta_description', $selectedCategory->seo_description ?: 'Mua tài khoản ' . $selectedCategory->name . ' giá rẻ chính hãng tại VPNStore. Giao hàng tự động 24/7, bảo hành trọn gói 1 đổi 1 uy tín.')
@else
    @section('title', 'Tài Khoản Bản Quyền Giá Rẻ - Bảo Hành Trọn Gói')
    @section('meta_description', 'Cửa hàng cung cấp tài khoản bản quyền giá rẻ: ChatGPT, TikTok, Canva, Netflix, Spotify, VPN chính hãng. Giao hàng tự động 24/7, bảo hành trọn gói 1 đổi 1.')
@endif

@section('content')

<style>
.filter-category-item {
    display: flex;
    align-items: center;
    padding: 9px 12px;
    border-radius: var(--radius-md);
    font-size: 0.85rem;
    color: var(--text-secondary);
    transition: var(--transition);
    text-decoration: none;
    white-space: nowrap;
}
.filter-category-item:hover, .filter-category-item.active {
    color: var(--primary-light) !important;
    background: rgba(124, 58, 237, 0.08) !important;
    font-weight: 600;
}
.price-pill.active, .plan-filter-btn.active {
    background: var(--primary-light) !important;
    color: #ffffff !important;
    font-weight: 700 !important;
}
</style>

@php
    // Category icon mapping by slug keywords for all 78+ catalog items
    function catIcon(string $slug, string $type): string {
        $s = strtolower($slug);
        if (str_contains($s, 'vpn') || str_contains($s, 'proxy') || $type === 'vpn') return 'bi-shield-lock-fill';
        if (str_contains($s, 'chat') || str_contains($s, 'gpt') || str_contains($s, 'ai') || str_contains($s, 'gemini') || str_contains($s, 'grok') || str_contains($s, 'claude') || str_contains($s, 'cursor') || str_contains($s, 'deepseek') || str_contains($s, 'perplexity') || str_contains($s, 'elevenlabs') || str_contains($s, 'runway') || str_contains($s, 'lovable') || str_contains($s, 'qwen') || str_contains($s, 'genspark') || str_contains($s, 'kling') || str_contains($s, 'krea') || str_contains($s, 'openart') || str_contains($s, 'leonardo') || str_contains($s, 'heygen') || str_contains($s, 'minimax') || str_contains($s, 'higgfield') || str_contains($s, 'akool') || str_contains($s, 'seedance') || str_contains($s, 'antigravity')) return 'bi-cpu-fill';
        if (str_contains($s, 'canva') || str_contains($s, 'design') || str_contains($s, 'adobe') || str_contains($s, 'photoshop') || str_contains($s, 'figma') || str_contains($s, 'freepik') || str_contains($s, 'meitu') || str_contains($s, 'xingtu') || str_contains($s, 'wink') || str_contains($s, 'decor')) return 'bi-palette-fill';
        if (str_contains($s, 'youtube') || str_contains($s, 'capcut') || str_contains($s, 'netflix') || str_contains($s, 'video') || str_contains($s, 'phim') || str_contains($s, 'tv-360') || str_contains($s, 'galaxy') || str_contains($s, 'tiktok') || str_contains($s, 'spotify')) return 'bi-play-btn-fill';
        if (str_contains($s, 'code') || str_contains($s, 'intellij') || str_contains($s, 'jetbrains') || str_contains($s, 'replit') || str_contains($s, 'gpm') || str_contains($s, 'aws')) return 'bi-code-slash';
        return 'bi-box-seam-fill';
    }

    // Build a JS-safe list of categories
    $catList = $categories->map(fn($c) => [
        'id'              => $c->id,
        'name'            => $c->name,
        'slug'            => $c->slug,
        'type'            => $c->type,
        'count'           => $c->products_count ?? 0,
        'icon'            => catIcon($c->slug, $c->type),
        'seo_title'       => $c->seo_title,
        'seo_description' => $c->seo_description,
    ]);

    $totalActiveProducts = count($allProducts);
@endphp

<section class="section">
    <div class="container">
        {{-- Header --}}
        <div class="section-header" style="margin-bottom:28px;">
            <div>
                <h1 class="section-title">
                    <i class="bi bi-box-seam-fill" style="color: var(--primary-light); margin-right: 6px;" id="headerIcon"></i>
                    <span id="headerTitle">Tất Cả Sản Phẩm</span>
                </h1>
                <p class="section-subtitle">
                    Tìm thấy <strong style="color:var(--text-primary)" id="productCount">{{ $totalActiveProducts }}</strong> sản phẩm
                    <span id="categoryNameSpan" style="color:var(--primary-light); font-weight:700;"></span>
                </p>
            </div>

            {{-- Sort --}}
            <div style="display:flex; gap:8px; align-items:center;">
                <select id="sortSelect" class="form-control" style="width:auto; padding:8px 36px 8px 12px; background:var(--bg-elevated); border:1px solid var(--border); color:var(--text-primary); border-radius:var(--radius); outline:none;">
                    <option value="popular">Phổ Biến Nhất</option>
                    <option value="price_asc">Giá Tăng Dần</option>
                    <option value="price_desc">Giá Giảm Dần</option>
                    <option value="rating">Đánh Giá Cao</option>
                </select>
            </div>
        </div>

        <div class="products-main-layout">

            {{-- ===== SIDEBAR FILTERS ===== --}}
            <aside class="products-sidebar-sticky" style="display:flex; flex-direction:column; gap:16px;">
                {{-- Search Bar --}}
                <div class="search-bar" style="max-width:100%; border:1px solid var(--border); border-radius:var(--radius); background:var(--bg-input); padding:10px 14px; display:flex; align-items:center; gap:8px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="productSearch" placeholder="Tìm sản phẩm..." style="width:100%; border:none; background:none; outline:none; color:var(--text-primary); font-size:0.85rem;">
                </div>

                {{-- Price Filter Card --}}
                <div class="card" style="padding:16px;">
                    <h3 style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                        <i class="bi bi-cash-coin text-success"></i> Khoảng Giá
                    </h3>
                    
                    {{-- Quick Price Pills --}}
                    <div style="display:flex; flex-direction:column; gap:6px; margin-bottom:14px;" id="pricePills">
                        <button type="button" class="btn btn-ghost btn-sm price-pill active" data-min="0" data-max="999999999" style="text-align:left; justify-content:flex-start; font-size:0.82rem; padding:6px 10px; border-radius:8px;">
                            Tất Cả Giá
                        </button>
                        <button type="button" class="btn btn-ghost btn-sm price-pill" data-min="0" data-max="100000" style="text-align:left; justify-content:flex-start; font-size:0.82rem; padding:6px 10px; border-radius:8px;">
                            Dưới 100.000đ
                        </button>
                        <button type="button" class="btn btn-ghost btn-sm price-pill" data-min="100000" data-max="300000" style="text-align:left; justify-content:flex-start; font-size:0.82rem; padding:6px 10px; border-radius:8px;">
                            100.000đ - 300.000đ
                        </button>
                        <button type="button" class="btn btn-ghost btn-sm price-pill" data-min="300000" data-max="500000" style="text-align:left; justify-content:flex-start; font-size:0.82rem; padding:6px 10px; border-radius:8px;">
                            300.000đ - 500.000đ
                        </button>
                        <button type="button" class="btn btn-ghost btn-sm price-pill" data-min="500000" data-max="999999999" style="text-align:left; justify-content:flex-start; font-size:0.82rem; padding:6px 10px; border-radius:8px;">
                            Trên 500.000đ
                        </button>
                    </div>

                    {{-- Custom Min-Max Price Inputs --}}
                    <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; margin-bottom:8px;">Nhập khoảng giá (đ):</div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; margin-bottom:10px;">
                        <input type="number" id="minPriceInput" placeholder="Từ" style="width:100%; padding:6px 10px; font-size:0.8rem; border:1px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary); outline:none;">
                        <input type="number" id="maxPriceInput" placeholder="Đến" style="width:100%; padding:6px 10px; font-size:0.8rem; border:1px solid var(--border); border-radius:8px; background:var(--bg-input); color:var(--text-primary); outline:none;">
                    </div>
                    <button type="button" id="applyCustomPriceBtn" class="btn btn-primary btn-sm btn-full" style="padding:7px; font-size:0.8rem; border-radius:8px; font-weight:700;">
                        <i class="bi bi-funnel-fill me-1"></i>Áp Dụng
                    </button>
                </div>

                {{-- Plan Duration Filter Card --}}
                <div class="card" style="padding:16px;">
                    <h3 style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                        <i class="bi bi-clock-history text-info"></i> Thời Hạn Gói
                    </h3>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;" id="planFilterButtons">
                        <button type="button" class="btn btn-ghost btn-sm plan-filter-btn active" data-plan="all" style="font-size:0.78rem; padding:5px 9px; border-radius:6px;">Tất Cả</button>
                        <button type="button" class="btn btn-ghost btn-sm plan-filter-btn" data-plan="1month" style="font-size:0.78rem; padding:5px 9px; border-radius:6px;">1 Tháng</button>
                        <button type="button" class="btn btn-ghost btn-sm plan-filter-btn" data-plan="6month" style="font-size:0.78rem; padding:5px 9px; border-radius:6px;">6 Tháng</button>
                        <button type="button" class="btn btn-ghost btn-sm plan-filter-btn" data-plan="1year" style="font-size:0.78rem; padding:5px 9px; border-radius:6px;">1 Năm</button>
                        <button type="button" class="btn btn-ghost btn-sm plan-filter-btn" data-plan="2year" style="font-size:0.78rem; padding:5px 9px; border-radius:6px;">2 Năm</button>
                        <button type="button" class="btn btn-ghost btn-sm plan-filter-btn" data-plan="lifetime" style="font-size:0.78rem; padding:5px 9px; border-radius:6px;">Vĩnh Viễn</button>
                    </div>
                </div>

                {{-- Stock Status Filter Card --}}
                <div class="card" style="padding:14px 16px;">
                    <label style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; margin:0; font-size:0.85rem; font-weight:600; color:var(--text-primary);">
                        <span><i class="bi bi-check-circle-fill text-success me-2"></i>Chỉ hiện Còn Hàng</span>
                        <input type="checkbox" id="inStockCheckbox" style="width:16px; height:16px; accent-color:var(--primary); cursor:pointer;">
                    </label>
                </div>

                <button id="clearFiltersBtn" class="btn btn-ghost btn-full btn-sm" style="display:none; border:1px solid #ef4444; color:#ef4444; border-radius:10px; font-weight:700; padding:10px;">✕ Xóa Tất Cả Bộ Lọc</button>
            </aside>

            {{-- ===== PRODUCTS GRID ===== --}}
            <div style="min-width: 0; max-width: 100%;">

                {{-- Mobile Search Bar --}}
                <div class="mobile-search-wrapper" style="margin-bottom: 16px;">
                    <div class="search-bar" style="max-width:100%; border:1px solid var(--border); border-radius:var(--radius); background:var(--bg-input); padding:10px 14px; display:flex; align-items:center; gap:8px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="productSearchMobile" placeholder="Tìm sản phẩm..." style="width:100%; border:none; background:none; outline:none; color:var(--text-primary); font-size:16px;">
                    </div>
                </div>

                {{-- Category Tabs (top) --}}
                <div class="category-tabs" style="margin-bottom:24px;">
                    <a href="#" class="category-tab active" data-category="all">
                        <i class="bi bi-grid-fill" style="margin-right:6px;"></i> Tất Cả
                    </a>
                    @foreach($categories as $cat)
                    <a href="#" class="category-tab" data-category="{{ $cat->slug }}" style="display: flex; align-items: center; gap: 6px;">
                        @if($cat->image_path && strlen($cat->image_url) > 5)
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" width="14" height="14" loading="lazy" decoding="async" style="width: 14px; height: 14px; object-fit: contain; border-radius: 2px; flex-shrink: 0;" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline-block';">
                            <i class="bi {{ catIcon($cat->slug, $cat->type) }}" style="flex-shrink: 0; display:none;"></i>
                        @else
                            <i class="bi {{ catIcon($cat->slug, $cat->type) }}" style="flex-shrink: 0;"></i>
                        @endif
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>

                {{-- Grid --}}
                <div class="product-grid" id="productsGrid">
                    @foreach($allProducts as $prod)
                    @php
                        $pCatSlug = strtolower($prod['category']['slug'] ?? '');
                        if (empty($pCatSlug)) {
                            $pBrand = strtolower($prod['brand'] ?? '');
                            $pName = strtolower($prod['name'] ?? '');
                            foreach($categories as $c) {
                                $cName = strtolower($c->name);
                                $cSlug = strtolower($c->slug);
                                if (str_contains($pBrand, $cName) || str_contains($pBrand, $cSlug) || str_contains($pName, $cName) || str_contains($pName, $cSlug)) {
                                    $pCatSlug = $c->slug;
                                    break;
                                }
                            }
                        }
                        if (empty($pCatSlug)) {
                            $pCatSlug = 'all';
                        }
                        $pPlan = strtolower($prod['plan'] ?? '');
                        $pStock = (int)($prod['stock'] ?? 0);
                    @endphp
                    <div class="product-card-wrap"
                         data-id="{{ $prod['id'] }}"
                         data-name="{{ strtolower($prod['name']) }}"
                         data-brand="{{ strtolower($prod['brand'] ?? '') }}"
                         data-price="{{ $prod['price'] }}"
                         data-rating="{{ $prod['rating'] ?? 0 }}"
                         data-category="{{ $pCatSlug }}"
                         data-plan="{{ $pPlan }}"
                         data-stock="{{ $pStock }}">
                        @include('partials.product-card', ['product' => $prod])
                    </div>
                    @endforeach
                </div>

                {{-- Pagination container --}}
                <div id="paginationContainer" class="pagination" style="margin-top:32px;"></div>

                {{-- Empty state --}}
                <div id="emptyState" style="text-align:center; padding:80px 0; display:none;">
                    <div style="margin-bottom:16px;"><i class="bi bi-search text-muted" style="font-size: 4rem;"></i></div>
                    <h3 style="font-size:1.2rem; font-weight:700; margin-bottom:8px;">Không Tìm Thấy Sản Phẩm</h3>
                    <p style="color:var(--text-muted); margin-bottom:24px;">Thử tìm kiếm với từ khóa khác hoặc xóa bộ lọc</p>
                    <a href="#" class="btn btn-primary" id="resetAllBtn">Xem Tất Cả</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
// Category meta built from server-side Blade
const catMetaFromDB = {
    'all': { title: 'Tất Cả Sản Phẩm', icon: 'bi bi-box-seam-fill' },
    @foreach($categories as $cat)
    '{{ $cat->slug }}': { title: '{{ addslashes($cat->name) }}', icon: 'bi {{ catIcon($cat->slug, $cat->type) }}' },
    @endforeach
};

const categoriesList = @json($catList);

document.addEventListener("DOMContentLoaded", function () {
    const searchInput       = document.getElementById('productSearch');
    const searchInputMobile = document.getElementById('productSearchMobile');
    const sortSelect        = document.getElementById('sortSelect');
    const productsGrid      = document.getElementById('productsGrid');
    const productCountText  = document.getElementById('productCount');
    const headerTitle       = document.getElementById('headerTitle');
    const headerIcon        = document.getElementById('headerIcon');
    const categoryNameSpan  = document.getElementById('categoryNameSpan');
    const emptyState        = document.getElementById('emptyState');
    const clearFiltersBtn   = document.getElementById('clearFiltersBtn');
    const resetAllBtn       = document.getElementById('resetAllBtn');
    const paginationContainer = document.getElementById('paginationContainer');
    
    // Price filter elements
    const pricePills          = document.querySelectorAll('#pricePills .price-pill');
    const minPriceInput       = document.getElementById('minPriceInput');
    const maxPriceInput       = document.getElementById('maxPriceInput');
    const applyCustomPriceBtn = document.getElementById('applyCustomPriceBtn');
    
    // Plan filter buttons & Stock checkbox
    const planButtons      = document.querySelectorAll('#planFilterButtons .plan-filter-btn');
    const inStockCheckbox  = document.getElementById('inStockCheckbox');
    
    const sidebarItems  = document.querySelectorAll('#sidebarCategories .filter-category-item');
    const tabItems      = document.querySelectorAll('.category-tabs .category-tab');
    const productWrappers = Array.from(document.querySelectorAll('.product-card-wrap'));
    const totalCount = productWrappers.length;

    // Update "Tất Cả" count
    document.getElementById('count-all').textContent = totalCount;

    let activeCategory = 'all';
    let searchQuery    = '';
    let minPrice       = 0;
    let maxPrice       = 999999999;
    let activePlan     = 'all';
    let inStockOnly    = false;
    let currentPage    = 1;
    const itemsPerPage = 12;

    function updateCategoryUI(catSlug, skipPushState = false) {
        activeCategory = catSlug;
        currentPage = 1;

        sidebarItems.forEach(item => item.classList.toggle('active', item.dataset.category === catSlug));
        tabItems.forEach(item     => item.classList.toggle('active', item.dataset.category === catSlug));

        const meta = catMetaFromDB[catSlug] || catMetaFromDB['all'];
        headerTitle.textContent = meta.title;
        headerIcon.className    = meta.icon;
        categoryNameSpan.textContent = (catSlug !== 'all') ? ` trong danh mục ${meta.title}` : '';

        // Dynamically update page URL without reload
        if (!skipPushState) {
            const newUrl = catSlug === 'all' 
                ? window.location.pathname 
                : window.location.pathname + '?category=' + catSlug;
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        // Dynamically update document title & meta description (SEO)
        const catObj = categoriesList.find(c => c.slug === catSlug);
        if (catObj) {
            document.title = (catObj.seo_title || catObj.name) + ' - VPN Store Pro';
            const metaDesc = document.querySelector('meta[name="description"]');
            if (metaDesc) {
                metaDesc.setAttribute('content', catObj.seo_description || `Khám phá các phần mềm bản quyền chính hãng trong danh mục ${catObj.name} với giá tốt nhất.`);
            }
        } else {
            document.title = 'Sản Phẩm - VPN Store Pro';
            const metaDesc = document.querySelector('meta[name="description"]');
            if (metaDesc) {
                metaDesc.setAttribute('content', 'Khám phá các phần mềm bản quyền chính hãng: VPN Premium, AI Code, Design Software, Xem Phim Premium với giá tốt nhất.');
            }
        }

        filterProducts();
    }

    function filterProducts() {
        searchQuery = searchInput.value.toLowerCase().trim();

        const matchingWrappers = productWrappers.filter(wrap => {
            const name     = wrap.dataset.name     || '';
            const brand    = wrap.dataset.brand    || '';
            const cat      = wrap.dataset.category || 'all';
            const price    = parseFloat(wrap.dataset.price) || 0;
            const plan     = wrap.dataset.plan     || '';
            const stock    = parseInt(wrap.dataset.stock) || 0;

            const matchesSearch   = !searchQuery || name.includes(searchQuery) || brand.includes(searchQuery);
            const matchesCategory = activeCategory === 'all' || cat === activeCategory;
            const matchesPrice    = price >= minPrice && price <= maxPrice;
            const matchesPlan     = activePlan === 'all' || plan.includes(activePlan);
            const matchesStock    = !inStockOnly || (stock > 0 || stock === -1);

            return matchesSearch && matchesCategory && matchesPrice && matchesPlan && matchesStock;
        });

        const visibleCount = matchingWrappers.length;
        productWrappers.forEach(wrap => wrap.style.display = 'none');

        const totalPages = Math.ceil(visibleCount / itemsPerPage);
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex   = Math.min(startIndex + itemsPerPage, visibleCount);

        for (let i = startIndex; i < endIndex; i++) {
            matchingWrappers[i].style.display = 'block';
            const animEl = matchingWrappers[i].querySelector('.animate-on-scroll');
            if (animEl) {
                animEl.style.opacity = '1';
                animEl.style.transform = 'translateY(0)';
            }
        }

        productCountText.textContent = visibleCount;

        if (visibleCount > 0) {
            productsGrid.style.display = 'grid';
            emptyState.style.display   = 'none';
            renderPagination(totalPages);
        } else {
            productsGrid.style.display = 'none';
            emptyState.style.display   = 'block';
            paginationContainer.innerHTML = '';
        }

        const isFiltered = searchQuery || activeCategory !== 'all' || minPrice > 0 || maxPrice < 999999999 || activePlan !== 'all' || inStockOnly;
        clearFiltersBtn.style.display = isFiltered ? 'block' : 'none';
    }

    function renderPagination(totalPages) {
        if (totalPages <= 1) { paginationContainer.innerHTML = ''; return; }

        let html = '';
        html += currentPage > 1
            ? `<a href="#" class="page-btn" data-page="${currentPage - 1}">‹</a>`
            : `<span class="page-btn" style="opacity:0.4; cursor:not-allowed;">‹</span>`;

        for (let i = 1; i <= totalPages; i++) {
            html += i === currentPage
                ? `<span class="page-btn active">${i}</span>`
                : `<a href="#" class="page-btn" data-page="${i}">${i}</a>`;
        }

        html += currentPage < totalPages
            ? `<a href="#" class="page-btn" data-page="${currentPage + 1}">›</a>`
            : `<span class="page-btn" style="opacity:0.4; cursor:not-allowed;">›</span>`;

        paginationContainer.innerHTML = html;

        paginationContainer.querySelectorAll('a.page-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.dataset.page);
                filterProducts();
                window.scrollTo({ top: document.querySelector('.section-header').offsetTop - 90, behavior: 'smooth' });
            });
        });
    }

    function sortProducts() {
        productWrappers.sort((a, b) => {
            const priceA  = parseFloat(a.dataset.price) || 0;
            const priceB  = parseFloat(b.dataset.price) || 0;
            const ratingA = parseFloat(a.dataset.rating) || 0;
            const ratingB = parseFloat(b.dataset.rating) || 0;
            const sortVal = sortSelect.value;
            if (sortVal === 'price_asc')  return priceA - priceB;
            if (sortVal === 'price_desc') return priceB - priceA;
            if (sortVal === 'rating')     return ratingB - ratingA;
            return (parseInt(a.dataset.id) || 0) - (parseInt(b.dataset.id) || 0);
        });

        productWrappers.forEach(w => productsGrid.appendChild(w));
        filterProducts();
    }

    // Category click handlers
    sidebarItems.forEach(item => item.addEventListener('click', e => { e.preventDefault(); updateCategoryUI(item.dataset.category); }));
    tabItems.forEach(item     => item.addEventListener('click', e => { e.preventDefault(); updateCategoryUI(item.dataset.category); }));

    // Price Preset Pills
    pricePills.forEach(pill => {
        pill.addEventListener('click', function() {
            pricePills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            minPrice = parseFloat(this.dataset.min) || 0;
            maxPrice = parseFloat(this.dataset.max) || 999999999;
            minPriceInput.value = '';
            maxPriceInput.value = '';
            currentPage = 1;
            filterProducts();
        });
    });

    // Custom Price Input Apply
    applyCustomPriceBtn.addEventListener('click', function() {
        pricePills.forEach(p => p.classList.remove('active'));
        minPrice = parseFloat(minPriceInput.value) || 0;
        maxPrice = parseFloat(maxPriceInput.value) || 999999999;
        if (minPrice > maxPrice) {
            const tmp = minPrice;
            minPrice = maxPrice;
            maxPrice = tmp;
        }
        currentPage = 1;
        filterProducts();
    });

    // Plan Duration Buttons
    planButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            planButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activePlan = this.dataset.plan || 'all';
            currentPage = 1;
            filterProducts();
        });
    });

    // In Stock Only Checkbox
    inStockCheckbox.addEventListener('change', function() {
        inStockOnly = this.checked;
        currentPage = 1;
        filterProducts();
    });
    
    // Search inputs
    searchInput.addEventListener('input', () => {
        if (searchInputMobile) searchInputMobile.value = searchInput.value;
        currentPage = 1;
        filterProducts();
    });
    if (searchInputMobile) {
        searchInputMobile.addEventListener('input', () => {
            searchInput.value = searchInputMobile.value;
            currentPage = 1;
            filterProducts();
        });
    }
    
    sortSelect.addEventListener('change', sortProducts);
 
    function resetAllFilters() { 
        searchInput.value = ''; 
        if (searchInputMobile) searchInputMobile.value = ''; 
        minPrice = 0;
        maxPrice = 999999999;
        minPriceInput.value = '';
        maxPriceInput.value = '';
        pricePills.forEach((p, idx) => p.classList.toggle('active', idx === 0));
        activePlan = 'all';
        planButtons.forEach((b, idx) => b.classList.toggle('active', idx === 0));
        inStockOnly = false;
        inStockCheckbox.checked = false;
        updateCategoryUI('all'); 
    }
    clearFiltersBtn.addEventListener('click', resetAllFilters);
    resetAllBtn.addEventListener('click', resetAllFilters);
 
    // Read URL params on load
    const urlParams     = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category') || urlParams.get('brand');
    const queryParam    = urlParams.get('q');
 
    if (categoryParam && catMetaFromDB[categoryParam]) {
        updateCategoryUI(categoryParam, true);
    } else {
        filterProducts();
    }
    if (queryParam) { 
        searchInput.value = queryParam; 
        if (searchInputMobile) searchInputMobile.value = queryParam;
        filterProducts(); 
    }
    sortProducts();
});
</script>
@endsection
