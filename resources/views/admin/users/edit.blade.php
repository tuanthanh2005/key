@extends('admin.layouts.admin')

@section('title', 'Chỉnh Sửa Người Dùng')
@section('page_title', 'Chỉnh Sửa: ' . $user->name)
@section('breadcrumb', 'Admin / Người Dùng / Chỉnh Sửa')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-pencil-fill text-primary"></i>
                    Cập Nhật Thông Tin Tài Khoản
                </div>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Họ và Tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required style="border-radius: 10px;">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required style="border-radius: 10px;">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Số Điện Thoại</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" style="border-radius: 10px;">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Vai Trò <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required style="border-radius: 10px;" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="user" {{ old('role', $user->role)=='user'?'selected':'' }}>Khách Hàng (User)</option>
                                <option value="admin" {{ old('role', $user->role)=='admin'?'selected':'' }}>Quản Trị Viên (Admin)</option>
                            </select>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="role" value="admin">
                                <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Bạn không thể tự thay đổi vai trò của chính mình</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Trạng Thái <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required style="border-radius: 10px;" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="active" {{ old('status', $user->status)=='active'?'selected':'' }}>Kích Hoạt (Active)</option>
                                <option value="banned" {{ old('status', $user->status)=='banned'?'selected':'' }}>Khóa (Banned)</option>
                            </select>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="status" value="active">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Mật Khẩu Mới <small class="text-muted">(Để trống nếu giữ nguyên)</small></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" style="border-radius: 10px;">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600">
                                <i class="bi bi-check-circle me-2"></i>Lưu Thay Đổi
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
