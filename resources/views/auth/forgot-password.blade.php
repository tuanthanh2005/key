@extends('auth.layouts.auth')
@section('title', 'Quên Mật Khẩu - VPNStore')

@section('content')

<div class="text-center mb-4">
    <div style="width:64px;height:64px;background:linear-gradient(135deg,rgba(37,99,235,.3),rgba(124,58,237,.3));border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;border:1px solid rgba(255,255,255,.1)">
        <i class="bi bi-key-fill" style="font-size:28px;color:#60a5fa"></i>
    </div>
    <h1 class="auth-title">Quên Mật Khẩu?</h1>
    <p class="auth-subtitle">Nhập email để nhận link đặt lại mật khẩu</p>
</div>

@if(session('success'))
<div class="alert-success-dark mb-4">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="alert-danger-dark mb-4">
    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('auth.forgot-password.post') }}">
    @csrf

    <div class="mb-4">
        <label class="form-label">Email Đăng Ký</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="email@gmail.com"
                   value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mt-2" style="font-size:12.5px;color:rgba(255,255,255,.4)">
            <i class="bi bi-info-circle me-1"></i>Link đặt lại sẽ hết hạn sau 60 phút
        </div>
    </div>

    <button type="submit" class="btn-auth-primary">
        <i class="bi bi-send me-2"></i>Gửi Link Đặt Lại Mật Khẩu
    </button>
</form>

<div class="text-center mt-4" style="font-size:14px;color:rgba(255,255,255,.5)">
    <a href="{{ route('auth.login') }}" class="auth-link">
        <i class="bi bi-arrow-left me-1"></i>Quay Lại Đăng Nhập
    </a>
</div>

@endsection
