<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // تحديد نوع الحارس (admin أو web)
        $guard = (
            $request->is('admin*') ||
            $request->is('manager*') ||
            $request->is('supervisor*')
        ) ? 'admin' : 'web';

        // إذا الجلسة منتهية أو المستخدم غير مسجل دخول
        if (!Auth::guard($guard)->check()) {
            return redirect()
                ->route($guard === 'admin' ? 'admin.login.show' : 'login.show')
                ->with('error', 'Your session has expired. Please log in again.');
        }

        $user = Auth::guard($guard)->user();

        $userRole = strtolower(trim((string) ($user->role ?? '')));
        $requiredRole = strtolower(trim((string) $role));

        // إذا الدور غير مطابق
        if ($userRole !== $requiredRole) {

            // 🔥 توجيه ذكي بدل 403
            if ($guard === 'admin') {

                // إذا المستخدم Manager وراح لصفحة Supervisor
                if ($userRole === 'manager') {
                    return redirect('/admin/manager/dashboard')
                        ->with('error', 'You are logged in as Manager.');
                }

                // إذا المستخدم Supervisor وراح لصفحة Manager
                if ($userRole === 'supervisor') {
                    return redirect('/admin/supervisor/dashboard')
                        ->with('error', 'You are logged in as Supervisor.');
                }
            }

            // fallback (نادراً يصير)
            abort(403, 'Unauthorized. This area is reserved for ' . $role);
        }

        return $next($request);
    }
}