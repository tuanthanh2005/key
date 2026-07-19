<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'VPNStore')</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content:'';
            position:fixed;
            top:-40%;left:-20%;
            width:700px;height:700px;
            background:radial-gradient(circle,rgba(37,99,235,.15),transparent 70%);
            pointer-events:none;
        }
        body::after {
            content:'';
            position:fixed;
            bottom:-30%;right:-15%;
            width:600px;height:600px;
            background:radial-gradient(circle,rgba(124,58,237,.15),transparent 70%);
            pointer-events:none;
        }
        .auth-card {
            background: rgba(255,255,255,.04);
            backdrop-filter: blur(24px);
            border: 1.5px solid rgba(255,255,255,.1);
            border-radius: 24px;
            padding: 44px 40px;
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
        }
        .auth-logo {
            display:flex;align-items:center;gap:10px;
            text-decoration:none;
            margin-bottom:32px;
        }
        .auth-logo .logo-icon {
            width:42px;height:42px;
            background:linear-gradient(135deg,#2563eb,#7c3aed);
            border-radius:10px;
            display:flex;align-items:center;justify-content:center;
            font-size:20px;color:#fff;
        }
        .auth-logo .logo-text {
            font-family:'Poppins',sans-serif;
            font-weight:800;font-size:20px;
            background:linear-gradient(135deg,#60a5fa,#a78bfa);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
        }
        .auth-title {
            font-family:'Poppins',sans-serif;
            font-size:26px;font-weight:800;
            color:#fff;margin-bottom:6px;
        }
        .auth-subtitle { font-size:14px;color:rgba(255,255,255,.5);margin-bottom:28px; }
        .form-label { font-size:13.5px;font-weight:600;color:rgba(255,255,255,.75);margin-bottom:6px; }
        .form-control {
            background:rgba(255,255,255,.06);
            border:1.5px solid rgba(255,255,255,.12);
            color:#fff;border-radius:12px;
            padding:12px 16px;font-size:14px;
            transition:all .2s;
        }
        .form-control:focus {
            background:rgba(255,255,255,.09);
            border-color:rgba(99,102,241,.6);
            box-shadow:0 0 0 3px rgba(99,102,241,.15);
            color:#fff;outline:none;
        }
        .form-control::placeholder { color:rgba(255,255,255,.3); }
        .input-group-text {
            background:rgba(255,255,255,.06);
            border:1.5px solid rgba(255,255,255,.12);
            border-right:none;color:rgba(255,255,255,.4);
            border-radius:12px 0 0 12px;
        }
        .input-group .form-control { border-left:none;border-radius:0 12px 12px 0; }
        .btn-auth-primary {
            background:linear-gradient(135deg,#2563eb,#7c3aed);
            border:none;border-radius:12px;
            color:#fff;font-weight:700;font-size:15px;
            padding:13px 24px;width:100%;
            transition:all .25s;cursor:pointer;
            position:relative;overflow:hidden;
        }
        .btn-auth-primary:hover { transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,99,235,.4); }
        .btn-auth-primary:active { transform:translateY(0); }
        .btn-google {
            background:rgba(255,255,255,.06);
            border:1.5px solid rgba(255,255,255,.12);
            border-radius:12px;color:#fff;
            font-weight:600;font-size:14px;
            padding:11px 24px;width:100%;
            transition:all .2s;cursor:pointer;
            display:flex;align-items:center;justify-content:center;gap:10px;
        }
        .btn-google:hover { background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2); }
        .divider {
            display:flex;align-items:center;gap:12px;margin:20px 0;
        }
        .divider::before,.divider::after {
            content:'';flex:1;height:1px;
            background:rgba(255,255,255,.1);
        }
        .divider span { font-size:12px;color:rgba(255,255,255,.35);font-weight:500; }
        .form-check-input {
            background-color:rgba(255,255,255,.06);
            border-color:rgba(255,255,255,.2);
        }
        .form-check-input:checked { background-color:#2563eb;border-color:#2563eb; }
        .form-check-label { font-size:13px;color:rgba(255,255,255,.6); }
        .auth-link { color:#60a5fa;text-decoration:none;font-weight:600; }
        .auth-link:hover { color:#93c5fd;text-decoration:underline; }
        .alert-danger-dark {
            background:rgba(239,68,68,.1);
            border:1px solid rgba(239,68,68,.25);
            border-radius:12px;padding:12px 16px;
            color:#fca5a5;font-size:13.5px;
        }
        .alert-success-dark {
            background:rgba(34,197,94,.1);
            border:1px solid rgba(34,197,94,.25);
            border-radius:12px;padding:12px 16px;
            color:#86efac;font-size:13.5px;
        }
        .password-toggle { position:relative; }
        .password-toggle .toggle-btn {
            position:absolute;right:12px;top:50%;transform:translateY(-50%);
            background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;
            padding:4px;line-height:1;
        }
        .strength-bar { height:4px;border-radius:2px;background:rgba(255,255,255,.08);margin-top:8px;overflow:hidden; }
        .strength-fill { height:100%;border-radius:2px;transition:all .3s; }
        .brand-dots {
            display:flex;gap:6px;justify-content:center;margin-top:24px;
        }
        .brand-dot {
            width:28px;height:28px;border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            font-size:12px;color:#fff;font-weight:700;
            opacity:.7;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <a href="{{ route('home') }}" class="auth-logo">
            @if(!empty($settings['logo_path']))
                <div class="logo-icon" style="background:none; box-shadow:none;"><img src="{{ asset($settings['logo_path']) }}" alt="Logo" style="max-width:100%; max-height:100%; object-fit:contain;"></div>
            @else
                <div class="logo-icon"><i class="bi bi-shield-lock-fill"></i></div>
            @endif
            <span class="logo-text">{{ $settings['store_name'] ?? 'VPNStore' }}</span>
        </a>

        @yield('content')

        <!-- Brand Dots -->
        <div class="brand-dots mt-4">
            @foreach([['#4687FF','N'],['#DA3940','E'],['#10B981','S'],['#F59E0B','H'],['#8B5CF6','C'],['#6D28D9','P']] as [$c,$l])
            <div class="brand-dot" style="background:{{ $c }}">{{ $l }}</div>
            @endforeach
        </div>
    </div>

    @yield('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra_js')
    <script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon  = event.currentTarget.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
    </script>
</body>
</html>
