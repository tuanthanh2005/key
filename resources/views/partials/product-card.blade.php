@php
    $isArr = is_array($product);
    $prodId = $isArr ? $product['id'] : $product->id;
    $prodName = $isArr ? $product['name'] : $product->name;
    $prodSlug = $isArr ? $product['slug'] : $product->slug;
    $prodPrice = $isArr ? $product['price'] : $product->price;
    $prodOldPrice = $isArr ? ($product['old_price'] ?? null) : $product->old_price;
    $prodPlan = $isArr ? ($product['plan'] ?? null) : $product->plan;
    $prodImage = $isArr ? ($product['image_url'] ?? $product['image_path'] ?? null) : ($product->image_url ?: $product->image_path);
    $prodIsPopular = $isArr ? ($product['is_popular'] ?? false) : $product->is_popular;
    $prodRating = $isArr ? ($product['rating'] ?? 0) : ($product->rating ?? 0);
    $prodReviews = $isArr ? ($product['reviews'] ?? 0) : ($product->reviews ?? 0);
    $prodSold = $isArr ? ($product['sold'] ?? $product['sold_count'] ?? 0) : ($product->sold ?? $product->sold_count ?? 0);
    $prodStock = $isArr ? ($product['stock'] ?? 0) : $product->stock;
    $prodColor = $isArr ? ($product['color'] ?? '#2563eb') : $product->color;
    $prodBrand = $isArr ? ($product['brand'] ?? '') : $product->brand;
    $prodRequireEmail = $isArr ? ($product['require_upgrade_email'] ?? false) : ($product->require_upgrade_email ?? false);
    
    // Real rating & sold count
    $realRating = (float)$prodRating;
    $realSold = (int)$prodSold;
    
    // Category relation
    $cat = $isArr ? ($product['category'] ?? null) : $product->category;
    $catName = $cat ? (is_array($cat) ? $cat['name'] : $cat->name) : '';
    $catSlug = $cat ? (is_array($cat) ? $cat['slug'] : $cat->slug) : '';

    // Calculate discount
    $discount = 0;
    if ($prodOldPrice && $prodOldPrice > $prodPrice) {
        $discount = (int) round((($prodOldPrice - $prodPrice) / $prodOldPrice) * 100);
    }
@endphp
<div class="product-card animate-on-scroll">
    <div class="product-card-image">
        <a href="{{ route('product.detail', $catSlug ?: $prodSlug) }}" style="display:block; width:100%; height:100%;">
            @if($prodImage)
                <img src="{{ asset($prodImage) }}" alt="{{ $prodName }}" loading="lazy"
                     onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';"
                     style="width:100%; height:100%; object-fit:cover; display:block;">
                <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; background:linear-gradient(135deg, {{ $prodColor }}, {{ $prodColor }}77);">
                    <div style="text-align:center;">
                        <div style="font-size:2.5rem; line-height:1; color:#fff;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div style="font-size:0.6rem; color:#fff; margin-top:8px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;">{{ $catName ?: $prodBrand }}</div>
                    </div>
                </div>
            @else
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, {{ $prodColor }}, {{ $prodColor }}77);">
                    <div style="text-align:center;">
                        <div style="font-size:2.5rem; line-height:1; color:#fff;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div style="font-size:0.6rem; color:#fff; margin-top:8px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;">{{ $catName ?: $prodBrand }}</div>
                    </div>
                </div>
            @endif
        </a>

        <div class="product-badge-left">
            @if($prodIsPopular)
                <span class="badge badge-popular"><i class="bi bi-star-fill text-warning"></i> Popular</span>
            @elseif($prodPlan === '1year' || $prodPlan === '2year')
                <span class="badge badge-featured"><i class="bi bi-fire"></i> Hot</span>
            @endif
        </div>

        @if($discount > 0)
            <div class="product-badge-right">
                <span class="badge badge-sale">-{{ $discount }}%</span>
            </div>
        @endif
    </div>

    <a href="{{ route('product.detail', $catSlug ?: $prodSlug) }}" style="flex:1; display:flex; flex-direction:column;">
        <div class="product-card-body">
            @if($catName)
                <span class="product-category"><i class="bi bi-tag-fill" style="margin-right:4px;"></i>{{ $catName }}</span>
            @else
                <span class="product-category"><i class="bi bi-shield-check" style="margin-right:4px;"></i>{{ $prodBrand }}</span>
            @endif

            <h3 class="product-name">{{ $prodName }}</h3>

            @if($prodPlan)
                <div style="display:flex; align-items:center; gap:6px; font-size:0.75rem; color:var(--text-muted); margin-bottom:8px;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Gói {{ $prodPlan === '1month' ? '1 Tháng' : ($prodPlan === '1year' ? '1 Năm' : ($prodPlan === '2year' ? '2 Năm' : $prodPlan)) }}
                </div>
            @endif

            <div class="product-rating" style="margin-bottom:8px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:4px; font-size:0.78rem;">
                <div style="display:flex; align-items:center; gap:4px;">
                    <div class="stars" style="display:flex; gap:1px;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill text-warning"></i>
                        @endfor
                    </div>
                    <span style="font-weight:700; color:var(--text-primary);">{{ $realRating > 0 ? number_format($realRating, 1) : '0' }}</span>
                </div>
                <span style="font-size:0.75rem; color:var(--text-muted);">Đã bán {{ number_format($realSold) }}</span>
            </div>

            <div class="product-pricing" style="margin-top:auto;">
                <span class="price-current" style="font-size:1.1rem; font-weight:800; color:var(--primary-light);">{{ number_format($prodPrice) }}đ</span>
                @if($prodOldPrice && $prodOldPrice > $prodPrice)
                    <span class="price-original" style="font-size:0.8rem; text-decoration:line-through; color:var(--text-muted); margin-left:8px;">{{ number_format($prodOldPrice) }}đ</span>
                @endif
            </div>
        </div>
    </a>

    <div class="product-card-footer" style="display:flex; gap:8px; align-items:center;">
        <button class="btn btn-primary"
                style="flex:1; height:38px; border-radius:20px; padding:0 12px; justify-content:center; font-size:0.85rem;"
                data-add-cart
                data-id="{{ $prodId }}"
                data-name="{{ $prodName }}"
                data-brand="{{ $prodBrand }}"
                data-plan="{{ $prodPlan ?: '1month' }}"
                data-price="{{ $prodPrice }}"
                data-color="{{ $prodColor }}"
                data-slug="{{ $prodSlug }}"
                data-image="{{ $prodImage ? asset($prodImage) : '' }}"
                data-require-email="{{ $prodRequireEmail ? '1' : '0' }}"
                @if(($prodStock ?? 0) <= 0) disabled @endif>
            @if(($prodStock ?? 0) > 0)
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:5px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Thêm Vào Giỏ
            @else
                Hết Hàng
            @endif
        </button>

        <button type="button" class="btn btn-outline btn-wishlist-card" data-wishlist data-id="{{ $prodId }}" aria-label="Thêm vào yêu thích" title="Lưu vào sản phẩm yêu thích" style="width:38px; height:38px; min-width:38px; padding:0; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; flex-shrink:0;">
            <i class="bi bi-heart"></i>
        </button>
    </div>
</div>
