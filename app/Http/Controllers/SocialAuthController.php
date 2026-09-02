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
                $clientIp = request()->ip();
                $todayStart = now()->startOfDay();
                $ipRegCountDb = User::where('ip_address', $clientIp)
                    ->where('created_at', '>=', $todayStart)
                    ->count();
                $cacheKey = 'ip_reg_count_' . str_replace([':', '.'], '_', $clientIp) . '_' . date('Y-m-d');
                $ipRegCountCache = (int) \Illuminate\Support\Facades\Cache::get($cacheKey, 0);
                $totalRegistrationsToday = max($ipRegCountDb, $ipRegCountCache);

                if ($totalRegistrationsToday >= 3) {
                    return redirect()->route('auth.login')
                        ->with('error', 'Địa chỉ IP của bạn đã đạt giới hạn tạo 3 tài khoản trong ngày hôm nay. Vui lòng liên hệ trực tiếp Admin để được hỗ trợ tạo tài khoản!');
                }

                // Tạo user mới từ Google
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar'    => $googleUser->avatar,
                    'password'  => bcrypt(\Illuminate\Support\Str::random(32)),
                    'role'      => 'user',
                    'status'    => 'active',
                    'ip_address'=> $clientIp,
                    'email_verified_at' => now(),
                ]);
                \Illuminate\Support\Facades\Cache::put($cacheKey, $totalRegistrationsToday + 1, now()->endOfDay());
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
