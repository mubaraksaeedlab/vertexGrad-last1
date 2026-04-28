<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /** صفحة إنشاء مستخدم جديد */
    public function create()
    {
        return view('manager.create_user');
    }

    /** تخزين مستخدم جديد */
    public function store(Request $request)
    {
        $existingUser = User::where('email', $request->email)
            ->orWhere('username', $request->username)
            ->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'User already exists!');
        }

        $request->validate([
            'username'      => 'required|string|max:50',
            'name'          => 'required|string|max:150',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|confirmed|min:6',
            'role'          => 'required|string',
            'status'        => 'required|string',
            'gender'        => 'nullable|string|max:20',
            'city'          => 'nullable|string|max:100',
            'state'         => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|max:5120',
        ]);

        try {
            $user = new User();
            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->status = $request->status;
            $user->gender = $request->gender ?? null;
            $user->city = $request->city ?? null;
            $user->state = $request->state ?? null;
            $user->password = bcrypt($request->password);

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                if (! is_dir(public_path('uploads/users'))) {
                    mkdir(public_path('uploads/users'), 0777, true);
                }

                $image->move(public_path('uploads/users'), $imageName);
                $user->profile_image = 'uploads/users/' . $imageName;
            } else {
                $user->profile_image = 'src/images/avatar.png';
            }

            $user->save();

            AuditLogService::log(
                event: 'created',
                description: 'Created user: ' . ($user->name ?? $user->username),
                category: 'user',
                subject: $user,
                newValues: $this->auditUserPayload($user),
                properties: [
                    'created_by_guard' => Auth::guard('admin')->check() ? 'admin' : 'web',
                ]
            );

            return redirect()
                ->route('manager.pending.users')
                ->with('success', '✔ تم إضافة المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '❌ حدث خطأ أثناء الإضافة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /** تعديل مستخدم */
    public function edit(User $user)
    {
        return view('manager.edit_user', compact('user'));
    }

    /** تحديث بيانات مستخدم */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'   => 'required|string|max:150',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive,disabled,pending',
            'role'   => 'nullable|string|max:50',
        ]);

        $oldValues = $this->auditUserPayload($user);

        $user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'status' => $request->status,
            'role'   => $request->role ?? $user->role,
        ]);

        $user->refresh();

        AuditLogService::log(
            event: 'updated',
            description: 'Updated user: ' . ($user->name ?? $user->username),
            category: 'user',
            subject: $user,
            oldValues: $oldValues,
            newValues: $this->auditUserPayload($user)
        );

        return redirect()
            ->route('manager.pending.users')
            ->with('success', '✔ تم تحديث بيانات المستخدم بنجاح.');
    }

    /** حذف مستخدم */
    public function destroy(User $user)
    {
        AuditLogService::log(
            event: 'deleted',
            description: 'Deleted user: ' . ($user->name ?? $user->username),
            category: 'user',
            subject: $user,
            oldValues: $this->auditUserPayload($user)
        );

        $user->delete();

        return redirect()->back()->with('success', '✔ تم حذف المستخدم بنجاح');
    }

    /** إجبار المستخدم على تسجيل الخروج */
    public function forceLogout($userId)
    {
        $user = User::findOrFail($userId);

        AuditLogService::log(
            event: 'force_logout',
            description: 'Forced logout for user: ' . ($user->name ?? $user->username),
            category: 'auth',
            subject: $user,
            properties: [
                'forced_user_id'    => $user->id,
                'forced_user_email' => $user->email,
                'forced_user_role'  => $user->role,
            ]
        );

        return redirect()->back()->with('success', "✔ تم تسجيل خروج {$user->name} بنجاح.");
    }

    /** عرض بيانات المستخدم JSON */
    public function show(User $user)
    {
        return response()->json($user);
    }

    protected function auditUserPayload(User $user): array
    {
        return [
            'id'            => $user->id,
            'username'      => $user->username,
            'name'          => $user->name,
            'email'         => $user->email,
            'role'          => $user->role,
            'status'        => $user->status,
            'gender'        => $user->gender,
            'city'          => $user->city,
            'state'         => $user->state,
            'profile_image' => $user->profile_image,
        ];
    }
}