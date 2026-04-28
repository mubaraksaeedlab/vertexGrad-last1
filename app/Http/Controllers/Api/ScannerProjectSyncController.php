<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ScannerProjectSyncController extends Controller
{
    public function store(Request $request)
    {
        if ($request->header('X-SCANNER-SECRET') !== env('SCANNER_API_SECRET')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'scanner_project_id' => 'required|integer',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'category'           => 'nullable|string|max:255',
            'scanner_status'     => 'nullable|string|max:100',
            'scan_score'         => 'nullable|numeric',
            'scan_report'        => 'nullable|string',
            'scanned_at'         => 'nullable|date',
            'student_id'         => 'nullable|integer',
        ]);

        $project = Project::updateOrCreate(
            [
                'scanner_project_id' => $request->scanner_project_id,
            ],
            [
                'name'           => $request->name,
                'description'    => $request->description,
                'category'       => $request->category,
                'status'         => 'Pending',
                'scanner_status' => $request->scanner_status,
                'scan_score'     => $request->scan_score,
                'scan_report'    => $request->scan_report,
                'scanned_at'     => $request->scanned_at,
                'student_id'     => $request->student_id,
            ]
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Project synced successfully',
            'project_id' => $project->project_id,
        ]);
    }
}
