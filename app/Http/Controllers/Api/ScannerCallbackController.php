<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScannerCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // 🔒 حماية: التأكد من سر التكامل
        if ($request->header('X-SCANNER-SECRET') !== env('SCANNER_API_SECRET')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            Log::info('SCANNER CALLBACK RECEIVED', [
                'payload' => $request->all(),
            ]);

            $data = $request->validate([
                'event' => 'required|string|max:100',
                'version' => 'nullable|string|max:20',

                'project.platform_project_id' => 'required|integer',
                'project.scanner_project_id' => 'required|integer',
                'project.scanner_token' => 'nullable|string|max:255',
                'project.name' => 'nullable|string|max:255',
                'project.student_name' => 'nullable|string|max:255',
                'project.student_email' => 'nullable|email|max:255',
                'project.language' => 'nullable|string|max:100',

                'scan.status' => 'required|string|max:50',
                'scan.score' => 'nullable|numeric',
                'scan.grade' => 'nullable|string|max:20',
                'scan.risk_level' => 'nullable|string|max:50',
                'scan.started_at' => 'nullable|string',
                'scan.completed_at' => 'nullable|string',

                'summary.total_files' => 'nullable|integer',
                'summary.issues_total' => 'nullable|integer',
                'summary.critical' => 'nullable|integer',
                'summary.high' => 'nullable|integer',
                'summary.medium' => 'nullable|integer',
                'summary.low' => 'nullable|integer',

                'highlights' => 'nullable|array',
                'highlights.*' => 'nullable|string',

                'recommendations' => 'nullable|array',
                'recommendations.*' => 'nullable|string',
            ]);

            $platformProjectId = data_get($data, 'project.platform_project_id');

            $project = Project::where('project_id', $platformProjectId)->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Platform project not found'
                ], 404);
            }

            $scannerStatus = data_get($data, 'scan.status', 'unknown');
            $score = data_get($data, 'scan.score');
            $completedAt = data_get($data, 'scan.completed_at');

            $project->update([
                'scanner_project_id' => data_get($data, 'project.scanner_project_id'),
                'scanner_status' => $scannerStatus,
                'scan_score' => $score,
                'scan_report' => $data,
                'scanned_at' => $completedAt ?: now(),
                'status' => $scannerStatus === 'completed'
                    ? 'awaiting_manual_review'
                    : ($scannerStatus === 'failed' ? 'scan_failed' : 'scan_requested'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Scanner callback processed successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('SCANNER CALLBACK FAILED', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Callback processing failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}