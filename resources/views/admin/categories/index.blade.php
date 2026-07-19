@extends('admin.layouts.admin')
@section('title', 'Quản Lý Danh Mục')
@section('page_title', 'Quản Lý Danh Mục')
@section('breadcrumb', 'Admin / Danh Mục')

@section('content')

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px; font-size:13.5px">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

<div class="row g-4">
    <!-- ADD CATEGORY FORM -->
    <div class="col-12 col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-folder-plus text-primary"></i>
                    Tạo Danh Mục Mới
                </div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: VPN Giá Rẻ" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Loại Danh Mục <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            @foreach($categoryTypes as $t)
                                <option value="{{ $t->slug }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh Danh Mục</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text" style="font-size: 11px; color: var(--text-muted);">Kích thước khuyên dùng: 200x200px (tỷ lệ 1:1) dạng PNG hoặc SVG.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Đường Dẫn Slug <small class="text-muted">(Để trống tự sinh)</small></label>
                        <input type="text" name="slug" class="form-control" placeholder="Ví dụ: vpn-gia-re">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu Đề SEO <small class="text-muted">(Nếu có)</small></label>
                        <input type="text" name="seo_title" class="form-control" placeholder="Ví dụ: Mua VPN Giá Rẻ Nhất 2026">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô Tả SEO <small class="text-muted">(Nếu có)</small></label>
                        <textarea name="seo_description" class="form-control" rows="3" placeholder="Ví dụ: Dịch vụ cung cấp tài khoản VPN giá rẻ, bảo hành 1 đổi 1..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold" style="border-radius:10px;">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Danh Mục
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- CATEGORIES LIST -->
    <div class="col-12 col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="admin-card-title">
                    <i class="bi bi-folder-fill text-primary"></i>
                    Danh Sách Danh Mục
                </div>
                <span class="admin-badge admin-badge-primary">
                    {{ count($categories) }} danh mục
                </span>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Tên Danh Mục</th>
                                <th>Loại</th>
                                <th>Slug</th>
                                <th class="text-center">Số Sản Phẩm</th>
                                <th class="text-end" style="width: 150px;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse($categories as $cat)
                             <tr>
                                 <td class="text-muted fw-bold">{{ $cat->id }}</td>
                                 <td>
                                     <div class="d-flex align-items-center gap-2">
                                         @if($cat->image_path)
                                             <img src="{{ $cat->image_url }}" style="width: 32px; height: 32px; object-fit: contain; border-radius: 6px; border: 1px solid var(--admin-border);">
                                         @else
                                             <div style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: var(--admin-bg); border-radius: 6px; color: var(--primary);">
                                                 <i class="bi {{ $cat->type === 'proxy' ? 'bi-tag-fill' : 'bi-shield-lock-fill' }}"></i>
                                             </div>
                                         @endif
                                         <div>
                                             <div class="fw-bold text-dark">{{ $cat->name }}</div>
                                             @if($cat->seo_title)
                                             <div class="text-muted" style="font-size:11px; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                 <i class="bi bi-search me-1"></i>{{ $cat->seo_title }}
                                             </div>
                                             @endif
                                         </div>
                                     </div>
                                 </td>
                                 <td>
                                     <span class="badge {{ $cat->type === 'proxy' ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary' }} fw-bold" style="font-size:11px; border-radius:6px; text-transform:uppercase; padding: 4px 8px;">{{ $cat->type }}</span>
                                 </td>
                                 <td>
                                     <code style="font-size:11.5px; color:var(--admin-muted);">{{ $cat->slug }}</code>
                                 </td>
                                 <td class="text-center fw-bold text-primary">{{ $cat->products_count }}</td>
                                 <td class="text-end" style="padding-right: 24px;">
                                     <button class="btn btn-outline-primary btn-sm p-1 px-2 me-1 edit-cat-btn" 
                                             data-id="{{ $cat->id }}" 
                                             data-name="{{ $cat->name }}" 
                                             data-type="{{ $cat->type }}"
                                             data-slug="{{ $cat->slug }}"
                                             data-title="{{ $cat->seo_title }}"
                                             data-desc="{{ $cat->seo_description }}"
                                             data-image="{{ $cat->image_url ?: '' }}"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#editCategoryModal"
                                             style="border-radius:8px;"
                                             title="Sửa">
                                         <i class="bi bi-pencil-square"></i>
                                     </button>
                                     <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Các sản phẩm thuộc danh mục sẽ bị hủy gán.')" style="margin:0;">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="btn btn-outline-danger btn-sm p-1 px-2" style="border-radius:8px;" title="Xóa">
                                             <i class="bi bi-trash"></i>
                                         </button>
                                     </form>
                                 </td>
                             </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x display-6 mb-2 d-block"></i>
                                    Chưa có danh mục nào được tạo.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EDIT CATEGORY MODAL -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; overflow:hidden; background:var(--admin-surface); box-shadow:0 8px 30px rgba(0,0,0,0.12);">
            <div class="modal-header border-bottom py-3 px-4" style="border-color:var(--admin-border);">
                <h6 class="modal-title fw-bold text-dark" id="editCategoryModalLabel"><i class="bi bi-pencil-square text-primary me-2"></i>Cập Nhật Danh Mục</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" method="POST" style="margin:0;" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4" style="display:flex; flex-direction:column; gap:16px;">
                    <div class="form-group">
                        <label class="form-label fw-bold text-dark">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold text-dark">Loại Danh Mục <span class="text-danger">*</span></label>
                        <select name="type" id="edit_type" class="form-select" required>
                            @foreach($categoryTypes as $t)
                                <option value="{{ $t->slug }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold text-dark">Ảnh Danh Mục</label>
                        <div id="edit_image_preview" class="mb-2" style="display:none;">
                            <img src="" id="edit_image_img" style="width: 50px; height: 50px; object-fit: contain; border-radius: 8px; border: 1px solid var(--admin-border);">
                        </div>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text" style="font-size: 11px; color: var(--text-muted);">Kích thước khuyên dùng: 200x200px (tỷ lệ 1:1) dạng PNG hoặc SVG.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold text-dark">Đường Dẫn Slug</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold text-dark">Tiêu Đề SEO</label>
                        <input type="text" name="seo_title" id="edit_seo_title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold text-dark">Mô Tả SEO</label>
                        <textarea name="seo_description" id="edit_seo_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4" style="background:var(--admin-bg); border-color:var(--admin-border);">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="border-radius:20px;">Đóng</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4" style="border-radius:20px;">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editCategoryForm');
    const editButtons = document.querySelectorAll('.edit-cat-btn');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const type = this.getAttribute('data-type');
            const slug = this.getAttribute('data-slug');
            const title = this.getAttribute('data-title');
            const desc = this.getAttribute('data-desc');
            const image = this.getAttribute('data-image');

            editForm.action = `/admin/categories/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_slug').value = slug;
            document.getElementById('edit_seo_title').value = title || '';
            document.getElementById('edit_seo_description').value = desc || '';

            const previewDiv = document.getElementById('edit_image_preview');
            const previewImg = document.getElementById('edit_image_img');
            if (image) {
                previewImg.src = image;
                previewDiv.style.display = 'block';
            } else {
                previewDiv.style.display = 'none';
            }
        });
    });
});
</script>
@endsection