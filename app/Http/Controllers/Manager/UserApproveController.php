<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Jenssegers\Agent\Agent;
use App\Models\ActivityLog;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserApproveController extends Controller
{
    /* ---------------------------------------------------------
     * 🟢 Dashboard صفحة المدير
     * --------------------------------------------------------- */
public function dashboard()
{
    $stats = [
        'total_users'        => User::count(),
        'active_users'       => User::where('status', 'active')->count(),
        'students'           => User::where('role', 'Student')->count(),
        'investors'          => User::where('role', 'Investor')->count(),
        'managers'           => User::where('role', 'Manager')->count(),

        'total_projects'     => Project::count(),
        'pending_projects'   => Project::where('status', 'pending')->count(),
        'active_projects'    => Project::where('status', 'active')->count(),
        'completed_projects' => Project::where('status', 'completed')->count(),
        'rejected_projects'  => Project::where('status', 'rejected')->count(),

        'total_funding'      => Project::whereIn('status', ['active', 'completed'])->sum('budget'),
    ];

    $recentProjects = Project::with(['student', 'manager'])
        ->latest('project_id')
        ->take(6)
        ->get();

    $recentStudents = User::where('role', 'Student')
        ->latest('id')
        ->take(5)
        ->get();

    $recentInvestors = User::where('role', 'Investor')
        ->latest('id')
        ->take(5)
        ->get();

    $months = collect(range(5, 0))->map(function ($i) {
        return Carbon::now()->subMonths($i)->format('M');
    })->values();

    $submittedByMonth = collect(range(5, 0))->map(function ($i) {
        $date = Carbon::now()->subMonths($i);
        return Project::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
    })->values();

    $activeByMonth = collect(range(5, 0))->map(function ($i) {
        $date = Carbon::now()->subMonths($i);
        return Project::where('status', 'active')
            ->whereYear('updated_at', $date->year)
            ->whereMonth('updated_at', $date->month)
            ->count();
    })->values();

    $rejectedByMonth = collect(range(5, 0))->map(function ($i) {
        $date = Carbon::now()->subMonths($i);
        return Project::where('status', 'rejected')
            ->whereYear('updated_at', $date->year)
            ->whereMonth('updated_at', $date->month)
            ->count();
    })->values();

    $completedByMonth = collect(range(5, 0))->map(function ($i) {
        $date = Carbon::now()->subMonths($i);
        return Project::where('status', 'completed')
            ->whereYear('updated_at', $date->year)
            ->whereMonth('updated_at', $date->month)
            ->count();
    })->values();

    $this->logActivity('View', 'Dashboard', 'Accessed Manager Dashboard');

    return view('manager.dashboard', compact(
        'stats',
        'recentProjects',
        'recentStudents',
        'recentInvestors',
        'months',
        'submittedByMonth',
        'activeByMonth',
        'rejectedByMonth',
        'completedByMonth'
    ));
}

    /* ---------------------------------------------------------
     * 🟢 عرض المستخدمين المعلقين
     * --------------------------------------------------------- */
public function pendingUsers()
{
    $allUsers      = User::all(); // ← جميع المستخدمين
    $allCount      = $allUsers->count(); // ← العدد الإجمالي

    $pendingUsers  = User::where('status', 'pending')->get();
    $activeUsers   = User::where('status', 'active')->get();
    $inactiveUsers = User::where('status', 'inactive')->get();
    $disabledUsers = User::where('status', 'disabled')->get();

    // احصائيات الحالات
    $pendingCount  = $pendingUsers->count();
    $activeCount   = $activeUsers->count();
    $inactiveCount = $inactiveUsers->count();
    $disabledCount = $disabledUsers->count();

    $this->logActivity('View', 'PendingUsers', 'Viewed pending users list');

    return view('manager.pending_users', compact(
        'allUsers',      
        'pendingUsers',
        'activeUsers',
        'inactiveUsers',
        'disabledUsers',
         'allCount', 
        'pendingCount',
        'activeCount',
        'inactiveCount',
        'disabledCount'
    ));
}



    /* ---------------------------------------------------------
     * 🟢 تغيير حالة المستخدم (active, inactive, disabled)
     * --------------------------------------------------------- */
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'status' => 'required|in:active,inactive,disabled'
        ]);

        $oldStatus = $user->status;
        $user->status = $request->status;
        $user->save();

        $this->logActivity(
            'Update',
            'User',
            "Changed status of user {$user->username} from {$oldStatus} to {$user->status}"
        );

        return back()->with('status', 'تم تحديث حالة المستخدم.');
    }

    /* ---------------------------------------------------------
     * 🟢 تفعيل مباشر AJAX
     * --------------------------------------------------------- */
    public function approveDirect($id)
    {
        try {
            $user = User::findOrFail($id);
            $oldStatus = $user->status;
            $user->status = 'active';
            $user->save();

            $this->logActivity(
                'Update',
                'User',
                "Activated user {$user->username} (Status: {$oldStatus} → active)"
            );

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /* ---------------------------------------------------------
     * 🟢 تعطيل المستخدم
     * --------------------------------------------------------- */
    public function reject($id)
    {
        try {
            $user = User::findOrFail($id);
            $oldStatus = $user->status;
            $user->status = 'disabled';
            $user->save();

            $this->logActivity(
                'Update',
                'User',
                "Disabled user {$user->username} (Status: {$oldStatus} → disabled)"
            );

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /* ---------------------------------------------------------
     * 🔹 تسجيل النشاط
     * --------------------------------------------------------- */
    private function logActivity($action, $model, $description = null)
    {
        if (Auth::check()) {

            $user = Auth::user();
            $agent = new Agent();

            // تحديث آخر نشاط
            $user->update(['last_activity' => now()]);

            // تسجيل النشاط
            ActivityLog::create([
                'user_id' => $user->id,
                'action'  => $action,
                'model'   => $model,
                'description' => $description,
                'ip'      => request()->ip(),
                'device'  => $agent->device(),
                'browser' => $agent->browser(),
                'os'      => $agent->platform(),
            ]);
        }
    }

    /* ---------------------------------------------------------
     * 🟢 عرض فورم إنشاء مستخدم جديد
     * --------------------------------------------------------- */
    public function create()
    {
        return view('manager.create_user');
    }

    /* ---------------------------------------------------------
     * 🟢 حفظ المستخدم الجديد
     * --------------------------------------------------------- */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:Manager,Supervisor,Student',
        ]);

        $user = User::create([
            'username' => $request->username,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
            'status'   => 'pending',
        ]);

        $this->logActivity(
            'Create',
            'User',
            "Created new user {$user->username} with role {$user->role}"
        );

        return redirect()->route('manager.pending.users')
            ->with('success', 'User created successfully!');
    }
public function index()
{
    // جميع المستخدمين
    $allUsers = User::latest()->get();

    // حسب الحالة
    $pendingUsers  = User::where('status', 'pending')->latest()->get();
    $activeUsers   = User::where('status', 'active')->latest()->get();
    $inactiveUsers = User::where('status', 'inactive')->latest()->get();
    $disabledUsers = User::where('status', 'disabled')->latest()->get();

    // العدّادات
    $allCount      = $allUsers->count();
    $pendingCount  = $pendingUsers->count();
    $activeCount   = $activeUsers->count();
    $inactiveCount = $inactiveUsers->count();
    $disabledCount = $disabledUsers->count();

    return view('manager.pending_users', compact(
        'allUsers',
        'pendingUsers',
        'activeUsers',
        'inactiveUsers',
        'disabledUsers',
        'allCount',
        'pendingCount',
        'activeCount',
        'inactiveCount',
        'disabledCount'
    ));
}

    /* ---------------------------------------------------------
     * 🟢 تحرير مستخدم
     * --------------------------------------------------------- */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('manager.edit_user', compact('user'));
    }

    /* ---------------------------------------------------------
     * 🟢 تحديث بيانات المستخدم
     * --------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only([
            'name',
            'email',
            'status'
        ]));

        return redirect()->route('manager.pending.users')
            ->with('success', 'User updated successfully.');
    }
}
