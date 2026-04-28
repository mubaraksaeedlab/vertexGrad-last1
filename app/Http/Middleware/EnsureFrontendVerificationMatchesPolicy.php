<?php

namespace App\Http\Middleware;

use App\Services\AuthPolicyResolverService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFrontendVerificationMatchesPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('web')->user();

        if (! $user) {
            return redirect()->route('login.show');
        }

        $policy = AuthPolicyResolverService::resolveForUser($user);

        if (($policy['emergency_bypass_enabled'] ?? false) === true) {
            return $next($request);
        }

        if (($policy['email_verification_mode'] ?? 'required') !== 'required') {
            return $next($request);
        }

        if (method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail()) {
            return $next($request);
        }

        return redirect()
            ->route('verification.notice')
            ->with('success', 'Please verify your email to continue.');
    }
}