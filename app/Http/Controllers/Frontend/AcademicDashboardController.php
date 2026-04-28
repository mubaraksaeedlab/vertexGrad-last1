<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Models\ProjectRequestResponse;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AcademicDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('web')->user();

        abort_unless($user && $user->role === 'Student', 403);

        $projects = Project::where('student_id', $user->id)
            ->with('media')
            ->latest('project_id')
            ->get();

        $selectedId = (int) $request->query('project', 0);
        $currentProject = $selectedId ? $projects->firstWhere('project_id', $selectedId) : null;

        if (!$currentProject) {
            $currentProject = $projects->first();
        }

        $progress = 20;
        if ($currentProject) {
            if ($currentProject->status === 'active') {
                $progress = 60;
            } elseif ($currentProject->status === 'completed') {
                $progress = 100;
            } elseif ($currentProject->status === 'rejected') {
                $progress = 0;
            } elseif ($currentProject->status === 'pending') {
                $progress = 35;
            }
        }

        $currentImages = collect();
        $currentVideoUrl = null;
        $currentRequests = collect();
        $currentMeetings = collect();

        if ($currentProject) {
            $currentImages = method_exists($currentProject, 'getMedia')
                ? $currentProject->getMedia('images')
                : collect();

            $currentVideoUrl = method_exists($currentProject, 'getFirstMediaUrl')
                ? $currentProject->getFirstMediaUrl('videos')
                : null;

            $currentRequests = method_exists($currentProject, 'requests')
                ? $currentProject->requests()
                    ->with(['supervisor', 'latestResponse'])
                    ->latest()
                    ->get()
                : collect();

            $currentMeetings = method_exists($currentProject, 'meetings')
                ? $currentProject->meetings()
                    ->latest('meeting_date')
                    ->get()
                : collect();
        }

        $announcements = Announcement::published()
            ->where(function ($query) {
                $query->where('audience', 'all')
                      ->orWhere('audience', 'students');
            })
            ->ordered()
            ->get();

        return view('frontend.dashboard.academic', compact(
            'user',
            'projects',
            'currentProject',
            'progress',
            'currentImages',
            'currentVideoUrl',
            'currentRequests',
            'currentMeetings',
            'announcements'
        ));
    }

    public function settings()
    {
        $user = auth('web')->user();

        abort_unless($user && $user->role === 'Student', 403);

        $projects = Project::where('student_id', $user->id)
            ->latest('project_id')
            ->get();

        return view('frontend.settings.academic', compact('user', 'projects'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth('web')->user();

        abort_unless($user && $user->role === 'Student', 403);

        $validated = $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'academic_title' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'institution' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'notif_status_change' => ['nullable'],
            'notif_investor_interest' => ['nullable'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $userTable = $user->getTable();

        if (array_key_exists('full_name', $validated) && !empty($validated['full_name']) && Schema::hasColumn($userTable, 'name')) {
            $user->name = $validated['full_name'];
        }

        if (array_key_exists('email', $validated) && !empty($validated['email']) && Schema::hasColumn($userTable, 'email')) {
            $user->email = $validated['email'];
        }

        if (array_key_exists('phone', $validated) && Schema::hasColumn($userTable, 'phone')) {
            $user->phone = $validated['phone'];
        }

        if (array_key_exists('academic_title', $validated) && Schema::hasColumn($userTable, 'academic_title')) {
            $user->academic_title = $validated['academic_title'];
        }

        if (array_key_exists('institution', $validated) && Schema::hasColumn($userTable, 'institution')) {
            $user->institution = $validated['institution'];
        }

        if (array_key_exists('department', $validated) && Schema::hasColumn($userTable, 'department')) {
            $user->department = $validated['department'];
        }

        if (Schema::hasColumn($userTable, 'notif_status_change')) {
            $user->notif_status_change = $request->boolean('notif_status_change');
        }

        if (Schema::hasColumn($userTable, 'notif_investor_interest')) {
            $user->notif_investor_interest = $request->boolean('notif_investor_interest');
        }

        if ($request->hasFile('profile_image') && Schema::hasColumn($userTable, 'profile_image')) {
            if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->save();

        return redirect()
            ->route('settings.academic')
            ->with('success', 'Academic settings updated successfully.');
    }

    public function respondToRequest(Request $request, ProjectRequest $requestItem)
    {
        $user = auth('web')->user();

        abort_unless($user && $user->role === 'Student', 403);
        abort_unless((int) $requestItem->student_id === (int) $user->id, 403);

        $validated = $request->validate([
            'response_text' => ['nullable', 'string'],
            'response_link' => ['nullable', 'url', 'max:500'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,zip,mp4,mov,doc,docx,ppt,pptx,xls,xlsx',
                'max:20480',
            ],
        ]);

        if (
            empty($validated['response_text']) &&
            empty($validated['response_link']) &&
            !$request->hasFile('attachment')
        ) {
            return redirect()
                ->back()
                ->with('error', 'Please provide a response text, a link, or an attachment.');
        }

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('project-request-responses', 'public');
        }

        ProjectRequestResponse::create([
            'project_request_id' => $requestItem->id,
            'student_id' => $user->id,
            'response_text' => $validated['response_text'] ?? null,
            'response_link' => $validated['response_link'] ?? null,
            'attachment_path' => $attachmentPath,
            'submitted_at' => now(),
        ]);

        $requestItem->update([
            'status' => 'completed',
        ]);

        if ($requestItem->supervisor) {
            $isSystemVerification = strtolower($requestItem->request_type ?? '') === 'system_verification';

$requestItem->supervisor->notify(new GeneralNotification([
    'key' => $isSystemVerification
        ? 'system_verification_submitted'
        : 'student_response_submitted',
    'request_title' => $requestItem->title,
    'project_id' => $requestItem->project_id,
    'url' => route('supervisor.projects.show', $requestItem->project_id),
    'icon' => $isSystemVerification ? 'fas fa-server' : 'fas fa-reply',
    'type' => $isSystemVerification
        ? 'system_verification_submitted'
        : 'student_response_submitted',
]));
        }

        return redirect()
            ->back()
            ->with('success', 'Your response has been submitted successfully.');
    }
}