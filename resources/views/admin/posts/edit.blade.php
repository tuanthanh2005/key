@extends('admin.layouts.admin')

@section('title', 'Sửa Bài Viết')
@section('page_title', 'Sửa Bài Viết')
@section('breadcrumb', 'Admin / Bài Viết / Sửa')

@section('content')

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>Vui lòng kiểm tra lại thông tin nhập.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <ul class="mb-0 mt-2 small">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="postForm">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <!-- Main Form Content -->
        <div class="col-12 col-lg-8">
            <div class="admin-card border-0 shadow-sm mb-4" style="border-radius:15px; background: #fff;">
                <div class="admin-card-header border-bottom py-3 px-4" style="background:#fafafa;">
                    <div class="admin-card-title m-0 fw-700" style="font-size:14px; color: var(--gray-800);">
                        Nội Dung Bài Viết
                    </div>
                </div>
                <div class="admin-card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Tiêu Đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title-input" class="form-control" placeholder="Nhập tiêu đề bài viết..." value="{{ old('title', $post->title) }}" required style="border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Đường Dẫn Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="slug-input" class="form-control" placeholder="vi-du-duong-dan" value="{{ old('slug', $post->slug) }}" required style="border-radius:10px;">
                        <small class="text-muted" style="font-size:11px;">URL của bài viết sẽ có định dạng: vpnstore.pro/tin-tuc/vi-du-duong-dan</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Tóm Tắt Ngắn</label>
                        <textarea name="summary" class="form-control" rows="3" placeholder="Nhập tóm tắt ngắn của bài viết (hiển thị ở trang danh sách)..." style="border-radius:10px;">{{ old('summary', $post->summary) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Nội Dung Chi Tiết <span class="text-danger">*</span></label>
                        
                        <!-- Rich Text Editor Container -->
                        <div id="editor-container" style="height: 400px; border-radius: 0 0 10px 10px;">
                            {!! old('content', $post->content) !!}
                        </div>
                        <input type="hidden" name="content" id="content-input" value="{{ old('content', $post->content) }}">
                    </div>
                </div>
            </div>

            <!-- SEO Settings Card -->
            <div class="admin-card border-0 shadow-sm" style="border-radius:15px; background: #fff;">
                <div class="admin-card-header border-bottom py-3 px-4" style="background:#fafafa;">
                    <div class="admin-card-title m-0 fw-700" style="font-size:14px; color: var(--gray-800);">
                        Cấu Hình SEO Google
                    </div>
                </div>
                <div class="admin-card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Meta Title (Tiêu đề Google)</label>
                        <input type="text" name="meta_title" class="form-control" placeholder="Độ dài khuyên dùng 50-60 ký tự" value="{{ old('meta_title', $post->meta_title) }}" style="border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Meta Description (Mô tả Google)</label>
                        <textarea name="meta_description" class="form-control" rows="3" placeholder="Mô tả tóm tắt ngắn cho kết quả tìm kiếm Google (150-160 ký tự)" style="border-radius:10px;">{{ old('meta_description', $post->meta_description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Từ Khóa SEO (Keywords)</label>
                        <input type="text" name="meta_keywords" class="form-control" placeholder="Cách nhau bằng dấu phẩy. Ví dụ: nordvpn gia re, tai khoan vpn" value="{{ old('meta_keywords', $post->meta_keywords) }}" style="border-radius:10px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="col-12 col-lg-4">
            <div class="admin-card border-0 shadow-sm mb-4" style="border-radius:15px; background: #fff;">
                <div class="admin-card-header border-bottom py-3 px-4" style="background:#fafafa;">
                    <div class="admin-card-title m-0 fw-700" style="font-size:14px; color: var(--gray-800);">
                        Đăng Bài Viết
                    </div>
                </div>
                <div class="admin-card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Trạng Thái Xuất Bản</label>
                        <select name="status" class="form-select" style="border-radius:10px;">
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Bản Nháp (Draft)</option>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Xuất Bản (Published)</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary w-50 py-2.5" style="border-radius:10px;">Hủy Bỏ</a>
                        <button type="submit" class="btn btn-primary w-50 py-2.5 fw-600" style="border-radius:10px;">Cập Nhật</button>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="admin-card border-0 shadow-sm" style="border-radius:15px; background: #fff;">
                <div class="admin-card-header border-bottom py-3 px-4" style="background:#fafafa;">
                    <div class="admin-card-title m-0 fw-700" style="font-size:14px; color: var(--gray-800);">
                        Ảnh Đại Diện
                    </div>
                </div>
                <div class="admin-card-body p-4">
                    <div class="mb-3 text-center">
                        <div id="image-preview-container" class="bg-light rounded d-flex align-items-center justify-content-center border-dashed" style="width:100%; height:200px; border:2px dashed var(--gray-300); overflow: hidden;">
                            @if($post->image_path)
                                <img id="image-preview" src="{{ asset($post->image_path) }}" alt="Preview" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                                <div class="text-center p-3 text-muted d-none" id="preview-placeholder">
                                    <i class="bi bi-image" style="font-size:36px;"></i>
                                    <div class="small mt-2">Chưa chọn ảnh đại diện</div>
                                </div>
                            @else
                                <div class="text-center p-3 text-muted" id="preview-placeholder">
                                    <i class="bi bi-image" style="font-size:36px;"></i>
                                    <div class="small mt-2">Chưa chọn ảnh đại diện</div>
                                </div>
                                <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded d-none" style="max-height: 100%; object-fit: contain;">
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Chọn File Ảnh Mới</label>
                        <input type="file" name="image" id="image-file" class="form-control" accept="image/*" style="border-radius:10px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Include Quill Styles and Scripts from CDN -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Initialize Quill Editor
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [2, 3, 4, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // 2. Set manual slug input tracking
        const slugInput = document.getElementById('slug-input');
        slugInput.dataset.manual = 'true'; // Keep manually true for edits by default

        // 3. Image Preview
        const imageFile = document.getElementById('image-file');
        const imagePreview = document.getElementById('image-preview');
        const previewPlaceholder = document.getElementById('preview-placeholder');

        imageFile.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    imagePreview.setAttribute('src', this.result);
                    imagePreview.classList.remove('d-none');
                    if (previewPlaceholder) {
                        previewPlaceholder.classList.add('d-none');
                    }
                });
                reader.readAsDataURL(file);
            }
        });

        // 4. Form Submit handler: copy content
        const form = document.getElementById('postForm');
        const contentInput = document.getElementById('content-input');

        form.addEventListener('submit', function(e) {
            contentInput.value = quill.root.innerHTML;
        });
    });
</script>
@endsection
