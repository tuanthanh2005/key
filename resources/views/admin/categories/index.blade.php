@extends('admin.layouts.admin')

@section('title', 'Quản Lý Danh Mục')
@section('page_title', 'Quản Lý Danh Mục')
@section('breadcrumb', 'Admin / Danh Mục')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row g-4">
    <!-- ADD CATEGORY FORM -->
    <div class="col-12 col-lg-4">
        <div class="admin-card border-0 shadow-sm" style="border-radius:15px;">
            <div class="admin-card-header border-bottom py-3 px-4" style="background:#fafafa;">
                <div class="admin-card-title m-0 d-flex align-items-center gap-2" style="font-size:14.5px;">
                    <i class="bi bi-folder-plus text-primary" style="font-size:18px;"></i>
                    Tạo Danh Mục Mới
                </div>
            </div>
            <div class="admin-card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: VPN Giá Rẻ" required style="border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Đường Dẫn Slug <small class="text-muted">(Để trống tự sinh)</small></label>
                        <input type="text" name="slug" class="form-control" placeholder="Ví dụ: vpn-gia-re" style="border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Tiêu Đề SEO <small class="text-muted">(Nếu có)</small></label>
                        <input type="text" name="seo_title" class="form-control" placeholder="Ví dụ: Mua VPN Giá Rẻ Nhất 2026" style="border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Mô Tả SEO <small class="text-muted">(Nếu có)</small></label>
                        <textarea name="seo_description" class="form-control" rows="3" placeholder="Ví dụ: Dịch vụ cung cấp tài khoản VPN giá rẻ, bảo hành 1 đổi 1..." style="border-radius:10px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-600" style="border-radius:10px;">
                        <i class="bi bi-plus-lg me-1"></i>Thêm Danh Mục
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- CATEGORIES LIST -->
    <div class="col-12 col-lg-8">
        <div class="admin-card border-0 shadow-sm" style="border-radius:15px;">
            <div class="admin-card-header border-bottom py-3 px-4 d-flex justify-content-between align-items-center" style="background:#fafafa;">
                <div class="admin-card-title m-0 d-flex align-items-center gap-2" style="font-size:14.5px;">
                    <i class="bi bi-folder-fill text-primary" style="font-size:18px;"></i>
                    Danh Sách Danh Mục
                </div>
                <span class="badge bg-primary-subtle text-primary fw-600" style="font-size:11.5px; border-radius:6px; padding: 5px 10px;">
                    {{ count($categories) }} danh mục
                </span>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="font-size:13.5px;">
                        <thead>
                            <tr class="table-light" style="font-weight:700;">
                                <th class="ps-4 py-3" style="width: 50px;">ID</th>
                                <th class="py-3">Tên Danh Mục</th>
                                <th class="py-3">Slug</th>
                                <th class="py-3 text-center">Số Sản Phẩm</th>
                                <th class="py-3 text-end pe-4" style="width: 150px;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                            <tr>
                                <td class="ps-4 text-muted fw-600">{{ $cat->id }}</td>
                                <td>
                                    <div class="fw-700 text-dark">{{ $cat->name }}</div>
                                    @if($cat->seo_title)
                                    <div class="text-muted" style="font-size:11px; max-width: 250px;" class="text-truncate">
                                        <i class="bi bi-search me-1"></i>{{ $cat->seo_title }}
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size:11.5px; border-radius:6px;">{{ $cat->slug }}</span>
                                </td>
                                <td class="text-center fw-700 text-primary">{{ $cat->products_count }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-primary border-0 me-1 edit-cat-btn" 
                                            data-id="{{ $cat->id }}" 
                                            data-name="{{ $cat->name }}" 
                                            data-slug="{{ $cat->slug }}"
                                            data-title="{{ $cat->seo_title }}"
                                            data-desc="{{ $cat->seo_description }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCategoryModal"
                                            style="border-radius:6px; background:#f1f5f9;">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Các sản phẩm thuộc danh mục sẽ bị hủy gán.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0" style="border-radius:6px; background:#fee2e2;">
                                            <i class="bi bi-trash-fill"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
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
        <div class="modal-content" style="border-radius:15px; border:none; overflow:hidden;">
            <div class="modal-header border-bottom py-3 px-4" style="background:#fafafa;">
                <h6 class="modal-title fw-800 text-dark" id="editCategoryModalLabel"><i class="bi bi-pencil-square text-primary me-2"></i>Cập Nhật Danh Mục</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required style="border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Đường Dẫn Slug</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control" style="border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Tiêu Đề SEO</label>
                        <input type="text" name="seo_title" id="edit_seo_title" class="form-control" style="border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark" style="font-size:13px;">Mô Tả SEO</label>
                        <textarea name="seo_description" id="edit_seo_description" class="form-control" rows="3" style="border-radius:10px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4" style="background:#fafafa;">
                    <button type="button" class="btn btn-secondary py-2 px-3 fw-600" data-bs-dismiss="modal" style="border-radius:10px;">Đóng</button>
                    <button type="submit" class="btn btn-primary py-2 px-4 fw-600" style="border-radius:10px;">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editCategoryForm');
    const editButtons = document.querySelectorAll('.edit-cat-btn');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const slug = this.getAttribute('data-slug');
            const title = this.getAttribute('data-title');
            const desc = this.getAttribute('data-desc');

            editForm.action = `/admin/categories/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_slug').value = slug;
            document.getElementById('edit_seo_title').value = title || '';
            document.getElementById('edit_seo_description').value = desc || '';
        });
    });
});
</script>

@endsection
