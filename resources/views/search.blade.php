@extends('layouts.app')

@section('title', 'Tìm Kiếm - VPNStore')

@section('content')

<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
                <li class="breadcrumb-item active">Tìm Kiếm</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-header">
    <div class="container">
        <h1 class="section-title mb-2">
            <i class="bi bi-search me-3 text-primary"></i>Kết Quả Tìm Kiếm
        </h1>
        @if(request('q'))
        <p class="text-muted mb-0">
            Tìm kiếm: <strong>"{{ request('q') }}"</strong>
        </p>
        @endif
    </div>
</div>

<div class="container py-5">
    @php
    $results = $allProducts ?? [];
    @endphp

    @if(count($results) > 0)
    <p class="text-muted mb-4">Tìm thấy <strong>{{ count($results) }}</strong> sản phẩm phù hợp</p>
    <div class="row g-4">
        @foreach($results as $prod)
        <div class="col-lg-4 col-md-6">
            <div class="product-card">
                <div class="product-card-badge">
                    @if($prod['price'] < ($prod['old_price'] ?? 0))<span class="badge-sale">Sale</span>@endif
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
                            @for($i=1;$i<=5;$i++)<i class="bi {{ $i <= floor($prod['rating']) ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor
                        </div>
                        <span class="rating-count ms-1">({{ $prod['reviews'] }})</span>
                    </div>
                    <div class="product-price-wrap">
                        <div class="product-price-old">{{ number_format($prod['old_price']) }}đ</div>
                        <div class="product-price">{{ number_format($prod['price']) }}đ</div>
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
                        <button class="btn-wishlist" data-add-cart data-id="{{ $prod['id'] }}" data-name="{{ $prod['name'] }}" data-brand="{{ $prod['brand'] }}" data-plan="{{ $prod['plan'] }}" data-price="{{ $prod['price'] }}" data-color="{{ $prod['color'] }}" data-slug="{{ $prod['slug'] }}" title="Thêm Vào Giỏ">
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
    @else
    <div class="empty-state py-5">
        <div class="empty-state-icon"><i class="bi bi-search"></i></div>
        <h5>Không tìm thấy kết quả cho "{{ request('q') }}"</h5>
        <p>Thử tìm với từ khóa khác: NordVPN, ExpressVPN, Surfshark...</p>
        <a href="{{ route('products') }}" class="btn btn-primary mt-3 rounded-pill px-4">
            <i class="bi bi-grid me-2"></i>Xem Tất Cả Sản Phẩm
        </a>
    </div>
    @endif
</div>

@endsection
