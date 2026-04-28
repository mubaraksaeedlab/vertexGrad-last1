<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Identify the environment based on the URL
        $isAdminArea = $request->is('admin*') || $request->is('manager*') || $request->is('Supervisior*');
        $guard = $isAdminArea ? 'admin' : 'web';

        // 2. Check if the specific guard is authenticated
        if (!Auth::guard($guard)->check()) {
            if ($isAdminArea) {
                return redirect()->route('admin.login.show');
            }
            return redirect()->route('login.show');
        }

        // 3. Security: Prevent a Student (web) from ever "sneaking" into an admin guard session
        $user = Auth::guard($guard)->user();
        if ($isAdminArea && !in_array($user->role, ['Manager', 'Supervisor'])) {
            Auth::guard($guard)->logout(); // Kill the session if they are the wrong role type
            return redirect()->route('admin.login.show')->withErrors(['login_id' => 'Unauthorized area.']);
        }

        return $next($request);
    }
}