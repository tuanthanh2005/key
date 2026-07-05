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
            Tôi đồng ý với <a href="#" class="auth-link" data-bs-toggle="modal" data-bs-target="#termsModal">Điều Khoản</a> và <a href="#" class="auth-link" data-bs-toggle="modal" data-bs-target="#privacyModal">Chính Sách Bảo Mật</a>
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

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#1e1b4b; border: 1.5px solid rgba(255,255,255,.1); border-radius: 20px; color:#fff;">
            <div class="modal-header border-bottom border-secondary py-3 px-4">
                <h6 class="modal-title fw-800 font-poppins" id="termsModalLabel"><i class="bi bi-file-earmark-text text-primary me-2" style="font-size:16px;"></i>Điều Khoản Dịch Vụ</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-start" style="font-size:13.5px; line-height:1.6; color:rgba(255,255,255,.8);">
                <p class="fw-700 text-white mb-2" style="font-size:14px;">1. Quy định chung</p>
                <p class="mb-3">Bằng việc đăng ký tài khoản tại VPNStore, bạn đồng ý tuân thủ toàn bộ các quy định và chính sách hoạt động của chúng tôi.</p>
                
                <p class="fw-700 text-white mb-2" style="font-size:14px;">2. Sử dụng dịch vụ</p>
                <p class="mb-3">Tài khoản và key kích hoạt được cung cấp chỉ dành cho mục đích cá nhân. Nghiêm cấm chia sẻ, bán lại hoặc sử dụng vào mục đích thương mại phi pháp.</p>
                
                <p class="fw-700 text-white mb-2" style="font-size:14px;">3. Chính sách bảo hành</p>
                <p class="mb-3">Chúng tôi cam kết bảo hành rõ ràng trong suốt thời hạn sử dụng. Đổi sản phẩm mới hoặc hoàn tiền nếu lỗi phát sinh từ phía hệ thống.</p>
                
                <p class="fw-700 text-white mb-2" style="font-size:14px;">4. Từ chối trách nhiệm</p>
                <p class="mb-0">Chúng tôi không chịu trách nhiệm đối với các thiệt hại trực tiếp hoặc gián tiếp phát sinh từ việc sử dụng sai mục đích các dịch vụ.</p>
            </div>
            <div class="modal-footer border-top border-secondary py-3 px-4">
                <button type="button" class="btn btn-primary py-2 px-4 fw-600 rounded-pill" data-bs-dismiss="modal" style="font-size:13px;">Đã Hiểu</button>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#1e1b4b; border: 1.5px solid rgba(255,255,255,.1); border-radius: 20px; color:#fff;">
            <div class="modal-header border-bottom border-secondary py-3 px-4">
                <h6 class="modal-title fw-800 font-poppins" id="privacyModalLabel"><i class="bi bi-shield-lock text-success me-2" style="font-size:16px;"></i>Chính Sách Bảo Mật</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-start" style="font-size:13.5px; line-height:1.6; color:rgba(255,255,255,.8);">
                <p class="fw-700 text-white mb-2" style="font-size:14px;">1. Thu thập thông tin</p>
                <p class="mb-3">Chúng tôi thu thập email và tên đăng ký để phục vụ công tác quản lý đơn hàng, giao nhận key tự động và hỗ trợ bảo hành.</p>
                
                <p class="fw-700 text-white mb-2" style="font-size:14px;">2. Bảo mật dữ liệu</p>
                <p class="mb-3">Mọi dữ liệu của bạn đều được mã hóa bằng công nghệ hiện đại. VPNStore cam kết tuyệt đối không chia sẻ, bán thông tin khách hàng cho bên thứ ba.</p>
                
                <p class="fw-700 text-white mb-2" style="font-size:14px;">3. Quyền của khách hàng</p>
                <p class="mb-0">Bạn có quyền truy cập, chỉnh sửa hoặc gửi yêu cầu xóa bỏ vĩnh viễn thông tin tài khoản cá nhân của mình bất kỳ lúc nào.</p>
            </div>
            <div class="modal-footer border-top border-secondary py-3 px-4">
                <button type="button" class="btn btn-success py-2 px-4 fw-600 rounded-pill text-white" data-bs-dismiss="modal" style="font-size:13px;">Đã Hiểu</button>
            </div>
        </div>
    </div>
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
