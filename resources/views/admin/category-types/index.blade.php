@extends('admin.layouts.admin')
@section('title', 'Quản Lý Loại Danh Mục')
@section('page_title', 'Quản Lý Loại Danh Mục')
@section('breadcrumb', 'Admin / Loại Danh Mục')

@section('content')

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px; font-size:13.5px">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius:12px; font-size:13.5px">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
</div>
@endif

<div class="row g-4">
    <!-- ADD CATEGORY TYPE FORM -->
    <div class="col-12 col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-plus-circle text-primary"></i>
                    Tạo Loại Danh Mục Mới
                </div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.category-types.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Loại Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: VPN, Proxy" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mã Slug <small class="text-muted">(Để trống tự sinh)</small></label>
                        <input type="text" name="slug" class="form-control" placeholder="Ví dụ: vpn, proxy">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold" style="border-radius:10px;">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Loại Danh Mục
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- CATEGORY TYPES LIST -->
    <div class="col-12 col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="admin-card-title">
                    <i class="bi bi-grid-fill text-primary"></i>
                    Danh Sách Loại Danh Mục
                </div>
                <span class="admin-badge admin-badge-primary">
                    {{ count($types) }} loại
                </span>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Tên Loại Danh Mục</th>
                                <th>Mã Slug (Key)</th>
                                <th class="text-end" style="width: 150px;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse($types as $type)
                             <tr>
                                 <td class="text-muted fw-bold">{{ $type->id }}</td>
                                 <td>
                                     <strong class="text-dark">{{ $type->name }}</strong>
                                 </td>
                                 <td>
                                     <code class="px-2 py-1 bg-light text-primary rounded" style="font-size: 12px;">{{ $type->slug }}</code>
                                 </td>
                                 <td class="text-end">
                                     <div class="d-flex justify-content-end gap-2">
                                         <button class="btn btn-sm btn-outline-primary" 
                                                 style="padding: 4px 8px; border-radius: 6px;"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#editModal-{{ $type->id }}">
                                             <i class="bi bi-pencil-square"></i>
                                         </button>
                                         <form action="{{ route('admin.category-types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa loại danh mục này?')">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="btn btn-sm btn-outline-danger" style="padding: 4px 8px; border-radius: 6px;">
                                                 <i class="bi bi-trash"></i>
                                             </button>
                                         </form>
                                     </div>
                                 </td>
                             </tr>

                             <!-- EDIT MODAL -->
                             <div class="modal fade" id="editModal-{{ $type->id }}" tabindex="-1" aria-hidden="true">
                                 <div class="modal-dialog modal-dialog-centered">
                                     <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                         <form action="{{ route('admin.category-types.update', $type->id) }}" method="POST">
                                             @csrf
                                             @method('PUT')
                                             <div class="modal-header border-bottom-0 pb-0">
                                                 <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Chỉnh Sửa Loại Danh Mục</h5>
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body py-4">
                                                 <div class="mb-3">
                                                     <label class="form-label fw-bold">Tên Loại Danh Mục <span class="text-danger">*</span></label>
                                                     <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                                                 </div>
                                                 <div class="mb-3">
                                                     <label class="form-label fw-bold">Mã Slug <span class="text-danger">*</span></label>
                                                     <input type="text" name="slug" class="form-control" value="{{ $type->slug }}" required>
                                                 </div>
                                             </div>
                                             <div class="modal-footer border-top-0 pt-0">
                                                 <button type="button" class="btn btn-light px-4 py-2" style="border-radius: 10px;" data-bs-dismiss="modal">Hủy</button>
                                                 <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">Lưu Thay Đổi</button>
                                             </div>
                                         </form>
                                     </div>
                                 </div>
                             </div>
                             @empty
                             <tr>
                                 <td colspan="4" class="text-center py-4 text-muted">Không có loại danh mục nào.</td>
                             </tr>
                             @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
