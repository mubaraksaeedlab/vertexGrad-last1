<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectInvestment;
use Illuminate\Http\Request;
use App\Notifications\FundingRequestApprovedNotification;
use App\Notifications\FundingRequestRejectedNotification;
use App\Services\AuditLogService;

class InvestmentRequestController extends Controller
{
    public function index(Request $request)
    {
        $allowedStatuses = ['interested', 'requested', 'approved', 'rejected'];

        $query = ProjectInvestment::query()
            ->with([
                'investor.investor',
                'project',
            ]);

        if ($request->filled('status') && in_array($request->status, $allowedStatuses)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('investor', function ($investorQ) use ($search) {
                    $investorQ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })->orWhereHas('project', function ($projectQ) use ($search) {
                    $projectQ->where('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                })->orWhere('message', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'latest');
        if ($sortBy === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $perPage = (int) $request->get('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $investmentRequests = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total'      => ProjectInvestment::count(),
            'interested' => ProjectInvestment::where('status', 'interested')->count(),
            'requested'  => ProjectInvestment::where('status', 'requested')->count(),
            'approved'   => ProjectInvestment::where('status', 'approved')->count(),
            'rejected'   => ProjectInvestment::where('status', 'rejected')->count(),
            'amount'     => ProjectInvestment::where('status', 'approved')->sum('amount'),
        ];

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed investment requests index',
            category: 'investment_request',
            properties: [
                'filter_status' => $request->status,
                'search' => $request->search,
                'sort_by' => $sortBy,
                'per_page' => $perPage,
                'results_count' => $investmentRequests->count(),
                'total_requests' => $stats['total'],
            ]
        );

        return view('admin.investment-requests.index', compact('investmentRequests', 'stats'));
    }

    public function updateStatus(Request $request, ProjectInvestment $investmentRequest)
    {
        $data = $request->validate([
            'status' => 'required|in:interested,requested,approved,rejected',
        ]);

        $investmentRequest->load(['investor', 'project']);

        $oldValues = $this->auditInvestmentPayload($investmentRequest);

        $oldStatus = $investmentRequest->status;
        $newStatus = $data['status'];

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Request status is already up to date.');
        }

        $investmentRequest->update([
            'status' => $newStatus,
        ]);

        $investmentRequest->refresh();
        $investmentRequest->load(['investor', 'project']);

        AuditLogService::log(
            event: 'status_updated',
            description: 'Updated investment request status from ' . $oldStatus . ' to ' . $newStatus,
            category: 'investment_request',
            subject: $investmentRequest->project,
            oldValues: $oldValues,
            newValues: $this->auditInvestmentPayload($investmentRequest),
            properties: [
                'investment_request_id' => $investmentRequest->id,
                'project_id' => $investmentRequest->project_id,
                'project_name' => $investmentRequest->project->name ?? null,
                'investor_id' => $investmentRequest->investor_id,
                'investor_name' => $investmentRequest->investor->name ?? null,
                'amount' => $investmentRequest->amount,
                'message' => $investmentRequest->message,
            ]
        );

        if ($newStatus === 'approved' && $investmentRequest->investor && $investmentRequest->project) {
            $investmentRequest->investor->notify(
                new FundingRequestApprovedNotification($investmentRequest->project)
            );

            AuditLogService::log(
                event: 'notification_sent',
                description: 'Sent funding approval notification to investor for project: ' . ($investmentRequest->project->name ?? 'Unknown Project'),
                category: 'investment_request_notification',
                subject: $investmentRequest->project,
                properties: [
                    'investment_request_id' => $investmentRequest->id,
                    'investor_id' => $investmentRequest->investor_id,
                    'investor_name' => $investmentRequest->investor->name ?? null,
                    'notification_type' => 'FundingRequestApprovedNotification',
                ]
            );
        }

        if ($newStatus === 'rejected' && $investmentRequest->investor && $investmentRequest->project) {
            $investmentRequest->investor->notify(
                new FundingRequestRejectedNotification($investmentRequest->project)
            );

            AuditLogService::log(
                event: 'notification_sent',
                description: 'Sent funding rejection notification to investor for project: ' . ($investmentRequest->project->name ?? 'Unknown Project'),
                category: 'investment_request_notification',
                subject: $investmentRequest->project,
                properties: [
                    'investment_request_id' => $investmentRequest->id,
                    'investor_id' => $investmentRequest->investor_id,
                    'investor_name' => $investmentRequest->investor->name ?? null,
                    'notification_type' => 'FundingRequestRejectedNotification',
                ]
            );
        }

        return back()->with('success', 'Investment request status updated successfully.');
    }

    protected function auditInvestmentPayload(ProjectInvestment $investmentRequest): array
    {
        return [
            'id' => $investmentRequest->id,
            'project_id' => $investmentRequest->project_id,
            'investor_id' => $investmentRequest->investor_id,
            'status' => $investmentRequest->status,
            'amount' => $investmentRequest->amount,
            'message' => $investmentRequest->message,
            'created_at' => $investmentRequest->created_at,
            'updated_at' => $investmentRequest->updated_at,
        ];
    }
}