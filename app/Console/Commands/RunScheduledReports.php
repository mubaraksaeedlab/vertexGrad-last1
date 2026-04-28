<?php

namespace App\Console\Commands;

use App\Mail\ScheduledReportReadyMail;
use App\Models\ReportExport;
use App\Models\ScheduledReport;
use App\Services\Reports\ReportBuilderService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Spatie\LaravelPdf\Facades\Pdf;

class RunScheduledReports extends Command
{
    protected $signature = 'reports:run-scheduled';
    protected $description = 'Run all due scheduled reports, generate export files, and send them by email if configured';

    public function handle(ReportBuilderService $reportBuilder): int
    {
        $dueReports = ScheduledReport::with(['template', 'creator'])
            ->where('is_active', true)
            ->whereNotNull('next_run_at')
            ->where('next_run_at', '<=', now())
            ->get();

        if ($dueReports->isEmpty()) {
            $this->info('No scheduled reports due right now.');
            return self::SUCCESS;
        }

        $storagePath = storage_path('app/reports');

        if (! File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        foreach ($dueReports as $scheduledReport) {
            try {
                $template = $scheduledReport->template;

                if (! $template) {
                    $this->warn("Scheduled report #{$scheduledReport->id} has no template.");
                    continue;
                }

                $filters = array_merge(
                    [
                        'entity' => $template->entity,
                        'period' => $template->period,
                        'columns' => $template->columns_json ?? [],
                    ],
                    $template->filters_json ?? []
                );

                $report = $reportBuilder->build($filters);

                $fileName = 'scheduled_report_' . $scheduledReport->id . '_' . now()->format('Ymd_His') . '.pdf';
                $relativePath = 'reports/' . $fileName;
                $fullPath = storage_path('app/' . $relativePath);

                Pdf::view('admin.reports.pdf', [
                        'report' => $report,
                        'filters' => $filters,
                    ])
                    ->format('a4')
                    ->landscape()
                    ->save($fullPath);

                if (! empty($scheduledReport->email)) {
                    Mail::to($scheduledReport->email)->send(
                        new ScheduledReportReadyMail(
                            $template->name,
                            $scheduledReport->frequency,
                            now()->format('Y-m-d h:i A'),
                            $fullPath
                        )
                    );
                }

                ReportExport::create([
                    'scheduled_report_id' => $scheduledReport->id,
                    'report_template_id' => $template->id,
                    'user_id' => $scheduledReport->created_by,
                    'format' => 'pdf',
                    'file_path' => $relativePath,
                    'status' => 'completed',
                    'generated_at' => now(),
                ]);

                $scheduledReport->update([
                    'last_run_at' => now(),
                    'next_run_at' => $this->calculateNextRun($scheduledReport),
                ]);

                $this->info("Scheduled report #{$scheduledReport->id} generated successfully.");
            } catch (\Throwable $e) {
                ReportExport::create([
                    'scheduled_report_id' => $scheduledReport->id,
                    'report_template_id' => $scheduledReport->report_template_id,
                    'user_id' => $scheduledReport->created_by,
                    'format' => 'pdf',
                    'file_path' => null,
                    'status' => 'failed',
                    'generated_at' => now(),
                ]);

                $this->error("Failed scheduled report #{$scheduledReport->id}: " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }

    protected function calculateNextRun(ScheduledReport $scheduledReport): Carbon
    {
        $runTime = $scheduledReport->run_time ?: '09:00:00';
        $startDate = $scheduledReport->start_date
            ? Carbon::parse($scheduledReport->start_date->format('Y-m-d') . ' ' . $runTime)
            : Carbon::today()->setTimeFromTimeString($runTime);

        if ($scheduledReport->frequency === 'daily') {
            $next = Carbon::tomorrow()->setTimeFromTimeString($runTime);

            if ($next->lessThanOrEqualTo(now())) {
                $next = $next->addDay();
            }

            return $next;
        }

        if ($scheduledReport->frequency === 'weekly') {
            $allowedDays = collect($scheduledReport->days_of_week ?? [])
                ->map(fn ($day) => strtolower($day))
                ->values();

            $candidate = now()->copy()->addDay()->setTimeFromTimeString($runTime);

            for ($i = 0; $i < 14; $i++) {
                $dayName = strtolower($candidate->format('l'));

                if ($allowedDays->contains($dayName)) {
                    return $candidate;
                }

                $candidate->addDay()->setTimeFromTimeString($runTime);
            }

            return now()->addWeek()->setTimeFromTimeString($runTime);
        }

        if ($scheduledReport->frequency === 'monthly') {
            $day = (int) ($scheduledReport->day_of_month ?: $startDate->day);

            $candidate = now()->copy()->addMonthNoOverflow()->startOfMonth()->setTimeFromTimeString($runTime);
            $candidate->day(min($day, $candidate->copy()->endOfMonth()->day));

            return $candidate;
        }

        if ($scheduledReport->frequency === 'yearly') {
            $month = (int) ($scheduledReport->month_of_year ?: $startDate->month);
            $day = (int) ($scheduledReport->day_of_month ?: $startDate->day);

            $candidate = now()->copy()->addYear()->startOfYear()->setTimeFromTimeString($runTime);
            $candidate->month($month);
            $candidate->day(min($day, $candidate->copy()->endOfMonth()->day));

            return $candidate;
        }

        return now()->addDay()->setTimeFromTimeString($runTime);
    }
}