<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AuditLogsExport;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LaravelPdf\Facades\Pdf;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = $this->filteredQuery($request)
            ->latestFirst()
            ->paginate(20)
            ->withQueryString();

        $analytics = $this->analytics();

        return view('admin.audit-logs.index', compact('logs', 'analytics'));
    }

    public function exportExcel(Request $request)
    {
        $logs = $this->filteredQuery($request)
            ->latestFirst()
            ->get();

        $fileName = 'vertexgrad_audit_logs_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(
            new AuditLogsExport(
                logs: $logs,
                filters: $request->only(['search', 'user', 'event', 'category', 'from', 'to']),
                exportedAt: now()
            ),
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $logs = $this->filteredQuery($request)
            ->latestFirst()
            ->get();

        $fileName = 'vertexgrad_audit_logs_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        return Pdf::view('admin.audit-logs.pdf', [
                'logs' => $logs,
                'filters' => $request->only(['search', 'user', 'event', 'category', 'from', 'to']),
                'generatedAt' => now(),
            ])
            ->format('A4')
            ->landscape()
            ->name($fileName);
    }

    protected function filteredQuery(Request $request)
    {
        return AuditLog::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhere('user_name', 'like', "%{$search}%")
                        ->orWhere('user_type', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('event', 'like', "%{$search}%")
                        ->orWhere('subject_title', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('user'), function ($query) use ($request) {
                $user = trim($request->user);

                $query->where(function ($q) use ($user) {
                    $q->where('user_name', 'like', "%{$user}%")
                        ->orWhere('user_type', 'like', "%{$user}%");
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->filled('event'), function ($query) use ($request) {
                $query->where('event', $request->event);
            })
            ->when($request->filled('from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to);
            });
    }

    protected function analytics(): array
    {
        return [
            'total'   => AuditLog::count(),
            'today'   => AuditLog::whereDate('created_at', today())->count(),
            'created' => AuditLog::where('event', 'created')->count(),
            'updated' => AuditLog::where('event', 'updated')->count(),
            'deleted' => AuditLog::where('event', 'deleted')->count(),
        ];
    }
}