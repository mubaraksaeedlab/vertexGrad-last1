<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\InvestorController;
use App\Http\Controllers\Admin\ProjectTaskController;

use App\Http\Controllers\Supervisor\NotificationController as SupervisorNotificationController;

use App\Http\Controllers\Manager\UserApproveController;
use App\Http\Controllers\Manager\UserController;

use App\Http\Controllers\Admin\LanguageController as BackendLanguageController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\InvestmentRequestController;
use App\Http\Controllers\Admin\InvestorReportController;
use App\Http\Controllers\Admin\InvestorSingleReportController;
use App\Http\Controllers\Admin\InvestorCommunicationController;
use App\Http\Controllers\Admin\InvestorMeetingController;
use App\Http\Controllers\Admin\InvestorContractController;
use App\Http\Controllers\Admin\InvestorEmailController;
use App\Http\Controllers\Admin\InvestorPreferenceController;
use App\Http\Controllers\Admin\InvestorReminderController;
use App\Http\Controllers\Admin\InvestorCalendarController;
use App\Http\Controllers\Admin\ManagerProjectDecisionController;
use App\Http\Controllers\Admin\PermissionManagementController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AuthPolicyManagementController;
use App\Http\Controllers\Admin\AuthRolePolicyController;

use App\Http\Controllers\Report\PlatformReportController;

use App\Http\Controllers\Supervisor\SupervisorDashboardController;
use App\Http\Controllers\Supervisor\SupervisorProjectController;
use App\Http\Controllers\Supervisor\SupervisorProfileController;
use App\Http\Controllers\Supervisor\ContactMessageController as SupervisorContactMessageController;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Admin + Manager + Supervisor
| Locale is separated from frontend using backend.locale middleware
|
*/

