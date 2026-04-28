<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InvestorDashboardController extends Controller
{
    public function index()
    {
        $user = auth('web')->user();

        abort_unless($user && $user->role === 'Investor', 403);

        $myInvestments = $this->getInvestorProjects($user);
        $totalDeployed = $this->calculateTotalDeployed($myInvestments);

        $interactedProjectIds = $this->getInteractedProjectIds($user);
        $preferredCategories = $this->getPreferredCategories($user);

        $suggestedProjects = $this->getSuggestedProjects(
            $preferredCategories,
            $interactedProjectIds,
            3
        );

        $marketplaceStats = $this->getMarketplaceStats($myInvestments);
        $announcements = $this->getInvestorAnnouncements();

        return view('frontend.dashboard.investor', compact(
            'myInvestments',
            'totalDeployed',
            'suggestedProjects',
            'marketplaceStats',
            'announcements'
        ));
    }

    public function investments()
    {
        $user = auth('web')->user();

        abort_unless($user && $user->role === 'Investor', 403);

        $projects = $user->investments()
            ->with('student')
            ->orderByPivot('created_at', 'desc')
            ->get();

        return view('frontend.investor.investments', compact('projects'));
    }

    public function settings()
    {
        $user = auth('web')->user();

        abort_unless($user && $user->role === 'Investor', 403);

        $myInvestments = $user->investments()->get();
        $approvedInvestments = $myInvestments->where('pivot.status', 'approved')->count();

        return view('frontend.settings.investor', compact(
            'user',
            'myInvestments',
            'approvedInvestments'
        ));
    }

    public function updateSettings(Request $request)
    {
        $user = auth('web')->user();

        abort_unless($user && $user->role === 'Investor', 403);

        $validated = $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'fund_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'min_investment' => ['nullable', 'numeric', 'min:0'],
            'investment_focus' => ['nullable', 'string', 'max:1000'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $userTable = $user->getTable();

        if (array_key_exists('full_name', $validated) && !empty($validated['full_name']) && Schema::hasColumn($userTable, 'name')) {
            $user->name = $validated['full_name'];
        }

        if (array_key_exists('email', $validated) && !empty($validated['email']) && Schema::hasColumn($userTable, 'email')) {
            $user->email = $validated['email'];
        }

        if (array_key_exists('contact_name', $validated) && Schema::hasColumn($userTable, 'contact_name')) {
            $user->contact_name = $validated['contact_name'];
        }

        if (array_key_exists('fund_name', $validated) && Schema::hasColumn($userTable, 'fund_name')) {
            $user->fund_name = $validated['fund_name'];
        }

        if (array_key_exists('phone', $validated) && Schema::hasColumn($userTable, 'phone')) {
            $user->phone = $validated['phone'];
        }

        if (array_key_exists('city', $validated) && Schema::hasColumn($userTable, 'city')) {
            $user->city = $validated['city'];
        }

        if (array_key_exists('min_investment', $validated) && Schema::hasColumn($userTable, 'min_investment')) {
            $user->min_investment = $validated['min_investment'];
        }

        if (array_key_exists('investment_focus', $validated) && Schema::hasColumn($userTable, 'investment_focus')) {
            $user->investment_focus = $validated['investment_focus'];
        }

        if ($request->hasFile('profile_image') && Schema::hasColumn($userTable, 'profile_image')) {
            if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->save();

        return redirect()
            ->route('settings.investor')
            ->with('success', 'Investor settings updated successfully.');
    }

    private function getInvestorProjects($user): Collection
    {
        return $user->investments()
            ->with(['student', 'media', 'investors'])
            ->orderByPivot('created_at', 'desc')
            ->get();
    }

    private function calculateTotalDeployed(Collection $investments): float
    {
        return (float) $investments
            ->where('pivot.status', 'approved')
            ->sum(function ($project) {
                return (float) ($project->pivot->amount ?? 0);
            });
    }

    private function getInteractedProjectIds($user): array
    {
        return $user->investments()
            ->pluck('projects.project_id')
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values()
            ->all();
    }

    private function getPreferredCategories($user): Collection
    {
        return $user->investments()
            ->whereNotNull('projects.category')
            ->pluck('projects.category')
            ->filter(function ($category) {
                return filled(trim((string) $category));
            })
            ->map(function ($category) {
                return trim((string) $category);
            })
            ->unique()
            ->values();
    }

    private function getSuggestedProjects(Collection $preferredCategories, array $interactedProjectIds, int $limit = 3): Collection
    {
        $query = Project::query()
            ->with(['student', 'media', 'investors'])
            ->whereIn('status', ['published', 'active']);

        if (!empty($interactedProjectIds)) {
            $query->whereNotIn('project_id', $interactedProjectIds);
        }

        if ($preferredCategories->isNotEmpty()) {
            $placeholders = implode(',', array_fill(0, $preferredCategories->count(), '?'));

            $query->orderByRaw(
                "CASE WHEN category IN ($placeholders) THEN 0 ELSE 1 END",
                $preferredCategories->all()
            );
        }

        $query->orderByRaw("CASE WHEN status = 'published' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN budget IS NULL OR budget = 0 THEN 1 ELSE 0 END")
            ->latest('project_id');

        $projects = $query->take($limit)->get();

        if ($projects->count() >= $limit) {
            return $projects;
        }

        $existingIds = $projects->pluck('project_id')->map(fn ($id) => (int) $id)->all();

        $fallbackQuery = Project::query()
            ->with(['student', 'media', 'investors'])
            ->whereIn('status', ['published', 'active']);

        if (!empty($interactedProjectIds)) {
            $fallbackQuery->whereNotIn('project_id', $interactedProjectIds);
        }

        if (!empty($existingIds)) {
            $fallbackQuery->whereNotIn('project_id', $existingIds);
        }

        $fallbackProjects = $fallbackQuery
            ->orderByRaw("CASE WHEN status = 'published' THEN 0 ELSE 1 END")
            ->latest('project_id')
            ->take($limit - $projects->count())
            ->get();

        return $projects->concat($fallbackProjects);
    }

    private function getMarketplaceStats(Collection $myInvestments): array
    {
        return [
            'active_projects'  => Project::whereIn('status', ['active', 'published'])->count(),
            'interested_count' => $myInvestments->where('pivot.status', 'interested')->count(),
            'requested_count'  => $myInvestments->where('pivot.status', 'requested')->count(),
            'approved_count'   => $myInvestments->where('pivot.status', 'approved')->count(),
        ];
    }

    private function getInvestorAnnouncements(): Collection
    {
        return Announcement::published()
            ->where(function ($query) {
                $query->where('audience', 'all')
                      ->orWhere('audience', 'investors');
            })
            ->ordered()
            ->get();
    }
}