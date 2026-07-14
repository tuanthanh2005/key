@extends('admin.layouts.admin')

@section('title', 'Quản Lý Bài Viết')
@section('page_title', 'Quản Lý Bài Viết')
@section('breadcrumb', 'Admin / Bài Viết')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="admin-card border-0 shadow-sm" style="border-radius:15px;">
    <div class="admin-card-header border-bottom py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="background:#fafafa;">
        <div class="admin-card-title m-0 d-flex align-items-center gap-2" style="font-size:14.5px;">
            <i class="bi bi-journal-text text-primary" style="font-size:18px;"></i>
            Danh Sách Bài Viết
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Search Form -->
            <form action="{{ route('admin.posts.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm kiếm tiêu đề..." style="border-radius:8px; width: 200px;">
                <button type="submit" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm fw-600 px-3" style="border-radius:8px;">
                <i class="bi bi-plus-lg me-1"></i>Viết Bài Mới
            </a>
        </div>
    </div>

    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:13.5px;">
                <thead>
                    <tr class="table-light" style="font-weight:700;">
                        <th class="ps-4 py-3" style="width: 50px;">ID</th>
                        <th class="py-3" style="width: 80px;">Ảnh</th>
                        <th class="py-3">Tiêu Đề</th>
                        <th class="py-3">Slug</th>
                        <th class="py-3">Trạng Thái</th>
                        <th class="py-3">Ngày Tạo</th>
                        <th class="py-3 text-end pe-4" style="width: 150px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td class="ps-4 text-muted fw-600">{{ $post->id }}</td>
                        <td>
                            @if($post->image_path)
                                <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}" class="rounded" style="width:50px;height:35px;object-fit:cover">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px;height:35px;color:var(--gray-400)">
                                    <i class="bi bi-image" style="font-size:14px"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-700 text-dark">{{ $post->title }}</div>
                            @if($post->meta_title)
                            <div class="text-muted" style="font-size:11px;">
                                <i class="bi bi-search me-1"></i>{{ $post->meta_title }}
                            </div>
                            @endif
                        </td>
                        <td><code class="text-secondary">{{ $post->slug }}</code></td>
                        <td>
                            @if($post->status === 'published')
                                <span class="badge bg-success-subtle text-success fw-700" style="font-size:11px; border-radius:6px; padding: 4px 8px;">Đã Xuất Bản</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary fw-700" style="font-size:11px; border-radius:6px; padding: 4px 8px;">Bản Nháp</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px; padding: 2px 8px;" title="Chỉnh sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px; padding: 2px 8px;" title="Xóa">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-journal-x d-block mb-2" style="font-size:24px"></i>
                            Không có bài viết nào được tìm thấy.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($posts->hasPages())
    <div class="admin-card-footer border-top py-3 px-4">
        {{ $posts->links() }}
    </div>
    @endif
</div>

@endsection
