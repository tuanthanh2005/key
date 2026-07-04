@extends('admin.layouts.admin')

@section('title', 'Thêm Người Dùng')
@section('page_title', 'Thêm Người Dùng Mới')
@section('breadcrumb', 'Admin / Người Dùng / Thêm Mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-person-plus-fill text-primary"></i>
                    Tạo Tài Khoản Người Dùng
                </div>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Họ và Tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nguyễn Văn A" value="{{ old('name') }}" required style="border-radius: 10px;">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@domain.com" value="{{ old('email') }}" required style="border-radius: 10px;">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Số Điện Thoại</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="0909 999 999" value="{{ old('phone') }}" style="border-radius: 10px;">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Vai Trò <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required style="border-radius: 10px;">
                                <option value="user" {{ old('role')=='user'?'selected':'' }}>Khách Hàng (User)</option>
                                <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Quản Trị Viên (Admin)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Mật Khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Tối thiểu 8 ký tự" required style="border-radius: 10px;">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600">
                                <i class="bi bi-save me-2"></i>Tạo Người Dùng
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                Hủy
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
