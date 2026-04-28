<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Frontend\SubmitProjectStep1Request;
use App\Http\Requests\Frontend\SubmitProjectFinalRequest;
use App\Notifications\ProjectSubmittedNotification;
use App\Notifications\ProjectPendingNotification;
use App\Models\ProjectCategory;

class ProjectSubmissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STEP 1
    |--------------------------------------------------------------------------
    */

    public function step1()
    {
        $this->ensureGuestOrStudent();
        return view('frontend.submissions.step1');
    }

    public function postStep1(SubmitProjectStep1Request $request)
    {
        $this->ensureGuestOrStudent();

        $data = $request->validated();

        session()->put('project_data', array_merge(
            session()->get('project_data', []),
            [
                'project_title' => $data['project_title'],
                'abstract' => $data['abstract'],
                'discipline' => $data['discipline'],
                'project_type' => $data['project_type'],
                'problem_statement' => $data['problem_statement'],
                'target_beneficiaries' => $data['target_beneficiaries'],
                'project_nature' => $data['project_nature'],
            ]
        ));

        return redirect()->route('project.submit.step2');
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2
    |--------------------------------------------------------------------------
    */

    public function step2()
    {
        $this->ensureGuestOrStudent();
        return view('frontend.submissions.step2');
    }

    public function postStep2(Request $request)
    {
        $this->ensureGuestOrStudent();

        $step2 = $request->validate([
            'student_name' => 'required|string|max:150',
            'academic_level' => 'required|string|max:100',
            'supervisor_name' => 'required|string|max:150',
            'supervisor_title' => 'required|string|max:150',
            'university_name' => 'required|string|max:150',
            'college_name' => 'required|string|max:150',
            'department' => 'required|string|max:150',
            'governorate' => 'required|string|max:100',
        ]);

        session()->put('project_data', array_merge(
            session()->get('project_data', []),
            $step2
        ));

        return redirect()->route('project.submit.step3');
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3
    |--------------------------------------------------------------------------
    */

    public function step3()
    {
        $this->ensureGuestOrStudent();
        return view('frontend.submissions.step3');
    }

    public function postStep3(Request $request)
    {
        $this->ensureGuestOrStudent();

        $step3 = $request->validate([
            'is_feasible' => 'required|string|max:50',
            'local_implementation' => 'required|string|max:50',
            'expected_impact' => 'required|string',
            'community_benefit' => 'required|string',
            'needs_funding' => 'required|string|max:10',
            'requested_amount' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1|max:60',
            'support_type' => 'required|string|max:100',
            'budget_breakdown' => 'required|string',
            'milestone_1' => 'required|string|max:255',
            'milestone_1_month' => 'required|integer|min:1|max:60',
            'milestone_2' => 'required|string|max:255',
            'milestone_2_month' => 'required|integer|min:1|max:60',
            'milestone_3' => 'required|string|max:255',
            'milestone_3_month' => 'required|integer|min:1|max:60',
        ]);

        session()->put('project_data', array_merge(
            session()->get('project_data', []),
            $step3
        ));

        return redirect()->route('project.submit.step4');
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 4
    |--------------------------------------------------------------------------
    */

    public function step4()
    {
        $this->ensureGuestOrStudent();

        if (auth('web')->check()) {
            return redirect()->route('project.submit.confirm');
        }

        return view('frontend.submissions.step4');
    }

    public function postStep4(Request $request)
    {
        $this->ensureGuestOrStudent();

        if (auth('web')->check()) {
            session()->put('user_data', [
                'email' => auth('web')->user()->email,
                'data_confirmation' => $request->boolean('data_confirmation', true),
                'terms_agreement' => $request->boolean('terms_agreement', true),
            ]);

            return redirect()->route('project.submit.confirm');
        }

        $userData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'data_confirmation' => 'accepted',
            'terms_agreement' => 'accepted',
        ]);

        session()->put('user_data', [
            'email' => $userData['email'],
            'password' => $userData['password'],
            'data_confirmation' => true,
            'terms_agreement' => true,
        ]);

        return redirect()->route('project.submit.confirm');
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRM
    |--------------------------------------------------------------------------
    */

    public function confirm()
    {
        $this->ensureGuestOrStudent();

        $projectData = session()->get('project_data');

        if (!$projectData) {
            return redirect()
                ->route('project.submit.step1')
                ->with('error', 'Session expired.');
        }

        $userData = session()->get('user_data');

        return view('frontend.submissions.confirm', compact('projectData', 'userData'));
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL = START TECHNICAL SCAN
    |--------------------------------------------------------------------------
    */

public function submitFinal(SubmitProjectFinalRequest $request)
{
    $this->ensureGuestOrStudent();

    $projectData = session()->get('project_data');

    if (!$projectData) {
        return redirect()
            ->route('project.submit.step1')
            ->with('error', 'انتهت الجلسة، يرجى إعادة تعبئة بيانات المشروع.');
    }

    $userData = null;

    if (!auth('web')->check()) {
        $userData = session()->get('user_data');

        if (!$userData) {
            return redirect()
                ->route('project.submit.step4')
                ->with('error', 'بيانات الحساب غير موجودة، يرجى إكمال الخطوة الرابعة.');
        }
    }

    try {
        DB::beginTransaction();

        // 1) Create student account if guest
        if (!auth('web')->check()) {
            $username = strstr($userData['email'], '@', true);

            if (User::where('username', $username)->exists()) {
                $username .= rand(10, 99);
            }

            $user = User::create([
                'name' => $projectData['student_name'] ?? $projectData['project_title'] ?? 'Student',
                'username' => $username,
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'role' => 'Student',
                'status' => 'active',
            ]);

            Auth::guard('web')->login($user);
        }

        $student = auth('web')->user();

        if (!$student) {
            throw new \Exception('تعذر تحديد حساب الطالب بعد تسجيل الدخول.');
        }

        // 2) Save project in main platform first
        $project = Project::create($this->buildProjectPayload($projectData, $student->id));

        // 3) Local safe bypass for development only
        if ($this->shouldBypassScannerLocally()) {
            $bypassMode = env('SCANNER_BYPASS_MODE', 'success');

            if ($bypassMode === 'success') {
                $fakeScannerProjectId = env('SCANNER_FAKE_PROJECT_ID', 'LOCAL-SCAN-' . $project->project_id);
                $fakeToken = env('SCANNER_FAKE_TOKEN', 'local-token-' . $project->project_id);

                $this->appendProjectHistory($project, [
                    'action' => 'scanner_bypassed_locally',
                    'message' => 'Scanner bypassed locally for development testing.',
                    'scanner_project_id' => $fakeScannerProjectId,
                    'at' => now()->toDateTimeString(),
                ]);

                $project->update([
                    'scanner_project_id' => $fakeScannerProjectId,
                    'scanner_status' => 'pending',
                    'status' => 'scan_requested',
                ]);

                DB::commit();

                $this->notifyManagersProjectSubmitted($project);

                if ($student) {
                    $student->notify(new ProjectPendingNotification($project));
                }

                session()->forget(['project_data', 'user_data']);

                return redirect('/dashboard/academic')->with(
                    'success',
                    'تم حفظ المشروع بنجاح في وضع التطوير المحلي، وتم تجاوز منصة الفحص مؤقتًا للاختبار.'
                );
            }

            if ($bypassMode === 'fail') {
                $this->appendProjectHistory($project, [
                    'action' => 'scanner_bypass_failed_locally',
                    'message' => 'Local development mode simulated scanner failure.',
                    'at' => now()->toDateTimeString(),
                ]);

                $project->update([
                    'scanner_status' => 'failed',
                    'status' => 'scan_requested',
                ]);

                DB::commit();

                $this->notifyManagersProjectSubmitted($project);

                session()->forget(['project_data', 'user_data']);

                return redirect('/dashboard/academic')->with(
                    'warning',
                    'تم حفظ المشروع بنجاح، وتمت محاكاة تعطل منصة الفحص محليًا لأغراض الاختبار.'
                );
            }
        }

        // 4) Real scanner integration
        try {
            $scannerRequestPayload = [
                'name' => $project->name,
                'platform_project_id' => $project->project_id,
                'student_id' => $student->id,
                'student_name' => $projectData['student_name'] ?? $student->name,
                'student_email' => $student->email,
                'callback_url' => env('SCANNER_CALLBACK_URL'),
            ];

            $response = Http::connectTimeout(5)
                ->timeout(15)
                ->withHeaders([
                    'X-INTEGRATION-SECRET' => env('SCANNER_INTEGRATION_SECRET'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post(env('SCANNER_INTEGRATION_URL'), $scannerRequestPayload);

            if (!$response->successful()) {
                throw new \Exception('Scanner HTTP request failed with status ' . $response->status());
            }

            $payload = $response->json();
            $scannerData = $payload['data'] ?? [];

            if (!($payload['success'] ?? false)) {
                throw new \Exception($payload['message'] ?? 'Scanner integration failed.');
            }

            if (empty($scannerData['scanner_project_id']) || empty($scannerData['token'])) {
                throw new \Exception('Scanner response missing scanner_project_id or token.');
            }

            $this->appendProjectHistory($project, [
                'action' => 'scanner_request_created',
                'message' => 'Technical scan request created successfully.',
                'at' => now()->toDateTimeString(),
            ]);

            $project->update([
                'scanner_project_id' => $scannerData['scanner_project_id'],
                'scanner_status' => 'pending',
                'status' => 'scan_requested',
            ]);

            DB::commit();

            $this->notifyManagersProjectSubmitted($project);

            if ($student) {
                $student->notify(new ProjectPendingNotification($project));
            }

            session()->forget(['project_data', 'user_data']);

            $scanUrl = rtrim(env('SCANNER_PUBLIC_BASE_URL'), '/') . '/?project_token=' . urlencode($scannerData['token']);

            return redirect()->away($scanUrl);

        } catch (\Throwable $scannerException) {
            Log::warning('Scanner platform unavailable during project submission', [
                'project_id' => $project->project_id,
                'student_id' => $student->id,
                'message' => $scannerException->getMessage(),
            ]);

            $this->appendProjectHistory($project, [
                'action' => 'scanner_unavailable',
                'message' => 'Scanner platform unavailable at submission time. Project saved for later follow-up.',
                'error' => $scannerException->getMessage(),
                'at' => now()->toDateTimeString(),
            ]);

            $project->update([
                'scanner_status' => 'failed',
                'status' => 'scan_requested',
            ]);

            DB::commit();

            $this->notifyManagersProjectSubmitted($project);

            session()->forget(['project_data', 'user_data']);

            return redirect('/dashboard/academic')->with(
                'warning',
                'تم حفظ معلومات مشروعك بنجاح، لكن منصة الفحص في وضع الصيانة حاليًا. سيتم التواصل معك قريبًا بعد استئناف الخدمة.'
            );
        }

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('submitFinal failed', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()
            ->route('project.submit.confirm')
            ->with('error', 'تعذر إكمال إرسال المشروع الآن. يرجى المحاولة مرة أخرى. سبب الخطأ: ' . $e->getMessage());
    }
}

private function shouldBypassScannerLocally(): bool
{
    return app()->environment('local') && filter_var(env('SCANNER_BYPASS_LOCAL', false), FILTER_VALIDATE_BOOL);
}

    /*
    |--------------------------------------------------------------------------
    | RESUME
    |--------------------------------------------------------------------------
    */

    public function resume()
    {
        $this->ensureGuestOrStudent();

        if (session()->has('user_data')) {
            return redirect()->route('project.submit.confirm');
        }

        if (session()->has('project_data')) {
            return redirect()->route('project.submit.step4');
        }

        return redirect()->route('project.submit.step1');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    // private function buildProjectPayload(array $projectData, int $studentId): array
    // {
    //     return [
    //         // Step 1
    //         'name' => $projectData['project_title'] ?? null,
    //         'description' => $projectData['abstract'] ?? null,
    //         'category' => $projectData['discipline'] ?? null,
    //         'project_type' => $projectData['project_type'] ?? null,
    //         'project_nature' => $projectData['project_nature'] ?? null,
    //         'problem_statement' => $projectData['problem_statement'] ?? null,
    //         'target_beneficiaries' => $projectData['target_beneficiaries'] ?? null,

    //         // Step 2
    //         'student_name' => $projectData['student_name'] ?? null,
    //         'academic_level' => $projectData['academic_level'] ?? null,
    //         'supervisor_name' => $projectData['supervisor_name'] ?? null,
    //         'supervisor_title' => $projectData['supervisor_title'] ?? null,
    //         'university_name' => $projectData['university_name'] ?? null,
    //         'college_name' => $projectData['college_name'] ?? null,
    //         'department' => $projectData['department'] ?? null,
    //         'governorate' => $projectData['governorate'] ?? null,

    //         // Step 3
    //         'is_feasible' => $projectData['is_feasible'] ?? null,
    //         'local_implementation' => $projectData['local_implementation'] ?? null,
    //         'expected_impact' => $projectData['expected_impact'] ?? null,
    //         'community_benefit' => $projectData['community_benefit'] ?? null,
    //         'needs_funding' => $projectData['needs_funding'] ?? null,
    //         'budget' => $projectData['requested_amount'] ?? null,
    //         'duration_months' => $projectData['duration_months'] ?? null,
    //         'support_type' => $projectData['support_type'] ?? null,
    //         'budget_breakdown' => $projectData['budget_breakdown'] ?? null,

    //         // Milestones
    //         'milestone_1' => $projectData['milestone_1'] ?? null,
    //         'milestone_1_month' => $projectData['milestone_1_month'] ?? null,
    //         'milestone_2' => $projectData['milestone_2'] ?? null,
    //         'milestone_2_month' => $projectData['milestone_2_month'] ?? null,
    //         'milestone_3' => $projectData['milestone_3'] ?? null,
    //         'milestone_3_month' => $projectData['milestone_3_month'] ?? null,

    //         // System
    //         'student_id' => $studentId,
    //         'status' => 'scan_requested',
    //         'scanner_status' => 'pending',
    //         'scan_score' => null,
    //         'scan_report' => null,
    //         'scanned_at' => null,
    //     ];
    // }

    private function appendProjectHistory(Project $project, array $entry): void
    {
        $history = $project->status_history;

        if (!is_array($history)) {
            $history = [];
        }

        $history[] = $entry;

        $project->update([
            'status_history' => $history,
        ]);
    }

    private function notifyManagersProjectSubmitted(Project $project): void
    {
        $managers = User::where('role', 'Manager')
            ->where('status', 'active')
            ->get();

        if ($managers->count()) {
            Notification::send($managers, new ProjectSubmittedNotification($project));
        }
    }

    private function ensureGuestOrStudent()
    {
        if (auth('web')->check() && auth('web')->user()->role !== 'Student') {
            abort(403, 'Only students can submit projects.');
        }
    }
    private function buildProjectPayload(array $projectData, int $studentId): array
{
    return [
        // Step 1
        'name' => $projectData['project_title'] ?? null,
        'description' => $projectData['abstract'] ?? null,
        'category' => $projectData['discipline'] ?? null,
        'project_category_id' => $this->resolveProjectCategoryId($projectData['discipline'] ?? null),
        'project_type' => $projectData['project_type'] ?? null,
        'project_nature' => $projectData['project_nature'] ?? null,
        'problem_statement' => $projectData['problem_statement'] ?? null,
        'target_beneficiaries' => $projectData['target_beneficiaries'] ?? null,

        // Step 2
        'student_name' => $projectData['student_name'] ?? null,
        'academic_level' => $projectData['academic_level'] ?? null,
        'supervisor_name' => $projectData['supervisor_name'] ?? null,
        'supervisor_title' => $projectData['supervisor_title'] ?? null,
        'university_name' => $projectData['university_name'] ?? null,
        'college_name' => $projectData['college_name'] ?? null,
        'department' => $projectData['department'] ?? null,
        'governorate' => $projectData['governorate'] ?? null,

        // Step 3
        'is_feasible' => $projectData['is_feasible'] ?? null,
        'local_implementation' => $projectData['local_implementation'] ?? null,
        'expected_impact' => $projectData['expected_impact'] ?? null,
        'community_benefit' => $projectData['community_benefit'] ?? null,
        'needs_funding' => $projectData['needs_funding'] ?? null,
        'budget' => $projectData['requested_amount'] ?? null,
        'duration_months' => $projectData['duration_months'] ?? null,
        'support_type' => $projectData['support_type'] ?? null,
        'budget_breakdown' => $projectData['budget_breakdown'] ?? null,

        // Milestones
        'milestone_1' => $projectData['milestone_1'] ?? null,
        'milestone_1_month' => $projectData['milestone_1_month'] ?? null,
        'milestone_2' => $projectData['milestone_2'] ?? null,
        'milestone_2_month' => $projectData['milestone_2_month'] ?? null,
        'milestone_3' => $projectData['milestone_3'] ?? null,
        'milestone_3_month' => $projectData['milestone_3_month'] ?? null,

        // System
        'student_id' => $studentId,
        'status' => 'scan_requested',
        'scanner_status' => 'pending',
        'scan_score' => null,
        'scan_report' => null,
        'scanned_at' => null,
    ];
}
private function resolveProjectCategoryId(?string $discipline): ?int
{
    $normalized = mb_strtolower(trim((string) $discipline));

    $map = [
        'information technology' => 'information-technology',
        'software engineering' => 'software-engineering',
        'artificial intelligence & machine learning' => 'artificial-intelligence-machine-learning',
        'medical / health' => 'medical-health',
        'electrical engineering' => 'electrical-engineering',
        'renewable energy' => 'renewable-energy',
        'agriculture' => 'agriculture',
        'education' => 'education',
        'business / management' => 'business-management',
        'other' => 'other',
    ];

    $slug = $map[$normalized] ?? 'other';

    return ProjectCategory::where('slug', $slug)->value('id');
}
}