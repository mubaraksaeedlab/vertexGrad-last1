<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\LoginOtp;
use App\Models\RecoveryCode;
use App\Models\TrustedDevice;
use App\Models\User;
use App\Notifications\LoginOtpNotification;
use App\Notifications\SuspiciousLoginAlertNotification;
use App\Services\SecurityActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginOtpController extends Controller
{
    public function show()
    {
        if (! session()->has('otp_user_id')) {
            return redirect()->route('login.show');
        }

        $policy = session('otp_auth_policy', [
            'trusted_devices_enabled' => true,
            'recovery_codes_enabled' => true,
        ]);

        return view('frontend.auth.login-otp', compact('policy'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = session('otp_user_id');
        $policy = session('otp_auth_policy', []);

        if (! $userId) {
            return redirect()->route('login.show');
        }

        $otp = LoginOtp::where('user_id', $userId)
            ->whereNull('verified_at')
            ->latest('id')
            ->first();

        if (! $otp) {
            SecurityActivityLogger::log(
                $userId,
                'otp_session_missing',
                $request,
                false
            );

            return redirect()
                ->route('login.show')
                ->withErrors(['code' => 'Verification session expired. Please log in again.']);
        }

        if ($otp->isExpired()) {
            SecurityActivityLogger::log(
                $userId,
                'otp_expired',
                $request,
                false
            );

            session()->forget(['otp_user_id', 'otp_remember', 'otp_auth_policy']);

            return redirect()
                ->route('login.show')
                ->withErrors(['code' => 'The verification code has expired. Please log in again.']);
        }

        if ($otp->attempts >= 5) {
            SecurityActivityLogger::log(
                $userId,
                'otp_locked',
                $request,
                false
            );

            session()->forget(['otp_user_id', 'otp_remember', 'otp_auth_policy']);

            return redirect()
                ->route('login.show')
                ->withErrors(['code' => 'Too many incorrect attempts. Please log in again.']);
        }

        if (! Hash::check($request->code, $otp->code)) {
            $otp->increment('attempts');

            SecurityActivityLogger::log(
                $userId,
                'otp_failed',
                $request,
                false
            );

            return back()->withErrors([
                'code' => 'Invalid verification code.',
            ]);
        }

        $otp->update([
            'verified_at' => now(),
        ]);

        $user = User::findOrFail($userId);

        SecurityActivityLogger::log(
            $user->id,
            'otp_verified',
            $request,
            true
        );

        $browser = SecurityActivityLogger::detectBrowser((string) $request->userAgent());
        $os = SecurityActivityLogger::detectOs((string) $request->userAgent());
        $device = SecurityActivityLogger::detectDevice((string) $request->userAgent());

        if (($policy['suspicious_login_alerts_enabled'] ?? true) === true) {
            $knownLogin = LoginActivity::where('user_id', $user->id)
                ->whereIn('event', ['login_completed', 'login_completed_trusted_device', 'login_completed_no_otp'])
                ->where('ip_address', $request->ip())
                ->where('browser', $browser)
                ->where('os', $os)
                ->exists();

            if (! $knownLogin) {
                $user->notify(new SuspiciousLoginAlertNotification(
                    $request->ip(),
                    $browser,
                    $os,
                    $device
                ));

                SecurityActivityLogger::log(
                    $user->id,
                    'suspicious_login_alert_sent',
                    $request,
                    true
                );
            }
        }

        Auth::guard('web')->login($user, session('otp_remember', false));
        $request->session()->regenerate();

        if (
            $request->boolean('trust_device')
            && (($policy['trusted_devices_enabled'] ?? true) === true)
        ) {
            $plainTrustedToken = Str::random(64);

            $trustedDevice = TrustedDevice::create([
                'user_id'      => $user->id,
                'token_hash'   => hash('sha256', $plainTrustedToken),
                'device_name'  => $device,
                'browser'      => $browser,
                'os'           => $os,
                'ip_address'   => $request->ip(),
                'last_used_at' => now(),
                'expires_at'   => now()->addDays(30),
            ]);

            cookie()->queue(
                cookie(
                    'trusted_device_token',
                    $plainTrustedToken,
                    60 * 24 * 30,
                    null,
                    null,
                    false,
                    true,
                    false,
                    'Lax'
                )
            );

            SecurityActivityLogger::log(
                $user->id,
                'trusted_device_added',
                $request,
                true,
                ['trusted_device_id' => $trustedDevice->id]
            );
        }

        session()->forget(['otp_user_id', 'otp_remember', 'otp_auth_policy']);

        SecurityActivityLogger::log(
            $user->id,
            'login_completed',
            $request,
            true
        );

        if (
            ($policy['email_verification_mode'] ?? 'required') === 'required'
            && ! $user->hasVerifiedEmail()
        ) {
            return redirect()
                ->route('verification.notice')
                ->with('success', 'Login verified successfully. Please verify your email to continue.');
        }

        return redirect()->to(match ($user->role) {
            'Investor' => route('dashboard.investor'),
            'Student'  => route('dashboard.academic'),
            default    => route('home'),
        });
    }

    public function verifyRecoveryCode(Request $request)
    {
        $request->validate([
            'recovery_code' => ['required', 'string'],
        ]);

        $userId = session('otp_user_id');
        $policy = session('otp_auth_policy', []);

        if (! $userId) {
            return redirect()->route('login.show');
        }

        if (($policy['recovery_codes_enabled'] ?? true) !== true) {
            return back()->withErrors([
                'recovery_code' => 'Recovery codes are disabled for this account.',
            ]);
        }

        $user = User::findOrFail($userId);

        $codes = RecoveryCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->get();

        $matchedCode = $codes->first(function ($code) use ($request) {
            return Hash::check($request->recovery_code, $code->code_hash);
        });

        if (! $matchedCode) {
            SecurityActivityLogger::log(
                $user->id,
                'recovery_code_failed',
                $request,
                false
            );

            return back()->withErrors([
                'recovery_code' => 'Invalid recovery code.',
            ]);
        }

        $matchedCode->update([
            'used_at' => now(),
        ]);

        Auth::guard('web')->login($user, session('otp_remember', false));
        $request->session()->regenerate();

        session()->forget(['otp_user_id', 'otp_remember', 'otp_auth_policy']);

        SecurityActivityLogger::log(
            $user->id,
            'recovery_code_used',
            $request,
            true
        );

        if (
            ($policy['email_verification_mode'] ?? 'required') === 'required'
            && ! $user->hasVerifiedEmail()
        ) {
            return redirect()
                ->route('verification.notice')
                ->with('success', 'Login verified successfully. Please verify your email to continue.');
        }

        return redirect()->to(match ($user->role) {
            'Investor' => route('dashboard.investor'),
            'Student'  => route('dashboard.academic'),
            default    => route('home'),
        });
    }

    public function resend(Request $request)
    {
        $userId = session('otp_user_id');

        if (! $userId) {
            return redirect()->route('login.show');
        }

        $user = User::findOrFail($userId);

        LoginOtp::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();

        $plainCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        LoginOtp::create([
            'user_id'    => $user->id,
            'code'       => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(10),
            'attempts'   => 0,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000),
        ]);

        $user->notify(new LoginOtpNotification($plainCode));

        SecurityActivityLogger::log(
            $user->id,
            'otp_resent',
            $request,
            true
        );

        return back()->with('status', 'A new verification code has been sent to your email.');
    }
}