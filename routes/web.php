<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\Frontend\Auth\AuthController as FrontendAuth;
use App\Http\Controllers\Frontend\Auth\LoginOtpController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LanguageController as FrontendLanguageController;
use App\Http\Controllers\Frontend\ProjectController as FrontendProjectController;
use App\Http\Controllers\Frontend\ProjectSubmissionController;
use App\Http\Controllers\Frontend\NotificationController as FrontNotificationController;
use App\Http\Controllers\Frontend\InvestorDashboardController;
use App\Http\Controllers\Frontend\AcademicDashboardController;
use App\Http\Controllers\Frontend\InvestorProjectSummaryController;
use App\Http\Controllers\Frontend\ContactController;

use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Frontend\Auth\SecurityController;
Route::get('/session-test', function () {
    $count = session('count', 0);
    $count++;
    session(['count' => $count]);

    return $count;
});
/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Public site + student/investor frontend
| Locale is separated from backend using frontend.locale middleware
|
*/

Route::middleware(['web', 'frontend.locale'])->group(function () {

    // ------------------------
    // FRONTEND PUBLIC PAGES
    // ------------------------
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // ------------------------
    // FRONTEND LANGUAGE SWITCH
    // ------------------------
    Route::get('/language/{locale}', [FrontendLanguageController::class, 'switch'])
        ->where('locale', 'en|ar')
        ->name('frontend.language.switch');

    // ------------------------
    // FRONTEND AUTH (GUEST ONLY)
    // ------------------------
    Route::middleware('guest:web')->prefix('auth')->group(function () {

        Route::get('/login', [FrontendAuth::class, 'showLogin'])->name('login.show');
        Route::post('/login', [FrontendAuth::class, 'login'])->middleware('throttle:login')->name('login.post');


        Route::get('/login/otp', [LoginOtpController::class, 'show'])->name('login.otp.show');
Route::post('/login/otp', [LoginOtpController::class, 'verify'])->name('login.otp.verify');
Route::post('/login/otp/resend', [LoginOtpController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('login.otp.resend');
Route::post('/login/otp/recovery', [LoginOtpController::class, 'verifyRecoveryCode'])
    ->middleware('throttle:6,1')
    ->name('login.otp.recovery');
       Route::get('/recovery-codes/download', [SecurityController::class, 'downloadRecoveryCodes'])->name('recovery-codes.download');
    Route::get('/register', fn () => view('frontend.auth.register'))->name('register.show');
        Route::get('/register/investor', fn () => view('frontend.auth.register_investor'))->name('register.investor');
        Route::post('/register/investor', [FrontendAuth::class, 'registerInvestor'])->name('register.investor.post');
        Route::get('/register/academic', fn () => view('frontend.auth.register_academic'))->name('register.academic');
        Route::post('/register/student', [FrontendAuth::class, 'registerStudent'])->name('register.student.post');
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']) ->middleware('throttle:6,1') ->name('password.email');
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

    // ------------------------
    // FRONTEND AUTH (AUTHENTICATED)
    // ------------------------
   Route::middleware('auth:web')->group(function () {
    Route::post('/auth/logout', [FrontendAuth::class, 'logout'])->name('frontend.logout');

    Route::get('/auth/email/verify', function () {
        $user = auth('web')->user();

        if (! $user) {
            return redirect()->route('login.show');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->to(match ($user->role) {
                'Investor' => route('dashboard.investor'),
                'Student'  => route('dashboard.academic'),
                default    => route('home'),
            });
        }

        return view('frontend.auth.verify-email');
    })->name('verification.notice');

    Route::get('/auth/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login.show');
        }

        $policy = \App\Services\AuthPolicyResolverService::resolveForUser($user);

        if ($policy['email_verification_mode'] === 'disabled') {
            return redirect()->to(match ($user->role) {
                'Investor' => route('dashboard.investor'),
                'Student'  => route('dashboard.academic'),
                default    => route('home'),
            });
        }

        if (! $user->hasVerifiedEmail()) {
            $request->fulfill();
        }

        return redirect()
            ->to(match ($user->role) {
                'Investor' => route('dashboard.investor'),
                'Student'  => route('dashboard.academic'),
                default    => route('home'),
            })
            ->with('success', 'Email verified successfully.');
    })->middleware('signed')->name('verification.verify');

    Route::post('/auth/email/verification-notification', function (Request $request) {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login.show');
        }

        $policy = \App\Services\AuthPolicyResolverService::resolveForUser($user);

        if ($policy['email_verification_mode'] === 'disabled') {
            return redirect()->to(match ($user->role) {
                'Investor' => route('dashboard.investor'),
                'Student'  => route('dashboard.academic'),
                default    => route('home'),
            });
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->to(match ($user->role) {
                'Investor' => route('dashboard.investor'),
                'Student'  => route('dashboard.academic'),
                default    => route('home'),
            });
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent successfully.');
    })->middleware('throttle:6,1')->name('verification.send');
});

    // ------------------------
    // FRONTEND MARKETPLACE (PUBLIC BROWSING)
    // ------------------------
    Route::get('/projects', [FrontendProjectController::class, 'index'])->name('frontend.projects.index');
    Route::get('/projects/{project}', [FrontendProjectController::class, 'show'])->name('frontend.projects.show');

    // ------------------------
    // FRONTEND PROFILE
    // ------------------------
Route::middleware(['auth:web'])->get('/profile', function () {
    $user = auth('web')->user();

    $policy = \App\Services\AuthPolicyResolverService::resolveForUser($user);

    if (
        ($policy['email_verification_mode'] ?? 'required') === 'required'
        && method_exists($user, 'hasVerifiedEmail')
        && ! $user->hasVerifiedEmail()
    ) {
        return redirect()->route('verification.notice');
    }

    return match ($user->role) {
        'Investor' => redirect()->route('dashboard.investor'),
        'Student'  => redirect()->route('dashboard.academic'),
        default    => redirect()->route('home'),
    };
})->name('profile');

    // ------------------------
    // FRONTEND VERIFIED USER AREA
    // ------------------------
    Route::middleware(['auth:web', 'frontend.verified.policy'])->group(function () {

                    Route::prefix('security')->name('security.')->group(function () {
    Route::get('/', [SecurityController::class, 'index'])->name('index');
    Route::post('/sessions/{sessionId}/revoke', [SecurityController::class, 'revokeSession'])->name('sessions.revoke');
    Route::post('/trusted-devices/{trustedDeviceId}/revoke', [SecurityController::class, 'revokeTrustedDevice'])->name('trusted-devices.revoke');
    Route::post('/logout-other-devices', [SecurityController::class, 'logoutOtherDevices'])->name('logout-other-devices');
    Route::post('/recovery-codes/regenerate', [SecurityController::class, 'regenerateRecoveryCodes'])
    ->name('recovery-codes.regenerate');
});
        Route::post('/projects/{project}/request-funding', [FrontendProjectController::class, 'requestFunding'])
            ->name('frontend.projects.requestFunding');

        Route::get('/dashboard/investor', [InvestorDashboardController::class, 'index'])
            ->name('dashboard.investor');

        Route::get('/dashboard/academic', [AcademicDashboardController::class, 'index'])
            ->name('dashboard.academic');

        Route::get('/projects/{project}/media', [FrontendProjectController::class, 'mediaForm'])
            ->name('projects.media.form');

        Route::post('/projects/{project}/media', [FrontendProjectController::class, 'mediaUpload'])
            ->name('projects.media.upload');

        Route::post('/projects/{project}/invest', [FrontendProjectController::class, 'invest'])
            ->name('frontend.projects.invest');

        Route::delete('/projects/{project}/interest', [FrontendProjectController::class, 'removeInterest'])
            ->name('frontend.projects.interest.remove');

        Route::get('/investor/investments', [InvestorDashboardController::class, 'investments'])
            ->name('investor.investments');

        Route::post('/student/requests/{requestItem}/respond', [AcademicDashboardController::class, 'respondToRequest'])
            ->name('student.requests.respond');

        Route::get('/settings/academic', [AcademicDashboardController::class, 'settings'])
            ->name('settings.academic');

        Route::post('/settings/academic/update', [AcademicDashboardController::class, 'updateSettings'])
            ->name('settings.academic.update');

        Route::get('/settings/investor', [InvestorDashboardController::class, 'settings'])
            ->name('settings.investor');

        Route::post('/settings/investor/update', [InvestorDashboardController::class, 'updateSettings'])
            ->name('settings.investor.update');

        Route::prefix('investor/projects')->name('investor.projects.')->group(function () {
            Route::get('{project}/summary', [InvestorProjectSummaryController::class, 'show'])
                ->name('summary');

            Route::get('{project}/pitch-deck/download', [InvestorProjectSummaryController::class, 'download'])
                ->name('pitch-deck.download');
        });

        // ------------------------
        // FRONTEND NOTIFICATIONS
        // ------------------------
        Route::get('/notifications', [FrontNotificationController::class, 'index'])
            ->name('frontend.notifications.index');

        Route::get('/notifications/unread-count', [FrontNotificationController::class, 'unreadCount'])
            ->name('frontend.notifications.count');
Route::get('/notifications/latest', [FrontNotificationController::class, 'latest'])
    ->name('frontend.notifications.latest');
        Route::post('/notifications/{id}/read', [FrontNotificationController::class, 'markAsRead'])
            ->name('frontend.notifications.read');

        Route::post('/notifications/mark-all-read', [FrontNotificationController::class, 'markAllRead'])
            ->name('frontend.notifications.markAllRead');
    });

    // ------------------------
    // STATIC / UTILITY
    // ------------------------
    Route::name('utility.')->group(function () {
        Route::get('/about', fn () => view('frontend.utility.about'))->name('about');
        Route::get('/careers', fn () => view('frontend.utility.careers'))->name('careers');
        Route::get('/contact', [ContactController::class, 'index'])->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
        Route::get('/how-it-works', fn () => view('frontend.utility.how-it-works'))->name('how-it-works');
        Route::get('/partnerships', fn () => view('frontend.utility.partnerships'))->name('partnerships');
        Route::get('/privacy', fn () => view('frontend.utility.privacy'))->name('privacy');
        Route::get('/support', fn () => view('frontend.utility.support'))->name('support');
        Route::get('/terms', fn () => view('frontend.utility.terms'))->name('terms');
        Route::get('/disclosures', fn () => view('frontend.utility.disclosures'))->name('disclosures');
    });

    // ------------------------
    // ACADEMIC SUBMISSION FLOW
    // ------------------------
    Route::prefix('submit-project')->name('project.submit.')->group(function () {
        Route::get('/', [ProjectSubmissionController::class, 'step1'])->name('step1');
        Route::post('/', [ProjectSubmissionController::class, 'postStep1'])->name('step1.post');

        Route::get('/step2', [ProjectSubmissionController::class, 'step2'])->name('step2');
        Route::post('/step2', [ProjectSubmissionController::class, 'postStep2'])->name('step2.post');

        Route::get('/step3', [ProjectSubmissionController::class, 'step3'])->name('step3');
        Route::post('/step3', [ProjectSubmissionController::class, 'postStep3'])->name('step3.post');

        Route::get('/step4', [ProjectSubmissionController::class, 'step4'])->name('step4');
        Route::post('/step4', [ProjectSubmissionController::class, 'postStep4'])->name('step4.post');

        Route::get('/confirm', [ProjectSubmissionController::class, 'confirm'])->name('confirm');
        Route::post('/final', [ProjectSubmissionController::class, 'submitFinal'])->name('final');

        Route::get('/resume', [ProjectSubmissionController::class, 'resume'])->name('resume');
    });

    // ------------------------
    // ADMIN PROJECT ACTIONS USED FROM FRONT FILE
    // ------------------------
    Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::patch('/projects/{project}/approve', [ProjectController::class, 'approve'])->name('projects.approve');
        Route::patch('/projects/{project}/publish', [ProjectController::class, 'publish'])->name('projects.publish');

        Route::get('/projects/{project}/scanner-review', [ProjectController::class, 'scannerReview'])
            ->name('projects.scannerReview');

        Route::post('/projects/{project}/start-scan', [ProjectController::class, 'startScan'])
            ->name('projects.startScan');
    });

    

    // ------------------------
    // DEBUG
    // ------------------------
    Route::get('/_debug/auth', function () {
        return response()->json([
            'web'   => auth('web')->check(),
            'admin' => auth('admin')->check(),
            'user'  => auth('admin')->user(),
        ]);
    })->middleware('web');
});