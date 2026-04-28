<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\RecoveryCode;
use App\Models\TrustedDevice;
use App\Services\RecoveryCodeService;
use App\Services\SecurityActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($request) {
                $userAgent = (string) ($session->user_agent ?? '');

                return (object) [
                    'id'            => $session->id,
                    'ip_address'    => $session->ip_address,
                    'browser'       => SecurityActivityLogger::detectBrowser($userAgent),
                    'os'            => SecurityActivityLogger::detectOs($userAgent),
                    'device'        => SecurityActivityLogger::detectDevice($userAgent),
                    'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                    'is_current'    => $session->id === $request->session()->getId(),
                ];
            });

        $trustedDevices = TrustedDevice::where('user_id', $user->id)
            ->orderByDesc('last_used_at')
            ->get();

        $recentActivities = LoginActivity::where('user_id', $user->id)
            ->latest()
            ->limit(15)
            ->get();

        $recoveryCodesCount = RecoveryCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->count();

        return view('frontend.auth.security', compact(
            'sessions',
            'trustedDevices',
            'recentActivities',
            'recoveryCodesCount'
        ));
    }

    public function revokeSession(Request $request, string $sessionId)
    {
        $user = $request->user();
        $currentSessionId = $request->session()->getId();

        $session = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->first();

        if (! $session) {
            return back()->withErrors([
                'security' => 'Session not found or already removed.',
            ]);
        }

        if ($sessionId === $currentSessionId) {
            return back()->withErrors([
                'security' => 'You cannot revoke your current session from this action.',
            ]);
        }

        DB::table('sessions')
            ->where('id', $sessionId)
            ->delete();

        SecurityActivityLogger::log(
            $user->id,
            'session_revoked',
            $request,
            true,
            ['revoked_session_id' => $sessionId]
        );

        return back()->with('status', 'Session revoked successfully.');
    }

    public function revokeTrustedDevice(Request $request, int $trustedDeviceId)
    {
        $user = $request->user();

        $trustedDevice = TrustedDevice::where('user_id', $user->id)
            ->where('id', $trustedDeviceId)
            ->first();

        if (! $trustedDevice) {
            return back()->withErrors([
                'security' => 'Trusted device not found.',
            ]);
        }

        $trustedDevice->delete();

        SecurityActivityLogger::log(
            $user->id,
            'trusted_device_revoked',
            $request,
            true,
            ['trusted_device_id' => $trustedDeviceId]
        );

        return back()->with('status', 'Trusted device removed successfully.');
    }

    public function logoutOtherDevices(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ]);
        }

        $currentSessionId = $request->session()->getId();

        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        TrustedDevice::where('user_id', $user->id)->delete();

        cookie()->queue(cookie()->forget('trusted_device_token'));

        SecurityActivityLogger::log(
            $user->id,
            'logout_other_devices',
            $request,
            true
        );

        return back()->with('status', 'Logged out from all other devices and cleared trusted devices.');
    }

    public function regenerateRecoveryCodes(Request $request)
    {
        $user = $request->user();

        $plainCodes = RecoveryCodeService::regenerateFor($user);

        SecurityActivityLogger::log(
            $user->id,
            'recovery_codes_regenerated',
            $request,
            true
        );

        return redirect()
            ->route('security.index')
            ->with('status', 'Recovery codes regenerated successfully.')
            ->with('recovery_codes', $plainCodes);
    }

    public function downloadRecoveryCodes(Request $request): StreamedResponse
    {
        $user = $request->user();

        $codes = session('recovery_codes');

        if (! is_array($codes) || empty($codes)) {
            return response()->streamDownload(function () {
                echo "No new recovery codes are available to download.\n";
                echo "Generate new recovery codes first, then download them immediately.\n";
            }, 'vertexgrad-recovery-codes.txt', [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]);
        }

        SecurityActivityLogger::log(
            $user->id,
            'recovery_codes_downloaded',
            $request,
            true
        );

        $filename = 'vertexgrad-recovery-codes-' . now()->format('Y-m-d-H-i') . '.txt';

        return response()->streamDownload(function () use ($user, $codes) {
            echo "VertexGrad Recovery Codes\n";
            echo "User: {$user->email}\n";
            echo "Generated At: " . now()->toDateTimeString() . "\n";
            echo "----------------------------------------\n";
            echo "Each code can be used once only.\n";
            echo "Store these codes in a safe place.\n";
            echo "----------------------------------------\n\n";

            foreach ($codes as $code) {
                echo $code . "\n";
            }

            echo "\n----------------------------------------\n";
            echo "If you regenerate recovery codes, old unused codes will be replaced.\n";
        }, $filename, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}