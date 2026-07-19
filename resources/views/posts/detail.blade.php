@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' - VPNStore')
@section('meta_description', $post->meta_description ?: ($post->summary ?: Str::limit(strip_tags($post->content), 150)))
@section('meta_keywords', $post->meta_keywords ?: 'huong dan vpn, thu thuat vpn, bao mat mang')
@section('og_image', $post->image_path ? asset($post->image_path) : '')

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ request()->url() }}"
  },
  "headline": "{{ $post->title }}",
  "description": "{{ $post->meta_description ?: ($post->summary ?: Str::limit(strip_tags($post->content), 150)) }}",
  "image": "{{ $post->image_path ? asset($post->image_path) : asset('favicon.ico') }}",
  "datePublished": "{{ $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": {
    "@@type": "Organization",
    "name": "{{ $settings['store_name'] ?? 'VPNStore' }}",
    "url": "{{ route('home') }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "{{ $settings['store_name'] ?? 'VPNStore' }}",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ !empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico') }}"
    }
  }
}
</script>
@endsection

@section('content')
<!-- Breadcrumbs -->
<div class="bg-light py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px; display: flex; flex-wrap: wrap; list-style: none; padding: 0; margin: 0; gap: 8px; align-items: center;">
                <li class="breadcrumb-item" style="display: flex; align-items: center; gap: 8px;"><a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--primary-light);">Trang Chủ</a><span class="text-muted">/</span></li>
                <li class="breadcrumb-item" style="display: flex; align-items: center; gap: 8px;"><a href="{{ route('posts.index') }}" class="text-decoration-none" style="color: var(--primary-light);">Bài Viết</a><span class="text-muted">/</span></li>
                <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 250px; color: var(--text-secondary);">{{ $post->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Section -->
