@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản - ' . ($settings['store_name'] ?? 'VPNStore'))

@section('content')
<section style="min-height:calc(100vh - 68px); display:flex; align-items:center; justify-content:center; padding:40px 24px; position:relative; overflow:hidden;">
    {{-- Background glow --}}
    <div style="position:absolute; top:-200px; left:50%; transform:translateX(-50%); width:600px; height:600px; background:radial-gradient(circle, rgba(124,58,237,0.12) 0%, transparent 70%);"></div>

    <div style="width:100%; max-width:420px; position:relative; z-index:1;">
        {{-- Logo --}}
        <div style="text-align:center; margin-bottom:36px;">
            <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:10px; text-decoration:none;">
                <div style="width:48px; height:48px; background:linear-gradient(135deg, var(--primary), var(--accent)); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; color:#fff; box-shadow:var(--glow-primary);"><i class="bi bi-shield-lock-fill"></i></div>
                <span style="font-size:1.4rem; font-weight:800;" class="gradient-text">{{ $settings['store_name'] ?? 'VPNStore' }}</span>
            </a>
        </div>

        <div class="card" style="border-color:rgba(124,58,237,0.3); padding:32px;">
            <h1 style="font-size:1.5rem; font-weight:800; text-align:center; margin-bottom:6px; color:var(--text-primary);">Tạo Tài Khoản</h1>
            <p style="text-align:center; color:var(--text-muted); font-size:0.875rem; margin-bottom:28px;">Đăng ký để mua phần mềm bản quyền nhanh chóng!</p>

            @if($errors->any())
            <div style="padding:12px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); border-radius:var(--radius); color:var(--danger); font-size:0.85rem; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            {{-- Google Register --}}
            <a href="{{ route('auth.google') }}" style="display:flex; align-items:center; justify-content:center; gap:12px; padding:12px; background:var(--bg-elevated); border:1px solid var(--border); border-radius:var(--radius-md); text-decoration:none; color:var(--text-primary); font-weight:700; font-size:0.9rem; transition:var(--transition); margin-bottom:20px;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='var(--bg-elevated)'">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Đăng Ký Bằng Google
            </a>

            <div style="text-align:center; font-size:0.8rem; color:var(--text-muted); margin-bottom:20px; display:flex; align-items:center; justify-content:center; gap:8px;">
                <div style="flex:1; height:1px; background:var(--border);"></div>
                <span>Hoặc đăng ký bằng email</span>
                <div style="flex:1; height:1px; background:var(--border);"></div>
            </div>

            <form method="POST" action="{{ route('auth.register.post') }}" style="display:flex; flex-direction:column; gap:16px;">
                @csrf

                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Họ và Tên</label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <span style="position:absolute; left:14px; color:var(--text-muted); font-size:1rem;"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" placeholder="Nguyễn Văn A" value="{{ old('name') }}" required autocomplete="name" style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 14px 10px 40px; border-radius:var(--radius); font-size:0.85rem; width:100%; outline:none;">
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Email</label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <span style="position:absolute; left:14px; color:var(--text-muted); font-size:1rem;"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" placeholder="email@gmail.com" value="{{ old('email') }}" required autocomplete="email" style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 14px 10px 40px; border-radius:var(--radius); font-size:0.85rem; width:100%; outline:none;">
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Mật Khẩu <span style="font-size:0.75rem; color:var(--text-muted); font-weight:normal;">(tối thiểu 8 ký tự)</span></label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <span style="position:absolute; left:14px; color:var(--text-muted); font-size:1rem;"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="regPassword" placeholder="••••••••" required autocomplete="new-password" style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 40px 10px 40px; border-radius:var(--radius); font-size:0.85rem; width:100%; outline:none;">
                        <button type="button" onclick="togglePassword('regPassword')" style="position:absolute; right:14px; background:none; border:none; color:var(--text-muted); cursor:pointer;"><i class="bi bi-eye" id="eye-icon-regPassword"></i></button>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:0.8rem; font-weight:700; color:var(--text-secondary);">Xác Nhận Mật Khẩu</label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <span style="position:absolute; left:14px; color:var(--text-muted); font-size:1rem;"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" id="regConfirm" placeholder="••••••••" required autocomplete="new-password" style="background:var(--bg-input); border:1px solid var(--border); color:var(--text-primary); padding:10px 40px 10px 40px; border-radius:var(--radius); font-size:0.85rem; width:100%; outline:none;">
                        <button type="button" onclick="togglePassword('regConfirm')" style="position:absolute; right:14px; background:none; border:none; color:var(--text-muted); cursor:pointer;"><i class="bi bi-eye" id="eye-icon-regConfirm"></i></button>
                    </div>
                </div>

                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.8rem; color:var(--text-secondary); margin-bottom:8px;">
                    <input type="checkbox" id="agreeTerms" required style="accent-color:var(--primary);">
                    <span>Tôi đồng ý với các điều khoản dịch vụ và chính sách bảo mật</span>
                </label>

                <button type="submit" class="btn btn-primary btn-full btn-lg" style="padding:12px; font-weight:700;">
                    <i class="bi bi-person-plus me-2"></i> Đăng Ký Tài Khoản
                </button>
            </form>

            <div style="text-align:center; font-size:0.85rem; color:var(--text-muted); margin-top:24px;">
                Đã có tài khoản?
                <a href="{{ route('auth.login') }}" style="color:var(--primary-light); font-weight:700; text-decoration:none;">Đăng Nhập</a>
            </div>
        </div>
    </div>
</section>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('eye-icon-' + id);
    if (input && icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
}
</script>
@endsection
