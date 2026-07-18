@extends('layouts.app')

@section('title', 'Tìm Kiếm - ' . ($settings['store_name'] ?? 'VPNStore'))

@section('content')

<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb" style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--text-muted); margin-bottom:32px;">
            <a href="{{ route('home') }}" style="color:var(--text-muted); text-decoration:none;">Trang Chủ</a>
            <span>/</span>
            <span style="color:var(--text-primary);">Tìm Kiếm</span>
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

<div class="container py-5" style="margin-bottom: 40px;">
    @php
    $results = $allProducts ?? [];
    @endphp

    @if(count($results) > 0)
    <p class="text-muted mb-4">Tìm thấy <strong>{{ count($results) }}</strong> sản phẩm phù hợp</p>
    <div class="product-grid">
        @foreach($results as $prod)
        <div class="product-card-wrap" data-slug="{{ $prod['slug'] }}" data-id="{{ $prod['id'] }}">
            @include('partials.product-card', ['product' => $prod])
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state py-5" style="text-align:center;">
        <div class="empty-state-icon text-muted" style="font-size:3rem; margin-bottom:16px;"><i class="bi bi-search"></i></div>
        <h5 style="font-size:1.2rem; font-weight:700; margin-bottom:8px;">Không tìm thấy kết quả cho "{{ request('q') }}"</h5>
        <p style="color:var(--text-muted); margin-bottom:24px;">Thử tìm với từ khóa khác: NordVPN, ExpressVPN, Surfshark...</p>
        <a href="{{ route('products') }}" class="btn btn-primary mt-3">
            <i class="bi bi-grid me-2"></i>Xem Tất Cả Sản Phẩm
        </a>
    </div>
    @endif
</div>

@endsection
