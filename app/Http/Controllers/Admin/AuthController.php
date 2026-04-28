<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 🟢 عرض صفحة تسجيل الدخول
    public function showLogin(Request $request)
    {
        return view('auth.login');
    }

    // 🟢 تنفيذ تسجيل الدخول مع تتبع الجهاز والمتصفح والجلسة
    public function login(Request $request)
    {
        $loginId = (string) $request->input('login_id');

        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $validated = $request->validate([
            'login_id' => 'required|' . ($fieldType === 'email' ? 'email' : 'string'),
            'password' => 'required|min:6',
            'role' => 'required|in:Manager,Supervisor',
        ]);

        $credentials = [
            $fieldType => $validated['login_id'],
            'password' => $validated['password'],
        ];

        $user = User::where($fieldType, $validated['login_id'])->first();

        if (!$user) {
            return back()->withErrors(['login_id' => 'User not found.'])->withInput();
        }

        if (trim(strtolower((string) $user->role)) !== trim(strtolower((string) $validated['role']))) {
            return back()->withErrors([
                'role' => 'This account is not registered as a ' . $validated['role'],
            ])->withInput();
        }

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            session(['active_guard' => 'admin']);
            $request->session()->forget('url.intended');

            return redirect()->to(
                match ($user->role) {
                    'Manager', 'Admin' => route('manager.dashboard'),
                    'Supervisor' => route('supervisor.dashboard'),
                    default => route('home'),
                }
            );
        }

        return back()->withErrors(['login_id' => 'Incorrect credentials.'])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // 🟢 تنفيذ التسجيل
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'full_name' => 'nullable|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'gender' => 'nullable|in:male,female',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        try {
            User::create([
                'username' => $validated['username'],
                'name' => $validated['full_name'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'Supervisor',
                'status' => 'pending',
                'gender' => $validated['gender'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تسجيل حسابك بنجاح.',
                'redirect_url' => route('manager.dashboard'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.show');
    }

    public function profile()
    {
        $user = auth()->user();

        return view('auth.profile', compact('user'));
    }
}