<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // عرض كل الطلاب
    public function index(Request $request)
    {
        $query = User::where('role', 'Student')->with('student');

        if ($request->filled('search')) {
            $search = (string) $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $students = $query->paginate(15);

        return view('students.index', compact('students'));
    }

    // إنشاء صفحة إضافة طالب
    public function create()
    {
        return view('students.create');
    }

    // حفظ طالب جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'status' => 'nullable|string|in:active,pending,inactive,disabled',
            'major' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'username' => explode('@', $validated['email'])[0],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'Student',
            'status' => $validated['status'] ?? 'active',
            'password' => bcrypt('12345678'),
        ]);

        if (
            !empty($validated['major']) ||
            !empty($validated['phone']) ||
            !empty($validated['address'])
        ) {
            $user->student()->create([
                'major' => $validated['major'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        }

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    // عرض صفحة تعديل الطالب
    public function edit(User $student)
    {
        $student->load('student');

        return view('students.edit', compact('student'));
    }

    // تحديث بيانات الطالب والمستخدم
    public function update(Request $request, User $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $student->getKey(),
            'status' => 'required|string|in:active,pending,inactive,disabled',
            'major' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $student->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ]);

        $studentProfile = $student->student()->first();

        if ($studentProfile) {
            $studentProfile->update([
                'major' => $validated['major'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        } elseif (
            !empty($validated['major']) ||
            !empty($validated['phone']) ||
            !empty($validated['address'])
        ) {
            $student->student()->create([
                'major' => $validated['major'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        }

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    // حذف الطالب والمستخدم
    public function destroy(User $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    // عرض تفاصيل الطالب
    public function show(User $student)
    {
        $student->load('student');

        return view('students.show', compact('student'));
    }

    public function updateStatus($id, $status)
    {
        // التحقق أن الحالة صحيحة
        if (!in_array($status, ['active', 'inactive', 'pending', 'disabled'], true)) {
            return back()->with('error', 'Invalid status value.');
        }

        // جلب المستخدم الطالب
        $student = User::where('role', 'Student')->findOrFail($id);

        // تحديث الحالة
        $student->update(['status' => $status]);

        return redirect()->route('students.index')->with('success', 'Status updated successfully.');
    }
}