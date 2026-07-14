@extends('layouts.app')

@section('title', 'Tin Tức & Hướng Dẫn VPN - VPNStore')
@section('meta_description', 'Đọc tin tức công nghệ mới nhất, các hướng dẫn sử dụng VPN, thủ thuật bảo mật trực tuyến và review dịch vụ VPN chính hãng từ chuyên gia.')
@section('meta_keywords', 'tin tuc vpn, huong dan vpn, thu thuat vpn, bao mat vpn')

@section('content')
<!-- Page Header -->
<section class="section-sm text-center" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 40%, #ede9fe 100%); border-bottom: 1px solid var(--gray-200); padding: 50px 0;">
    <div class="container">
        <h1 class="fw-800 text-dark mb-2" style="font-family:'Poppins', sans-serif; font-size: 32px;">Tin Tức & Hướng Dẫn</h1>
        <p class="text-muted mx-auto" style="max-width: 600px; font-size: 14.5px;">Cập nhật các tin tức công nghệ mới nhất, hướng dẫn bảo mật và đánh giá các giải pháp VPN/Proxy hàng đầu.</p>
    </div>
</section>

<!-- Blog List Section -->
<section class="section-md bg-light py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($posts as $post)
            <div class="col-12 col-md-6 col-lg-4">
                <article class="card h-100 border-0 shadow-sm transition hover-shadow" style="border-radius:16px; overflow:hidden; display:flex; flex-direction:column;">
                    <!-- Post Image -->
                    <a href="{{ route('posts.show', $post->slug) }}" class="d-block" style="height:200px; overflow:hidden;">
                        @if($post->image_path)
                            <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover transition-transform hover-scale">
                        @else
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--primary-light) 0%, #ede9fe 100%); color: var(--primary);">
                                <i class="bi bi-shield-lock" style="font-size:42px;"></i>
                                <span class="small mt-2 text-muted fw-600">VPNStore Security</span>
                            </div>
                        @endif
                    </a>
                    
                    <!-- Post Content -->
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2 text-muted" style="font-size:12px;">
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $post->created_at->format('d/m/Y') }}</span>
                            <span>•</span>
                            <span><i class="bi bi-person me-1"></i>Ban Biên Tập</span>
                        </div>
                        
                        <h2 class="card-title fw-700 h5 mb-2 line-clamp-2" style="font-size: 18px; line-height: 1.4;">
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                {{ $post->title }}
                            </a>
                        </h2>
                        
                        <p class="card-text text-muted line-clamp-3 mb-4" style="font-size: 13.5px; line-height: 1.6;">
                            {{ $post->summary ?? 'Đọc bài viết chi tiết để hiểu rõ hơn về chủ đề này...' }}
                        </p>
                        
                        <div class="mt-auto">
                            <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-link text-primary p-0 fw-600 text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 13.5px;">
                                Đọc Chi Tiết <i class="bi bi-arrow-right" style="font-size: 12px;"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-journal-x d-block mb-3 text-muted" style="font-size: 48px;"></i>
                <h3 class="fw-700">Chưa có bài viết nào</h3>
                <p>Nội dung đang được cập nhật, vui lòng quay lại sau.</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3 rounded-pill px-4">Quay Lại Trang Chủ</a>
            </div>
            @endforelse
        </div>

        @if($posts->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</section>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }
    .hover-scale:hover {
        transform: scale(1.05);
    }
    .transition {
        transition: all 0.3s ease;
    }
    .transition-transform {
        transition: transform 0.3s ease;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
    .hover-primary:hover {
        color: var(--primary) !important;
    }
</style>
@endsection
