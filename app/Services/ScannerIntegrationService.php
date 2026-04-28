<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Http;

class ScannerIntegrationService
{
    public function createScannerProject(Project $project): array
    {
        $response = Http::withHeaders([
            'X-INTEGRATION-SECRET' => env('SCANNER_INTEGRATION_SECRET'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(env('SCANNER_INTEGRATION_URL'), [
            'name' => $project->name,
            'platform_project_id' => $project->project_id,
            'student_id' => $project->student_id,
            'student_name' => optional($project->student)->name,
            'student_email' => optional($project->student)->email,
            'callback_url' => null,
        ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'Failed to connect to scanner platform',
                'response' => $response->json(),
            ];
        }

        $data = $response->json();

        if (! ($data['success'] ?? false)) {
            return [
                'success' => false,
                'message' => $data['message'] ?? 'Scanner integration failed',
                'response' => $data,
            ];
        }

        return [
            'success' => true,
            'data' => $data['data'] ?? [],
        ];
    }
}
