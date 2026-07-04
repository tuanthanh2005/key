@extends('auth.layouts.auth')
@section('title', 'Đăng Ký - VPNStore')

@section('content')

<h1 class="auth-title">Tạo Tài Khoản</h1>
<p class="auth-subtitle">Đăng ký để nhận ưu đãi độc quyền từ VPNStore</p>

@if($errors->any())
<div class="alert-danger-dark mb-4">
    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
</div>
@endif

<!-- Google Register -->
<a href="{{ route('auth.google') }}" class="btn-google mb-3" style="text-decoration:none">
    <svg width="18" height="18" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
    </svg>
    Đăng Ký Bằng Google
</a>

<div class="divider"><span>hoặc đăng ký bằng email</span></div>

<form method="POST" action="{{ route('auth.register.post') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Họ và Tên</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="name" class="form-control" placeholder="Nguyễn Văn A"
                   value="{{ old('name') }}" required autocomplete="name">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="email@gmail.com"
                   value="{{ old('email') }}" required autocomplete="email">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Mật Khẩu <small style="color:rgba(255,255,255,.4)">(tối thiểu 8 ký tự)</small></label>
        <div class="input-group" style="position:relative">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" id="regPassword"
                   placeholder="••••••••" required autocomplete="new-password" oninput="checkStrength(this.value)">
            <button type="button" onclick="togglePassword('regPassword')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;z-index:10">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="strength-bar"><div class="strength-fill" id="strengthFill" style="width:0%;background:#ef4444"></div></div>
        <div id="strengthLabel" style="font-size:11.5px;color:rgba(255,255,255,.4);margin-top:4px"></div>
    </div>

    <div class="mb-4">
        <label class="form-label">Xác Nhận Mật Khẩu</label>
        <div class="input-group" style="position:relative">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="password_confirmation" class="form-control" id="regConfirm"
                   placeholder="••••••••" required autocomplete="new-password">
            <button type="button" onclick="togglePassword('regConfirm')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;z-index:10">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
        <label class="form-check-label" for="agreeTerms">
            Tôi đồng ý với <a href="#" class="auth-link">Điều Khoản</a> và <a href="#" class="auth-link">Chính Sách Bảo Mật</a>
        </label>
    </div>

    <button type="submit" class="btn-auth-primary">
        <i class="bi bi-person-plus me-2"></i>Tạo Tài Khoản
    </button>
</form>

<div class="text-center mt-4" style="font-size:14px;color:rgba(255,255,255,.5)">
    Đã có tài khoản?
    <a href="{{ route('auth.login') }}" class="auth-link ms-1">Đăng Nhập</a>
</div>

@endsection

@section('extra_js')
<script>
function checkStrength(val) {
    const bar = document.getElementById('strengthFill');
    const lbl = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)     score++;
    if (/[A-Z]/.test(val))  score++;
    if (/[0-9]/.test(val))  score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const map = [
        { w:'0%',   c:'#ef4444', t:'' },
        { w:'25%',  c:'#ef4444', t:'Yếu' },
        { w:'50%',  c:'#f59e0b', t:'Trung bình' },
        { w:'75%',  c:'#3b82f6', t:'Mạnh' },
        { w:'100%', c:'#22c55e', t:'Rất mạnh' },
    ];
    bar.style.width  = map[score].w;
    bar.style.background = map[score].c;
    lbl.textContent  = map[score].t;
    lbl.style.color  = map[score].c;
}
</script>
@endsection