Route::middleware(['web', 'backend.locale'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Backend Language Switch
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/language/{locale}', [BackendLanguageController::class, 'switch'])
        ->where('locale', 'en|ar')
        ->name('admin.language.switch');

    /*
    |--------------------------------------------------------------------------
    | Admin Authentication
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });

    /*
    |--------------------------------------------------------------------------
    | Permission Management + Contact Messages (Manager only)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:admin', 'role:Manager'])->prefix('admin')->name('admin.')->group(function () {
            Route::get('/permissions', [PermissionManagementController::class, 'index'])->name('permissions.index');
            Route::get('/permissions/{user}', [PermissionManagementController::class, 'show'])->name('permissions.show');
            Route::post('/permissions/{user}/sync', [PermissionManagementController::class, 'sync'])->name('permissions.sync');

            Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
            Route::get('/contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
            Route::patch('/contact-messages/{contactMessage}/status', [ContactMessageController::class, 'updateStatus'])->name('contact-messages.update-status');
            Route::post('/contact-messages/{contactMessage}/reply', [ContactMessageController::class, 'sendReply'])->name('contact-messages.reply');
            Route::post('/contact-messages/{contactMessage}/notes', [ContactMessageController::class, 'storeNote'])->name('contact-messages.notes.store');


            Route::get('/auth-policies', [AuthPolicyManagementController::class, 'index'])->name('auth-policies.index');
Route::get('/auth-policies/{user}', [AuthPolicyManagementController::class, 'show'])->name('auth-policies.show');
Route::post('/auth-policies/{user}', [AuthPolicyManagementController::class, 'update'])->name('auth-policies.update');
Route::get('/auth-role-policies', [AuthRolePolicyController::class, 'index'])->name('auth-role-policies.index');
Route::get('/auth-role-policies/{rolePolicy}', [AuthRolePolicyController::class, 'show'])->name('auth-role-policies.show');
Route::post('/auth-role-policies/{rolePolicy}', [AuthRolePolicyController::class, 'update'])->name('auth-role-policies.update');

        });

    /*
    |--------------------------------------------------------------------------
    | Authenticated Admin Area
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */
Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        */
        Route::resource('students', StudentController::class);
        Route::get('students/{id}/status/{status}', [StudentController::class, 'updateStatus'])
            ->name('students.status');

        /*
        |--------------------------------------------------------------------------
        | Investors Notes
        |--------------------------------------------------------------------------
        */
        Route::post('investors/{investor}/notes', [InvestorController::class, 'storeNote'])
            ->name('investors.notes.store');

        Route::delete('investors/{investor}/notes/{note}', [InvestorController::class, 'deleteNote'])
            ->name('investors.notes.delete');

        /*
        |--------------------------------------------------------------------------
        | Investors Files
        |--------------------------------------------------------------------------
        */
        Route::post('investors/{investor}/files', [InvestorController::class, 'uploadFile'])
            ->name('investors.files.upload');

        Route::delete('investors/{investor}/files/{file}', [InvestorController::class, 'deleteFile'])
            ->name('investors.files.delete');

        /*
        |--------------------------------------------------------------------------
        | Investors Import / Export / Archive
        |--------------------------------------------------------------------------
        */
        Route::post('investors/import', [InvestorController::class, 'import'])
            ->name('investors.import');

        Route::get('investors/export/{format?}', [InvestorController::class, 'export'])
            ->name('investors.export');

        Route::post('investors/{investor}/restore', [InvestorController::class, 'restore'])
            ->name('investors.restore');

        Route::delete('investors/{investor}/force-delete', [InvestorController::class, 'forceDelete'])
            ->name('investors.forceDelete');

        /*
        |--------------------------------------------------------------------------
        | Single Investor Report
        |--------------------------------------------------------------------------
        */
        Route::get('investors/{investor}/report', [InvestorSingleReportController::class, 'show'])
            ->name('investors.report');

        Route::get('investors/{investor}/report/export', [InvestorSingleReportController::class, 'export'])
            ->name('investors.report.export');

        /*
        |--------------------------------------------------------------------------
        | Investor Manual Notification
        |--------------------------------------------------------------------------
        */
        Route::get('investors/{investor}/notify', [InvestorCommunicationController::class, 'create'])
            ->name('investors.notify.create');

        Route::post('investors/{investor}/notify', [InvestorCommunicationController::class, 'store'])
            ->name('investors.notify.store');

        /*
        |--------------------------------------------------------------------------
        | Investor Preferences
        |--------------------------------------------------------------------------
        */
        Route::get('investors/{investor}/preferences', [InvestorPreferenceController::class, 'edit'])
            ->name('investors.preferences.edit');

        Route::put('investors/{investor}/preferences', [InvestorPreferenceController::class, 'update'])
            ->name('investors.preferences.update');

        /*
        |--------------------------------------------------------------------------
        | Investor Email
        |--------------------------------------------------------------------------
        */
        Route::get('investors/{investor}/email', [InvestorEmailController::class, 'create'])
            ->name('investors.email.create');

        Route::post('investors/{investor}/email', [InvestorEmailController::class, 'store'])
            ->name('investors.email.store');

        /*
        |--------------------------------------------------------------------------
        | Investor Meetings
        |--------------------------------------------------------------------------
        */
        Route::get('investors/{investor}/meetings', [InvestorMeetingController::class, 'index'])
            ->name('investors.meetings.index');

        Route::get('investors/{investor}/meetings/create', [InvestorMeetingController::class, 'create'])
            ->name('investors.meetings.create');

        Route::post('investors/{investor}/meetings', [InvestorMeetingController::class, 'store'])
            ->name('investors.meetings.store');

        Route::get('investors/{investor}/meetings/{meeting}/edit', [InvestorMeetingController::class, 'edit'])
            ->name('investors.meetings.edit');

        Route::put('investors/{investor}/meetings/{meeting}', [InvestorMeetingController::class, 'update'])
            ->name('investors.meetings.update');

        Route::delete('investors/{investor}/meetings/{meeting}', [InvestorMeetingController::class, 'destroy'])
            ->name('investors.meetings.destroy');

        /*
        |--------------------------------------------------------------------------
        | Investor Contracts
        |--------------------------------------------------------------------------
        */
        Route::get('investors/{investor}/contracts', [InvestorContractController::class, 'index'])
            ->name('investors.contracts.index');

        Route::get('investors/{investor}/contracts/create', [InvestorContractController::class, 'create'])
            ->name('investors.contracts.create');

        Route::post('investors/{investor}/contracts', [InvestorContractController::class, 'store'])
            ->name('investors.contracts.store');

        Route::get('investors/{investor}/contracts/{contract}/edit', [InvestorContractController::class, 'edit'])
            ->name('investors.contracts.edit');

        Route::put('investors/{investor}/contracts/{contract}', [InvestorContractController::class, 'update'])
            ->name('investors.contracts.update');

        Route::delete('investors/{investor}/contracts/{contract}', [InvestorContractController::class, 'destroy'])
            ->name('investors.contracts.destroy');

        /*
        |--------------------------------------------------------------------------
        | Investor Reminders
        |--------------------------------------------------------------------------
        */
        Route::get('investors/{investor}/reminders', [InvestorReminderController::class, 'index'])
            ->name('investors.reminders.index');

        Route::get('investors/{investor}/reminders/create', [InvestorReminderController::class, 'create'])
            ->name('investors.reminders.create');

        Route::post('investors/{investor}/reminders', [InvestorReminderController::class, 'store'])
            ->name('investors.reminders.store');

        Route::get('investors/{investor}/reminders/{reminder}/edit', [InvestorReminderController::class, 'edit'])
            ->name('investors.reminders.edit');

        Route::put('investors/{investor}/reminders/{reminder}', [InvestorReminderController::class, 'update'])
            ->name('investors.reminders.update');

        Route::delete('investors/{investor}/reminders/{reminder}', [InvestorReminderController::class, 'destroy'])
            ->name('investors.reminders.destroy');

        /*
        |--------------------------------------------------------------------------
        | Main Investors Resource
        |--------------------------------------------------------------------------
        */
        Route::resource('investors', InvestorController::class)
            ->parameters(['investors' => 'investor']);

        /*
        |--------------------------------------------------------------------------
        | Investment Requests
        |--------------------------------------------------------------------------
        */
        Route::get('/investment-requests', [InvestmentRequestController::class, 'index'])
            ->name('investment-requests.index');

        Route::patch('/investment-requests/{investmentRequest}/status', [InvestmentRequestController::class, 'updateStatus'])
            ->name('investment-requests.update-status');

        /*
        |--------------------------------------------------------------------------
        | Investor Reports (Global)
        |--------------------------------------------------------------------------
        */
        Route::get('/investor-reports', [InvestorReportController::class, 'index'])
            ->name('investor-reports.index');

        Route::get('/investor-reports/export', [InvestorReportController::class, 'export'])
            ->name('investor-reports.export');

        /*
        |--------------------------------------------------------------------------
        | Investor Calendar Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/investor-calendar', [InvestorCalendarController::class, 'index'])
            ->name('investor-calendar.index');

        /*
        |--------------------------------------------------------------------------
        | Final Decisions
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:Manager'])
            ->prefix('projects/final-decisions')
            ->name('projects.final-decisions.')
            ->group(function () {
                Route::get('/', [ManagerProjectDecisionController::class, 'index'])->name('index');
                Route::get('/{project}', [ManagerProjectDecisionController::class, 'show'])->name('show');
                Route::post('/{project}/store', [ManagerProjectDecisionController::class, 'storeDecision'])->name('store');
            });

        /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        */
        Route::resource('projects', AdminProjectController::class);

        Route::post('projects/{project}/approve', [AdminProjectController::class, 'approve'])
            ->name('projects.approve');

        Route::post('projects/{project}/reject', [AdminProjectController::class, 'reject'])
            ->name('projects.reject');

        Route::post('projects/{project}/funding-requests/{user}/approve', [AdminProjectController::class, 'approveInvestor'])
            ->name('projects.investors.approve');

        Route::post('projects/{project}/funding-requests/{user}/reject', [AdminProjectController::class, 'rejectInvestor'])
            ->name('projects.investors.reject');

        Route::get('projects/{project}/scanner-review', [AdminProjectController::class, 'scannerReview'])
            ->name('projects.scannerReview');

        Route::post('projects/{project}/start-scan', [AdminProjectController::class, 'startScan'])
            ->name('projects.startScan');

        Route::patch('projects/{project}/publish', [AdminProjectController::class, 'publish'])
            ->name('projects.publish');

        /*
        |--------------------------------------------------------------------------
        | Project Tasks
        |--------------------------------------------------------------------------
        */
        Route::prefix('projects/{project}')->group(function () {
            Route::post('tasks', [ProjectTaskController::class, 'store'])->name('projects.tasks.store');
            Route::put('tasks/{task}', [ProjectTaskController::class, 'update'])->name('projects.tasks.update');
            Route::delete('tasks/{task}', [ProjectTaskController::class, 'destroy'])->name('projects.tasks.destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | Admin Notifications
        |--------------------------------------------------------------------------
        */
        Route::get('notifications', [AdminNotificationController::class, 'index'])
            ->name('notifications.index');

        Route::get('notifications/unread-count', [AdminNotificationController::class, 'unreadCount'])
            ->name('notifications.count');
Route::get('notifications/latest', [AdminNotificationController::class, 'latest'])
    ->name('notifications.latest');
        Route::post('notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])
            ->name('notifications.read');

        Route::post('notifications/mark-all-read', [AdminNotificationController::class, 'markAllRead'])
            ->name('notifications.markAllRead');

        /*
        |--------------------------------------------------------------------------
        | Announcements
        |--------------------------------------------------------------------------
        */
        Route::get('announcements/history', [AnnouncementController::class, 'history'])
            ->name('announcements.history')
            ->middleware('permission:manage_announcements');

        Route::resource('announcements', AnnouncementController::class)
            ->middleware('permission:manage_announcements');

        /*
        |--------------------------------------------------------------------------
        | Audit Logs
        |--------------------------------------------------------------------------
        */
        Route::get('audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index')
            ->middleware('permission:view_audit_logs');

        Route::get('audit-logs/export/excel', [AuditLogController::class, 'exportExcel'])
            ->name('audit.export.excel')
            ->middleware('permission:view_audit_logs');

        Route::get('audit-logs/export/pdf', [AuditLogController::class, 'exportPdf'])
            ->name('audit.export.pdf')
            ->middleware('permission:view_audit_logs');

        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */
        Route::get('settings', [SettingController::class, 'index'])
            ->name('settings.index')
            ->middleware('permission:manage_settings');

        Route::post('settings', [SettingController::class, 'update'])
            ->name('settings.update')
            ->middleware('permission:manage_settings');

        /*
        |--------------------------------------------------------------------------
        | Platform Reports
        |--------------------------------------------------------------------------
        */
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('platform', [PlatformReportController::class, 'index'])->name('platform');

            Route::get('investors/excel', [PlatformReportController::class, 'exportInvestorsExcel'])->name('investors.excel');
            Route::get('investors/pdf', [PlatformReportController::class, 'exportInvestorsPdf'])->name('investors.pdf');

            Route::get('students/excel', [PlatformReportController::class, 'exportStudentsExcel'])->name('students.excel');
            Route::get('students/pdf', [PlatformReportController::class, 'exportStudentsPdf'])->name('students.pdf');

            Route::get('projects/excel', [PlatformReportController::class, 'exportProjectsExcel'])->name('projects.excel');
            Route::get('projects/pdf', [PlatformReportController::class, 'exportProjectsPdf'])->name('projects.pdf');
        });

        /*
        |--------------------------------------------------------------------------
        | Advanced Reports
        |--------------------------------------------------------------------------
        */
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/preview', [ReportController::class, 'preview'])->name('reports.preview');
        Route::post('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::post('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');

        Route::get('/reports/templates', [ReportController::class, 'templates'])->name('reports.templates');
        Route::post('/reports/templates/save', [ReportController::class, 'saveTemplate'])->name('reports.templates.save');
        Route::get('/reports/templates/{template}/run', [ReportController::class, 'runTemplate'])->name('reports.templates.run');
        Route::delete('/reports/templates/{template}', [ReportController::class, 'deleteTemplate'])->name('reports.templates.delete');

        Route::get('/reports/scheduled', [ReportController::class, 'scheduled'])->name('reports.scheduled');
        Route::post('/reports/scheduled', [ReportController::class, 'storeScheduled'])->name('reports.scheduled.store');
        Route::patch('/reports/scheduled/{scheduledReport}/toggle', [ReportController::class, 'toggleScheduled'])->name('reports.scheduled.toggle');
        Route::delete('/reports/scheduled/{scheduledReport}', [ReportController::class, 'deleteScheduled'])->name('reports.scheduled.delete');
        Route::post('/reports/scheduled/{scheduledReport}/run-now', [ReportController::class, 'runNow'])->name('reports.scheduled.run-now');

        Route::get('/reports/history', [ReportController::class, 'history'])->name('reports.history');
        Route::get('/reports/history/{reportExport}/download', [ReportController::class, 'downloadExport'])->name('reports.history.download');
        Route::delete('/reports/history/{reportExport}', [ReportController::class, 'deleteExport'])->name('reports.history.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Manager Area
    |--------------------------------------------------------------------------
    */
    Route::prefix('manager')->name('manager.')->middleware(['auth:admin', 'role:Manager'])->group(function () {
        Route::get('/dashboard', [UserApproveController::class, 'dashboard'])->name('dashboard');

        Route::get('/pending-users', [UserApproveController::class, 'pendingUsers'])->name('pending.users');

        Route::post('/approve-direct/{user}', [UserApproveController::class, 'approveDirect'])->name('users.approve-direct');
        Route::post('/reject/{user}', [UserController::class, 'reject'])->name('users.reject');

        Route::resource('users', UserController::class)->except(['index']);
        Route::post('/users/{user}/force-logout', [UserController::class, 'forceLogout'])->name('users.force-logout');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
        Route::post('/calendar/add-event', [CalendarController::class, 'addEvent'])->name('calendar.add-event');
        Route::post('/calendar/delete-events', [CalendarController::class, 'deleteEvents'])->name('calendar.delete-events');

        Route::get('/sync', [ManagerController::class, 'sync'])->name('sync');
        Route::get('/migrate-managers', [ManagerController::class, 'migrateUsersToManagers'])->name('migrate');
        Route::resource('/', ManagerController::class)->parameters(['' => 'manager']);
    });

    /*
    |--------------------------------------------------------------------------
    | Supervisor Area
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/supervisor')
        ->name('supervisor.')
        ->middleware(['auth:admin', 'role:Supervisor'])
        ->group(function () {
            Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');

            Route::get('/projects', [SupervisorProjectController::class, 'index'])->name('projects.index');
            Route::get('/projects/pending', [SupervisorProjectController::class, 'pending'])->name('projects.pending');
            Route::get('/projects/approved', [SupervisorProjectController::class, 'approved'])->name('projects.approved');
            Route::get('/projects/revisions', [SupervisorProjectController::class, 'revisions'])->name('projects.revisions');
            Route::get('/projects/{project}', [SupervisorProjectController::class, 'show'])->name('projects.show');

            Route::get('/profile', [SupervisorProfileController::class, 'index'])->name('profile.index');
            Route::post('/profile', [SupervisorProfileController::class, 'update'])->name('profile.update');

            Route::get('/contact-messages', [SupervisorContactMessageController::class, 'index'])->name('contact-messages.index');
            Route::get('/contact-messages/{contactMessage}', [SupervisorContactMessageController::class, 'show'])->name('contact-messages.show');
            Route::patch('/contact-messages/{contactMessage}/status', [SupervisorContactMessageController::class, 'updateStatus'])->name('contact-messages.update-status');
            Route::post('/contact-messages/{contactMessage}/reply', [SupervisorContactMessageController::class, 'sendReply'])->name('contact-messages.reply');

            /*
            |--------------------------------------------------------------------------
            | System Verification
            |--------------------------------------------------------------------------
            */
            Route::post('/projects/{project}/system-verification', [SupervisorProjectController::class, 'updateSystemVerification'])
                ->name('projects.system-verification.update');

            /*
            |--------------------------------------------------------------------------
            | Meetings
            |--------------------------------------------------------------------------
            */
            Route::post('/projects/meetings/store', [SupervisorProjectController::class, 'storeMeeting'])
                ->name('projects.meetings.store');

            Route::post('/projects/{project}/meetings/{meeting}/status', [SupervisorProjectController::class, 'updateMeetingStatus'])
                ->name('projects.meetings.status');

            Route::get('/meetings', [SupervisorProjectController::class, 'meetingsIndex'])->name('meetings.index');
            Route::get('/meetings/upcoming', [SupervisorProjectController::class, 'meetingsUpcoming'])->name('meetings.upcoming');
            Route::get('/meetings/completed', [SupervisorProjectController::class, 'meetingsCompleted'])->name('meetings.completed');
            Route::get('/meetings/create', [SupervisorProjectController::class, 'meetingsCreate'])->name('meetings.create');

            /*
            |--------------------------------------------------------------------------
            | Requests
            |--------------------------------------------------------------------------
            */
            Route::get('/requests', [SupervisorProjectController::class, 'requestsIndex'])->name('requests.index');
            Route::get('/requests/pending', [SupervisorProjectController::class, 'requestsPending'])->name('requests.pending');
            Route::get('/requests/completed', [SupervisorProjectController::class, 'requestsCompleted'])->name('requests.completed');

            Route::post('/projects/{project}/requests/store', [SupervisorProjectController::class, 'storeRequest'])
                ->name('projects.requests.store');

            Route::post('/requests/{requestItem}/status', [SupervisorProjectController::class, 'updateRequestStatus'])
                ->name('requests.status');

            /*
            |--------------------------------------------------------------------------
            | Evaluation
            |--------------------------------------------------------------------------
            */
            Route::post('/projects/{project}/evaluation', [SupervisorProjectController::class, 'storeEvaluation'])
                ->name('projects.evaluation.store');

            /*
            |--------------------------------------------------------------------------
            | File View
            |--------------------------------------------------------------------------
            */
            Route::get('/file/{id}', function ($id) {
                $file = \App\Models\ProjectRequestResponse::findOrFail($id);

                return response()->file(storage_path('app/public/' . $file->attachment_path));
            })->name('file.view');

            /*
|--------------------------------------------------------------------------
| Supervisor Notifications
|--------------------------------------------------------------------------
*/
Route::get('/notifications', [SupervisorNotificationController::class, 'index'])
    ->name('notifications.index');

Route::get('/notifications/unread-count', [SupervisorNotificationController::class, 'unreadCount'])
    ->name('notifications.count');

Route::get('/notifications/latest', [SupervisorNotificationController::class, 'latest'])
    ->name('notifications.latest');

Route::post('/notifications/{id}/read', [SupervisorNotificationController::class, 'markAsRead'])
    ->name('notifications.read');

Route::post('/notifications/mark-all-read', [SupervisorNotificationController::class, 'markAllRead'])
    ->name('notifications.markAllRead');
        });
});