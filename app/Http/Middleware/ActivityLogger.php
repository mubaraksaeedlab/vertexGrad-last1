<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use App\Models\ActivityLog;

class ActivityLogger
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();
            $agent = new Agent();

            // تحديث آخر نشاط
            $user->update(['last_activity' => now()]);

            // تسجيل زيارة الصفحة
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'View Page',
                'model' => null,
                'description' => 'Visited: '.$request->path(),
                'ip' => $request->ip(),
                'device' => $agent->device(),
                'browser' => $agent->browser(),
                'os' => $agent->platform(),
            ]);
        }

        return $next($request);
    }
}
