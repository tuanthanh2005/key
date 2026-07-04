@extends('auth.layouts.auth')
@section('title', 'Đặt Lại Mật Khẩu - VPNStore')

@section('content')

<h1 class="auth-title">Đặt Lại Mật Khẩu</h1>
<p class="auth-subtitle">Tạo mật khẩu mới cho tài khoản của bạn</p>

@if($errors->any())
<div class="alert-danger-dark mb-4">
    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('auth.reset-password.post') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="email@gmail.com"
                   value="{{ $email ?? old('email') }}" required readonly>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Mật Khẩu Mới</label>
        <div class="input-group" style="position:relative">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" id="newPass"
                   placeholder="••••••••" required>
            <button type="button" onclick="togglePassword('newPass')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;z-index:10">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Xác Nhận Mật Khẩu Mới</label>
        <div class="input-group" style="position:relative">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="password_confirmation" class="form-control" id="confirmPass"
                   placeholder="••••••••" required>
            <button type="button" onclick="togglePassword('confirmPass')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;z-index:10">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn-auth-primary">
        <i class="bi bi-check-circle me-2"></i>Đặt Lại Mật Khẩu
    </button>
</form>

@endsection