<section class="py-5" style="background:#f8fafc">
    <div class="container">
        <div class="row g-4">
            
            <!-- Left Column: Article content -->
            <div class="col-12 col-lg-8">
                <article class="bg-white p-4 p-md-5 shadow-sm" style="border-radius:16px;">
                    <!-- Post Meta -->
                    <div class="d-flex align-items-center gap-2 mb-3 text-muted" style="font-size:13px;">
                        <span><i class="bi bi-calendar3 me-1"></i>{{ $post->created_at->format('d/m/Y H:i') }}</span>
                        <span>•</span>
                        <span><i class="bi bi-person me-1"></i>Tác giả: Ban Biên Tập</span>
                    </div>

                    <!-- Heading -->
                    <h1 class="fw-800 text-dark mb-4" style="font-family:'Poppins', sans-serif; font-size: 28px; line-height: 1.35;">{{ $post->title }}</h1>

                    <!-- Featured Image -->
                    @if($post->image_path)
                    <div class="mb-4 text-center rounded overflow-hidden" style="max-height: 400px;">
                        <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}" class="img-fluid w-100" style="object-fit: cover;">
                    </div>
                    @endif

                    <!-- Article Body Content -->
                    <div class="article-content">
                        {!! $post->content !!}
                    </div>
                </article>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="col-12 col-lg-4">
                <!-- Related/Recent Articles Widget -->
                <div class="bg-white p-4 shadow-sm mb-4" style="border-radius:16px;">
                    <h3 class="fw-700 h5 mb-3 pb-2 border-bottom text-dark">Bài Viết Mới Nhất</h3>
                    <div class="d-flex flex-column gap-3">
                        @forelse($recentPosts as $recent)
                        <div class="d-flex gap-3 align-items-start">
                            <a href="{{ route('posts.show', $recent->slug) }}" class="flex-shrink-0" style="width: 70px; height: 50px; overflow:hidden; border-radius: 8px;">
                                @if($recent->image_path)
                                    <img src="{{ asset($recent->image_path) }}" alt="{{ $recent->title }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 bg-light-subtle d-flex align-items-center justify-content-center" style="background:#e2e8f0; color:#64748b;">
                                        <i class="bi bi-shield-lock" style="font-size:16px;"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="flex-grow-1 min-w-0">
                                <h4 class="fw-600 mb-1" style="font-size:13.5px; line-height:1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <a href="{{ route('posts.show', $recent->slug) }}" class="text-dark text-decoration-none hover-primary">
                                        {{ $recent->title }}
                                    </a>
                                </h4>
                                <span class="text-muted" style="font-size:11px;">{{ $recent->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-muted small">Không có bài viết mới khác.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Featured Products Widget -->
                <div class="bg-white p-4 shadow-sm" style="border-radius:16px;">
                    <h3 class="fw-700 h5 mb-3 pb-2 border-bottom text-dark">Sản Phẩm Hot</h3>
                    <div class="d-flex flex-column gap-3">
                        @forelse($hotProducts as $prod)
                        <div class="d-flex justify-content-between align-items-center p-2 rounded hover-bg" style="border: 1px solid rgba(0,0,0,0.05); gap: 12px;">
                            <div class="d-flex align-items-center gap-2 min-w-0">
                                @if($prod->image_path)
                                    <img src="{{ asset($prod->image_path) }}" alt="{{ $prod->name }}" style="width:24px; height:24px; object-fit:contain; border-radius:4px;" class="flex-shrink-0">
                                @else
                                    <span class="brand-dot" style="background:var(--primary); width:8px; height:8px; border-radius:50%; display:inline-block;" class="flex-shrink-0"></span>
                                @endif
                                <span class="fw-700 text-dark small text-truncate" title="{{ $prod->name }}">{{ $prod->name }}</span>
                            </div>
                            <a href="{{ route('product.detail', $prod->slug) }}" class="btn btn-sm btn-primary py-1 px-3 fw-600 rounded-pill flex-shrink-0" style="font-size:12px;">Xem Thêm</a>
                        </div>
                        @empty
                        <div class="text-muted small">Đang cập nhật sản phẩm...</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Custom styling for AI-generated rich-text format in articles -->
<style>
    .article-content {
        font-family: 'Inter', sans-serif;
        font-size: 15.5px;
        line-height: 1.85;
        color: var(--gray-800);
    }
    .article-content p {
        margin-bottom: 20px;
        color: #334155;
    }
    .article-content h2 {
        font-size: 21px;
        font-weight: 800;
        margin-top: 35px;
        margin-bottom: 15px;
        color: var(--gray-900);
        position: relative;
        padding-left: 12px;
        font-family: 'Poppins', sans-serif;
    }
    .article-content h2::before {
        content: '';
        position: absolute;
        left: 0;
        top: 4px;
        bottom: 4px;
        width: 4px;
        background: var(--primary);
        border-radius: 2px;
    }
    .article-content h3 {
        font-size: 18px;
        font-weight: 700;
        margin-top: 25px;
        margin-bottom: 12px;
        color: var(--gray-900);
        font-family: 'Poppins', sans-serif;
    }
    .article-content h4 {
        font-size: 15.5px;
        font-weight: 700;
        margin-top: 20px;
        margin-bottom: 10px;
        color: var(--gray-900);
    }
    .article-content ul, .article-content ol {
        margin-bottom: 20px;
        padding-left: 20px;
        color: #334155;
    }
    .article-content li {
        margin-bottom: 8px;
    }
    .article-content blockquote {
        padding: 15px 20px;
        margin: 25px 0;
        background: #f1f5f9;
        border-left: 4px solid var(--primary);
        border-radius: 4px;
        font-style: italic;
        color: #475569;
    }
    .article-content blockquote p:last-child {
        margin-bottom: 0;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 25px auto;
        display: block;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .article-content table {
        width: 100%;
        margin-bottom: 25px;
        border-collapse: collapse;
        font-size: 14px;
    }
    .article-content th, .article-content td {
        padding: 10px 12px;
        border: 1px solid var(--gray-200);
    }
    .article-content th {
        background-color: var(--gray-50);
        font-weight: 700;
        color: var(--gray-900);
    }
    .article-content tr:nth-child(even) {
        background-color: #f8fafc;
    }
    .article-content pre {
        background: #0f172a;
        color: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        overflow-x: auto;
        margin-bottom: 25px;
    }
    .article-content code {
        font-family: SFMono-Regular, Consolas, "Liberation Mono", Menlo, monospace;
        font-size: 85%;
        padding: 2px 6px;
        background-color: #f1f5f9;
        color: #d63384;
        border-radius: 4px;
    }
    .article-content pre code {
        padding: 0;
        background-color: transparent;
        color: inherit;
        font-size: 13px;
    }
    .hover-primary:hover {
        color: var(--primary) !important;
    }
    .hover-bg:hover {
        background-color: #f8fafc;
    }
</style>
@endsection
