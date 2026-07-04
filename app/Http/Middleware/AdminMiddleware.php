<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        if (!auth()->user()->isActive()) {
            auth()->logout();
            return redirect()->route('auth.login')
                ->with('error', 'Tài khoản của bạn đã bị khóa. Liên hệ admin.');
        }

        return $next($request);
    }
}
