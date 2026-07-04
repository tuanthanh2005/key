<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Chuyển hướng đến Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Tìm user theo google_id hoặc email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Cập nhật google_id nếu chưa có
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar'    => $googleUser->avatar,
                    ]);
                }
            } else {
                // Tạo user mới từ Google
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar'    => $googleUser->avatar,
                    'password'  => bcrypt(\Illuminate\Support\Str::random(32)),
                    'role'      => 'user',
                    'status'    => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            // Kiểm tra tài khoản bị khóa
            if (!$user->isActive()) {
                return redirect()->route('auth.login')
                    ->with('error', 'Tài khoản của bạn đã bị khóa. Liên hệ admin.');
            }

            Auth::login($user, true);

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home')
                ->with('success', 'Đăng nhập Google thành công! Chào ' . $user->name);

        } catch (\Exception $e) {
            return redirect()->route('auth.login')
                ->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }
    }
}
