<?php

namespace App\Services\Reports;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportBuilderService
{
    public function build(array $filters): array
    {
        [$from, $to] = $this->resolveDateRange($filters);

        return match ($filters['entity']) {
            'projects'  => $this->buildProjectsReport($filters, $from, $to),
            'students'  => $this->buildStudentsReport($filters, $from, $to),
            'investors' => $this->buildInvestorsReport($filters, $from, $to),
            'platform'  => $this->buildPlatformSummary($filters, $from, $to),
            default     => throw new \InvalidArgumentException('Unsupported report entity.'),
        };
    }

    protected function resolveDateRange(array $filters): array
    {
        return match ($filters['period']) {
            'daily'   => [now()->startOfDay(), now()->endOfDay()],
            'weekly'  => [now()->startOfWeek(), now()->endOfWeek()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly'  => [now()->startOfYear(), now()->endOfYear()],
            'custom'  => [
                isset($filters['from']) ? Carbon::parse($filters['from'])->startOfDay() : now()->startOfMonth(),
                isset($filters['to']) ? Carbon::parse($filters['to'])->endOfDay() : now()->endOfMonth(),
            ],
        };
    }

    protected function buildProjectsReport(array $filters, Carbon $from, Carbon $to): array
    {
        $query = Project::query()
            ->with(['student', 'supervisor', 'manager', 'investors', 'reviews'])
            ->whereBetween('created_at', [$from, $to]);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['final_decision'])) {
            $query->where('final_decision', $filters['final_decision']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['student_name'])) {
            $query->whereHas('student', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['student_name'] . '%');
            });
        }

        $projects = $query->latest()->get();

        $rows = $projects->map(function ($project) use ($filters) {
            $row = [
                'project_id'      => $project->project_id,
                'name'            => $project->name,
                'category'        => $project->category,
                'status'          => $project->status,
                'final_decision'  => $project->final_decision,
                'budget'          => $project->budget,
                'created_at'      => optional($project->created_at)->format('Y-m-d H:i'),
                'student_name'    => $project->student?->name,
                'student_email'   => $project->student?->email,
                'supervisor_name' => $project->supervisor?->name,
                'manager_name'    => $project->manager?->name,
                'investors_count' => $project->investors?->count() ?? 0,
                'average_score'   => round($project->reviews->whereNotNull('score')->avg('score') ?? 0, 1),
            ];

            return $this->filterColumns($row, $filters['columns']);
        });

        return [
            'title' => 'Projects Report',
            'entity' => 'projects',
            'from' => $from,
            'to' => $to,
            'headings' => $this->resolveHeadings($rows),
            'rows' => $rows,
            'summary' => [
                'Total Projects' => $projects->count(),
                'Published' => $projects->where('final_decision', 'published')->count(),
                'Revision Requested' => $projects->where('final_decision', 'revision_requested')->count(),
                'Rejected' => $projects->where('final_decision', 'rejected')->count(),
            ],
        ];
    }

    protected function buildStudentsReport(array $filters, Carbon $from, Carbon $to): array
    {
        $query = User::query()
            ->where('role', 'Student')
            ->with(['projects' => function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            }]);

        if (!empty($filters['student_name'])) {
            $query->where('name', 'like', '%' . $filters['student_name'] . '%');
        }

        $students = $query->latest()->get();

        $rows = $students->map(function ($student) use ($filters) {
            $latestProject = $student->projects->sortByDesc('created_at')->first();

            $row = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'created_at' => optional($student->created_at)->format('Y-m-d H:i'),
                'projects_count' => $student->projects->count(),
                'latest_project' => $latestProject?->name,
            ];

            return $this->filterColumns($row, $filters['columns']);
        });

        return [
            'title' => 'Students Report',
            'entity' => 'students',
            'from' => $from,
            'to' => $to,
            'headings' => $this->resolveHeadings($rows),
            'rows' => $rows,
            'summary' => [
                'Total Students' => $students->count(),
                'Students With Projects' => $students->filter(fn ($s) => $s->projects->count() > 0)->count(),
            ],
        ];
    }

    protected function buildInvestorsReport(array $filters, Carbon $from, Carbon $to): array
    {
        $query = User::query()
            ->where('role', 'Investor')
            ->with(['investedProjects' => function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to])->with('student');
            }]);

        if (!empty($filters['investor_name'])) {
            $query->where('name', 'like', '%' . $filters['investor_name'] . '%');
        }

        $investors = $query->latest()->get();

        $rows = $investors->map(function ($investor) use ($filters) {
            $projectNames = $investor->investedProjects->pluck('name')->filter()->implode(', ');

            $row = [
                'id' => $investor->id,
                'name' => $investor->name,
                'email' => $investor->email,
                'created_at' => optional($investor->created_at)->format('Y-m-d H:i'),
                'projects_count' => $investor->investedProjects->count(),
                'project_names' => $projectNames,
            ];

            return $this->filterColumns($row, $filters['columns']);
        });

        return [
            'title' => 'Investors Report',
            'entity' => 'investors',
            'from' => $from,
            'to' => $to,
            'headings' => $this->resolveHeadings($rows),
            'rows' => $rows,
            'summary' => [
                'Total Investors' => $investors->count(),
                'Active Investors' => $investors->filter(fn ($i) => $i->investedProjects->count() > 0)->count(),
            ],
        ];
    }

    protected function buildPlatformSummary(array $filters, Carbon $from, Carbon $to): array
    {
        $students = User::where('role', 'Student')->count();
        $investors = User::where('role', 'Investor')->count();

        $projects = Project::whereBetween('created_at', [$from, $to])->get();

        $rows = collect([
            $this->filterColumns([
                'total_students'      => $students,
                'total_investors'     => $investors,
                'total_projects'      => $projects->count(),
                'published_projects'  => $projects->where('final_decision', 'published')->count(),
                'revision_projects'   => $projects->where('final_decision', 'revision_requested')->count(),
                'rejected_projects'   => $projects->where('final_decision', 'rejected')->count(),
            ], $filters['columns']),
        ]);

        return [
            'title' => 'Platform Summary Report',
            'entity' => 'platform',
            'from' => $from,
            'to' => $to,
            'headings' => $this->resolveHeadings($rows),
            'rows' => $rows,
            'summary' => [
                'Students' => $students,
                'Investors' => $investors,
                'Projects In Period' => $projects->count(),
            ],
        ];
    }

    protected function filterColumns(array $row, array $columns): array
    {
        if (empty($columns)) {
            return $row;
        }

        return collect($row)
            ->only($columns)
            ->toArray();
    }

    protected function resolveHeadings(Collection $rows): array
    {
        if ($rows->isEmpty()) {
            return [];
        }

        return array_keys($rows->first());
    }
}