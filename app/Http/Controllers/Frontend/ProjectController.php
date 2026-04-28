<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InvestorInterestSubmittedNotification;
use App\Notifications\FundingRequestSubmittedNotification;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['media', 'student', 'investors', 'projectCategory'])
            ->whereIn('status', ['active', 'published']);

        $search = trim((string) $request->get('search', ''));
        $selectedCategory = trim((string) $request->get('category', ''));
        $sort = trim((string) $request->get('sort', 'latest'));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('projectCategory', function ($categoryQuery) use ($search) {
                      $categoryQuery->where('name_en', 'like', "%{$search}%")
                                    ->orWhere('name_ar', 'like', "%{$search}%");
                  })
                  ->orWhereHas('student', function ($studentQuery) use ($search) {
                      $studentQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($selectedCategory !== '') {
            $query->where(function ($q) use ($selectedCategory) {
                $q->whereRaw('LOWER(TRIM(category)) = ?', [mb_strtolower($selectedCategory)])
                  ->orWhereHas('projectCategory', function ($categoryQuery) use ($selectedCategory) {
                      $categoryQuery->whereRaw('LOWER(TRIM(name_en)) = ?', [mb_strtolower($selectedCategory)])
                                    ->orWhereRaw('LOWER(TRIM(name_ar)) = ?', [mb_strtolower($selectedCategory)]);
                  });
            });
        }

        if ($request->filled('budget_min')) {
            $query->where('budget', '>=', (float) $request->budget_min);
        }

        if ($request->filled('budget_max')) {
            $query->where('budget', '<=', (float) $request->budget_max);
        }

        switch ($sort) {
            case 'oldest':
                $query->orderBy('project_id', 'asc');
                break;

            case 'budget_low':
                $query->orderBy('budget', 'asc')
                      ->orderBy('project_id', 'desc');
                break;

            case 'budget_high':
                $query->orderBy('budget', 'desc')
                      ->orderBy('project_id', 'desc');
                break;

            case 'name':
                $query->orderBy('name', 'asc');
                break;

            case 'latest':
            default:
                $query->latest('project_id');
                break;
        }

        $projects = $query->paginate(12)->appends($request->all());

        $categories = \App\Models\ProjectCategory::query()
            ->where('is_active', true)
            ->orderBy('name_en')
            ->get()
            ->map(function ($category) {
                return $category->display_name;
            });

        return view('frontend.projects.index', compact('projects', 'categories'));
    }

    public function show(Project $project)
    {
        $project->load(['media', 'student', 'files', 'investors', 'projectCategory']);

        $user = auth('web')->user();

        if ($user && $user->role === 'Student') {
            if ($project->student_id !== $user->id) {
                abort(403);
            }
        } else {
            if (!in_array($project->status, ['active', 'published'])) {
                abort(404);
            }
        }

        return view('frontend.projects.show', compact('project'));
    }

    public function mediaForm(Project $project)
    {
        if (!auth('web')->check() || auth('web')->id() !== $project->student_id) {
            abort(403);
        }

        if (!in_array($project->status, ['approved', 'published', 'completed'])) {
            return redirect()
                ->route('dashboard.academic')
                ->with('error', 'Media uploads are allowed only after project approval.');
        }

        return view('frontend.projects.media_upload', compact('project'));
    }

    public function mediaUpload(Request $request, Project $project)
    {
        if (!auth('web')->check() || auth('web')->id() !== $project->student_id) {
            abort(403);
        }

        if (!in_array($project->status, ['approved', 'published', 'completed'])) {
            return redirect()
                ->route('dashboard.academic')
                ->with('error', 'Media uploads are allowed only after project approval.');
        }

        $request->validate([
            'project_photos' => 'nullable|array',
            'project_photos.*' => 'image|max:5120',
            'project_video' => 'nullable|mimes:mp4,mov,ogg,qt|max:51200',
        ]);

        if ($request->hasFile('project_photos')) {
            foreach ($request->file('project_photos') as $img) {
                $project->addMedia($img)->toMediaCollection('images');
            }
        }

        if ($request->hasFile('project_video')) {
            $project->addMedia($request->file('project_video'))->toMediaCollection('videos');
        }

        return redirect()
            ->route('dashboard.academic')
            ->with('success', 'Media uploaded successfully.');
    }

    public function invest(Project $project)
    {
        $user = Auth::guard('web')->user();

        if (!$user || $user->role !== 'Investor') {
            abort(403);
        }

        if ($project->status !== 'published') {
            return back()->with('error', 'This project is not open for investment.');
        }

        if ($project->investors()->where('users.id', $user->id)->exists()) {
            return back()->with('error', 'You already expressed interest in this project.');
        }

        $project->investors()->attach($user->id, [
            'status' => 'interested',
            'amount' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managers = User::where('role', 'Manager')
            ->where('status', 'active')
            ->get();

        Notification::send($managers, new InvestorInterestSubmittedNotification($project, $user));

        return back()->with('success', 'Your investment interest was submitted successfully.');
    }

    public function removeInterest(Project $project)
    {
        $user = auth('web')->user();

        if (!$user || $user->role !== 'Investor') {
            abort(403);
        }

        $project->investors()->detach($user->id);

        return back()->with('success', 'Your interest was removed successfully.');
    }

    public function requestFunding(Request $request, Project $project)
    {
        $user = auth('web')->user();

        if (!$user || $user->role !== 'Investor') {
            abort(403);
        }

        if ($project->status !== 'published') {
            return back()->with('error', 'This project is not open for funding requests.');
        }

        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'message' => 'required|string|max:2000',
        ]);

        $existing = $project->investors()->where('users.id', $user->id)->first();

        if ($existing && in_array($existing->pivot->status, ['requested', 'approved'])) {
            return back()->with('error', 'You already submitted a funding request for this project.');
        }

        $project->investors()->syncWithoutDetaching([
            $user->id => [
                'status' => 'requested',
                'amount' => $data['amount'] ?? null,
                'message' => $data['message'] ?? null,
                'updated_at' => now(),
                'created_at' => $existing ? $existing->pivot->created_at : now(),
            ]
        ]);

        $managers = User::where('role', 'Manager')
            ->where('status', 'active')
            ->get();

        Notification::send(
            $managers,
            new FundingRequestSubmittedNotification($project, $user, $data['amount'] ?? null)
        );

        return back()->with('success', 'Your funding request was submitted successfully.');
    }

    public function approve(Project $project)
    {
        $project->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project approved successfully.');
    }

    public function publish(Project $project)
    {
        $project->update([
            'status' => 'published',
        ]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project published successfully.');
    }
}