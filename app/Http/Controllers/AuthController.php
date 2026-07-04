<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ==========================================
    // ĐĂNG NHẬP
    // ==========================================

    public function loginForm()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->isActive()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Liên hệ admin.']);
            }

            $request->session()->regenerate();
            return $this->redirectAfterLogin();
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->withInput($request->only('email'));
    }

    // ==========================================
    // ĐĂNG KÝ
    // ==========================================

    public function registerForm()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'name.required'      => 'Vui lòng nhập họ tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.email'        => 'Email không hợp lệ.',
            'email.unique'       => 'Email này đã được sử dụng.',
            'password.required'  => 'Vui lòng nhập mật khẩu.',
            'password.min'       => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'status'   => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Đăng ký thành công! Chào mừng ' . $user->name . '!');
    }

    // ==========================================
    // ĐĂNG XUẤT
    // ==========================================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Đã đăng xuất thành công!');
    }

    // ==========================================
    // QUÊN MẬT KHẨU
    // ==========================================

    public function forgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email'    => 'Email không hợp lệ.',
            'email.exists'   => 'Email này không tồn tại trong hệ thống.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link đặt lại mật khẩu đã được gửi về email của bạn!');
        }

        return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại.']);
    }

    public function resetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('auth.login')
                ->with('success', 'Mật khẩu đã được đặt lại thành công! Vui lòng đăng nhập.');
        }

        return back()->withErrors(['email' => 'Liên kết không hợp lệ hoặc đã hết hạn.']);
    }

    // ==========================================
    // HELPER
    // ==========================================

    private function redirectAfterLogin()
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }
}
