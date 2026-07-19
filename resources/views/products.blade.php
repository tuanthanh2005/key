@extends('layouts.app')

@if($selectedCategory)
    @section('title', $selectedCategory->seo_title ?: $selectedCategory->name)
    @section('meta_description', $selectedCategory->seo_description ?: 'Khám phá các phần mềm bản quyền chính hãng trong danh mục ' . $selectedCategory->name . ' với giá tốt nhất.')
@else
    @section('title', 'Danh Sách Sản Phẩm')
    @section('meta_description', 'Khám phá các phần mềm bản quyền chính hãng: VPN Premium, AI Code, Design Software, Xem Phim Premium với giá tốt nhất.')
@endif

@section('content')

<style>
.filter-category-item {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
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
</style>

@php
    // Category icon mapping by slug keywords
    function catIcon(string $slug, string $type): string {
        if (str_contains($slug, 'vpn') || $type === 'vpn') return 'bi-shield-lock-fill';
        if (str_contains($slug, 'ai') || str_contains($slug, 'code')) return 'bi-cpu-fill';
        if (str_contains($slug, 'design') || str_contains($slug, 'adobe')) return 'bi-palette-fill';
        if (str_contains($slug, 'phim') || str_contains($slug, 'film') || str_contains($slug, 'movie')) return 'bi-play-btn-fill';
        if (str_contains($slug, 'proxy')) return 'bi-hdd-network-fill';
        return 'bi-folder-fill';
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
        <div class="section-header" style="margin-bottom:32px;">
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
            <aside class="products-sidebar-sticky">
                {{-- Search --}}
                <div class="search-bar" style="max-width:100%; border:1px solid var(--border); border-radius:var(--radius); background:var(--bg-input); padding:10px 14px; display:flex; align-items:center; gap:8px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="productSearch" placeholder="Tìm sản phẩm..." style="width:100%; border:none; background:none; outline:none; color:var(--text-primary); font-size:0.85rem;">
                </div>

                {{-- Categories --}}
                <div class="card" style="padding:16px;">
                    <h3 style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-muted); margin-bottom:12px;">Danh Mục</h3>
                    <div style="display:flex; flex-direction:column; gap:4px;" id="sidebarCategories">
                        <a href="#" class="filter-category-item active" data-category="all">
                            <i class="bi bi-grid-fill" style="margin-right:8px;"></i> Tất Cả (<span id="count-all">{{ $totalActiveProducts }}</span>)
                        </a>
                        @foreach($categories as $cat)
                        <a href="#" class="filter-category-item" data-category="{{ $cat->slug }}" style="display: flex; align-items: center; gap: 8px;">
                            @if($cat->image_path)
                                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" style="width: 14px; height: 14px; object-fit: contain; border-radius: 2px; flex-shrink: 0;">
                            @else
                                <i class="bi {{ catIcon($cat->slug, $cat->type) }}" style="flex-shrink: 0;"></i>
                            @endif
                            <span style="flex: 1; text-align: left;">{{ $cat->name }} (<span id="count-{{ $cat->slug }}">{{ $cat->products_count ?? 0 }}</span>)</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <button id="clearFiltersBtn" class="btn btn-ghost btn-full btn-sm" style="display:none;">✕ Xóa Bộ Lọc</button>
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
                <div class="category-tabs" style="margin-bottom:24px; overflow-x:auto; padding-bottom:4px; display:flex; gap:8px;">
                    <a href="#" class="category-tab active" data-category="all">
                        <i class="bi bi-grid-fill" style="margin-right:6px;"></i> Tất Cả
                    </a>
                    @foreach($categories as $cat)
                    <a href="#" class="category-tab" data-category="{{ $cat->slug }}" style="display: flex; align-items: center; gap: 6px;">
                        @if($cat->image_path)
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" style="width: 14px; height: 14px; object-fit: contain; border-radius: 2px; flex-shrink: 0;">
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
                    <div class="product-card-wrap"
                         data-id="{{ $prod['id'] }}"
                         data-name="{{ strtolower($prod['name']) }}"
                         data-brand="{{ strtolower($prod['brand'] ?? '') }}"
                         data-price="{{ $prod['price'] }}"
                         data-rating="{{ $prod['rating'] ?? 0 }}"
                         data-category="{{ $prod['category']['slug'] ?? 'all' }}">
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
                    <p style="color:var(--text-muted); margin-bottom:24px;">Thử tìm kiếm với từ khóa khác hoặc xem tất cả sản phẩm</p>
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
    
    const sidebarItems  = document.querySelectorAll('#sidebarCategories .filter-category-item');
    const tabItems      = document.querySelectorAll('.category-tabs .category-tab');
    const productWrappers = Array.from(document.querySelectorAll('.product-card-wrap'));
    const totalCount = productWrappers.length;

    // Update "Tất Cả" count
    document.getElementById('count-all').textContent = totalCount;

    let activeCategory = 'all';
    let searchQuery    = '';
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
            const name   = wrap.dataset.name  || '';
            const brand  = wrap.dataset.brand || '';
            const cat    = wrap.dataset.category || '';

            const matchesSearch   = !searchQuery || name.includes(searchQuery) || brand.includes(searchQuery);
            const matchesCategory = activeCategory === 'all' || cat === activeCategory;

            return matchesSearch && matchesCategory;
        });

        const visibleCount = matchingWrappers.length;
        productWrappers.forEach(wrap => wrap.style.display = 'none');

        const totalPages = Math.ceil(visibleCount / itemsPerPage);
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex   = Math.min(startIndex + itemsPerPage, visibleCount);

        for (let i = startIndex; i < endIndex; i++) {
            matchingWrappers[i].style.display = 'block';
            // Show the product immediately so it doesn't get stuck at opacity 0 due to scroll observer when loading/filtering
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

        clearFiltersBtn.style.display = (searchQuery || activeCategory !== 'all') ? 'block' : 'none';
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

    sidebarItems.forEach(item => item.addEventListener('click', e => { e.preventDefault(); updateCategoryUI(item.dataset.category); }));
    tabItems.forEach(item     => item.addEventListener('click', e => { e.preventDefault(); updateCategoryUI(item.dataset.category); }));
    
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
        updateCategoryUI('all'); 
    }
    clearFiltersBtn.addEventListener('click', resetAllFilters);
    resetAllBtn.addEventListener('click', resetAllFilters);
 
    // Read URL params on load
    const urlParams    = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    const queryParam   = urlParams.get('q') || urlParams.get('brand');
 
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