@extends('frontend.layouts.app')

@section('content')
@php
$btnPrimaryClass = 'inline-flex items-center justify-center rounded-2xl px-8 py-4 font-black bg-brand-accent text-white
hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft';
$btnSecondaryClass = 'inline-flex items-center justify-center rounded-2xl px-6 py-3 font-bold border border-brand-accent
text-theme-text hover:bg-brand-accent hover:text-white transition duration-300';

$currentDecision = $currentProject->final_decision ?? null;

$mediaUploadAllowedStatuses = ['approved', 'active', 'published'];
$mediaUploadAllowedDecisions = ['published'];

$decisionLabel = function ($decision) {
return match ($decision) {
'published' => __('frontend.academic_dashboard.decision_published'),
'revision_requested' => __('frontend.academic_dashboard.decision_revision_requested'),
'rejected' => __('frontend.academic_dashboard.decision_rejected'),
'pending' => __('frontend.academic_dashboard.decision_pending'),
null => null,
default => ucfirst(str_replace('_', ' ', $decision)),
};
};
@endphp

<div class="min-h-screen pt-28 pb-12 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <header class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="relative">
                <h1 class="text-4xl md:text-6xl font-black text-theme-text tracking-tight">
                    {{ __('frontend.academic_dashboard.welcome') }},
                    <span class="text-brand-accent">{{ explode(' ', $user->name)[0] }}</span>
                </h1>
                <p class="text-theme-muted mt-3 flex items-center tracking-[0.2em] uppercase text-xs font-bold">
                    <span class="w-10 h-[2px] bg-brand-accent mr-3"></span>
                    {{ __('frontend.academic_dashboard.researcher_identity') }}: {{ $user->id + 5000 }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('settings.academic') }}" class="{{ $btnSecondaryClass }}">
                    <i class="fas fa-cog mr-2"></i> {{ __('frontend.academic_dashboard.settings', [],
                    app()->getLocale()) }}
                </a>

                <a href="{{ route('project.submit.step1') }}" class="{{ $btnPrimaryClass }}">
                    <i class="fas fa-rocket mr-2"></i> {{ __('frontend.academic_dashboard.submit_new_research') }}
                </a>
            </div>
        </header>

        {{-- Announcements --}}
        @if(isset($announcements) && $announcements->count())
        @php
        $featuredAnnouncement = $announcements->first();
        $otherAnnouncements = $announcements->slice(1);
        @endphp

        <section id="announcementSection" class="mb-8">
            <div class="relative overflow-hidden rounded-[2rem] theme-panel shadow-brand-soft">
                <div class="relative z-10 p-5 md:p-7">
                    <div class="flex items-start gap-4">
                        <div
                            class="hidden sm:flex w-14 h-14 rounded-2xl border border-brand-accent bg-brand-accent-soft text-brand-accent items-center justify-center shrink-0">
                            <i class="fas fa-bullhorn text-lg"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-theme-surface-2 text-theme-muted border border-theme-border text-[11px] font-black uppercase tracking-[0.16em]">
                                    {{ __('frontend.academic_dashboard.announcement') }}
                                </span>

                                @if($featuredAnnouncement->is_pinned)
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-400/10 text-amber-500 border border-amber-400/20 text-[11px] font-black uppercase tracking-[0.16em]">
                                    <i class="fas fa-thumbtack text-[10px]"></i>
                                    {{ __('frontend.academic_dashboard.pinned') }}
                                </span>
                                @endif

                                @if($featuredAnnouncement->expires_at)
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-red-500/10 text-red-500 border border-red-500/20 text-[11px] font-black uppercase tracking-[0.16em]">
                                    <i class="fas fa-hourglass-half text-[10px]"></i>
                                    {{ __('frontend.academic_dashboard.until') }} {{
                                    $featuredAnnouncement->expires_at->format('M d, Y • h:i A') }}
                                </span>
                                @endif
                            </div>

                            <h2 class="text-xl md:text-2xl font-black text-theme-text leading-tight mb-2">
                                {{ $featuredAnnouncement->title }}
                            </h2>

                            <p class="text-theme-muted text-sm md:text-[15px] leading-7 max-w-4xl">
                                {{ $featuredAnnouncement->body }}
                            </p>
                        </div>

                        <button type="button" onclick="dismissAnnouncements()"
                            class="shrink-0 w-11 h-11 rounded-2xl border border-theme-border bg-theme-surface-2 hover:bg-brand-accent-soft text-theme-muted hover:text-theme-text transition flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    @if($otherAnnouncements->count())
                    <div class="mt-5 pt-5 border-t border-theme-border">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @foreach($otherAnnouncements as $announcement)
                            <div class="rounded-[1.5rem] border border-theme-border bg-theme-surface-2 transition p-4">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <h3 class="text-theme-text font-bold text-base leading-snug">
                                        {{ $announcement->title }}
                                    </h3>

                                    @if($announcement->is_pinned)
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-400/10 text-amber-500 border border-amber-400/20 shrink-0">
                                        <i class="fas fa-thumbtack text-[11px]"></i>
                                    </span>
                                    @endif
                                </div>

                                <p class="text-theme-muted text-sm leading-6">
                                    {{ \Illuminate\Support\Str::limit($announcement->body, 160) }}
                                </p>

                                @if($announcement->expires_at)
                                <div class="mt-3 text-[11px] text-red-500 font-semibold">
                                    {{ __('frontend.academic_dashboard.visible_until') }} {{
                                    $announcement->expires_at->format('M d, Y • h:i A') }}
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        @endif
        @if($errors->any())
        <div class="max-w-6xl mx-auto px-4 mb-6">
            <div class="p-4 rounded-xl border border-red-500/40 bg-red-500/10 text-red-600">
                <div class="font-bold mb-2">{{ __('frontend.academic_dashboard.please_review_following') }}</div>
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @if($currentProject && !$currentDecision && $currentProject->status === 'pending')
        <div class="max-w-6xl mx-auto px-4 mb-6">
            <div class="p-4 rounded-xl border border-yellow-500/40 bg-yellow-500/10 text-yellow-700">
                {!! __('frontend.academic_dashboard.project_pending_review', ['name' => e($currentProject->name)]) !!}
            </div>
        </div>
        @endif

        @if($currentProject && $currentDecision === 'rejected')
        <div class="max-w-6xl mx-auto px-4 mb-6">
            <div class="p-4 rounded-xl border border-red-500/40 bg-red-500/10 text-red-600">
                {!! __('frontend.academic_dashboard.project_rejected', ['name' => e($currentProject->name)]) !!}
            </div>
        </div>
        @endif

        @if($currentProject && $currentDecision === 'revision_requested')
        <div class="max-w-6xl mx-auto px-4 mb-6">
            <div class="p-4 rounded-xl border border-yellow-500/40 bg-yellow-500/10 text-yellow-700">
                {!! __('frontend.academic_dashboard.project_revision_requested', ['name' => e($currentProject->name)])
                !!}
            </div>
        </div>
        @endif

        @if($currentProject && $currentDecision === 'published')
        <div class="max-w-6xl mx-auto px-4 mb-6">
            <div class="p-4 rounded-xl alert-success-theme">
                {!! __('frontend.academic_dashboard.project_published', ['name' => e($currentProject->name)]) !!}
            </div>
        </div>
        @endif

        @if($currentProject && !$currentDecision && in_array($currentProject->status, $mediaUploadAllowedStatuses))
        <div class="max-w-6xl mx-auto px-4 mb-6">
            <div class="p-4 rounded-xl alert-success-theme">
                {!! __('frontend.academic_dashboard.project_approved_upload_media', ['name' =>
                e($currentProject->name)]) !!}
            </div>
        </div>
        @endif

        @if($currentProject)
        {{-- HERO --}}
        <div class="relative overflow-hidden theme-panel rounded-[2.5rem] mb-10 transition-all">
            <div class="p-8 md:p-12">
                <div class="flex flex-col lg:flex-row justify-between gap-10">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-6 flex-wrap">
                            <span
                                class="px-4 py-1.5 rounded-xl bg-brand-accent-soft text-brand-accent text-[10px] font-black uppercase tracking-[0.15em] border border-brand-accent">
                                {{ $currentProject->category ?? __('frontend.academic_dashboard.uncategorized') }}
                            </span>
                            <span class="text-theme-muted text-xs font-mono">REF: PRJ-{{ $currentProject->project_id +
                                1000 }}</span>
                        </div>

                        <h2 class="text-3xl md:text-5xl font-bold text-theme-text mb-8 leading-[1.1] tracking-tight">
                            {{ $currentProject->name }}
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <p class="text-theme-muted text-[10px] uppercase font-black mb-1 tracking-widest">{{
                                    __('frontend.academic_dashboard.target_budget') }}</p>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($currentProject->budget
                                    ?? 0) }}</p>
                            </div>

                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <p class="text-theme-muted text-[10px] uppercase font-black mb-1 tracking-widest">{{
                                    __('frontend.academic_dashboard.submission_date') }}</p>
                                <p class="text-xl text-theme-text font-semibold">{{
                                    $currentProject->created_at->format('M d, Y') }}</p>
                            </div>

                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <p class="text-theme-muted text-[10px] uppercase font-black mb-1 tracking-widest">{{
                                    __('frontend.academic_dashboard.final_decision') }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex h-2 w-2 relative">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-500 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                    </div>
                                    <span class="text-lg font-bold text-theme-text italic">
                                        {{ $decisionLabel($currentDecision) ?? ($currentProject->status == 'pending' ?
                                        __('frontend.academic_dashboard.reviewing') : ucfirst($currentProject->status))
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Media Preview --}}
                        <div class="mt-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3
                                    class="text-xs font-black text-theme-text uppercase tracking-[0.25em] flex items-center gap-3">
                                    <i class="fas fa-photo-video text-brand-accent"></i> {{
                                    __('frontend.academic_dashboard.media_preview') }}
                                </h3>
                                <div class="text-xs text-theme-muted font-mono">
                                    {{ __('frontend.academic_dashboard.images') }}: {{ $currentImages->count() }} • {{
                                    __('frontend.academic_dashboard.video') }}: {{ $currentVideoUrl ?
                                    __('frontend.academic_dashboard.yes') : __('frontend.academic_dashboard.no') }}
                                </div>
                            </div>

                            @if($currentImages->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @foreach($currentImages->take(4) as $m)
                                <a href="{{ $m->getUrl() }}" target="_blank"
                                    class="project-preview-link relative overflow-hidden rounded-2xl border border-theme-border bg-theme-surface-2 transition">
                                    <img src="{{ $m->getUrl() }}" class="w-full h-28 object-cover"
                                        alt="{{ __('frontend.academic_dashboard.project_image') }}">
                                </a>
                                @endforeach
                            </div>
                            @else
                            <div
                                class="p-5 rounded-2xl border border-theme-border bg-theme-surface-2 text-theme-muted text-sm">
                                {{ __('frontend.academic_dashboard.no_images_uploaded') }}
                            </div>
                            @endif

                            @if($currentVideoUrl)
                            <button type="button" onclick="openVideoModal('{{ $currentVideoUrl }}')"
                                class="mt-4 w-full flex items-center justify-between p-5 bg-theme-surface-2 hover:bg-brand-accent-soft border border-theme-border rounded-2xl transition-all text-theme-text">
                                <span class="font-bold text-xs uppercase tracking-wider">{{
                                    __('frontend.academic_dashboard.play_video') }}</span>
                                <i class="fas fa-play text-brand-accent"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col justify-center gap-4 min-w-[260px]">
                        <a href="{{ route('frontend.projects.show', $currentProject->project_id) }}"
                            class="group/btn flex items-center justify-between p-5 bg-brand-accent text-white rounded-2xl transition-all hover:bg-brand-accent-strong">
                            <span class="font-black uppercase text-xs tracking-wider">{{
                                __('frontend.academic_dashboard.project_portfolio') }}</span>
                            <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                        </a>

                        @if(in_array($currentDecision, $mediaUploadAllowedDecisions) || (!$currentDecision &&
                        in_array($currentProject->status, $mediaUploadAllowedStatuses)))
                        <button type="button"
                            onclick="openUploadModal('{{ route('projects.media.upload', $currentProject->project_id) }}')"
                            class="flex items-center justify-between p-5 bg-theme-surface-2 hover:bg-brand-accent-soft border border-theme-border rounded-2xl transition-all group text-theme-text">
                            <span class="font-bold text-xs uppercase tracking-wider">{{
                                __('frontend.academic_dashboard.upload_project_media') }}</span>
                            <i
                                class="fas fa-upload group-hover:-translate-y-1 transition-transform text-brand-accent"></i>
                        </button>
                        @else
                        <div
                            class="flex items-center justify-between p-2 bg-theme-surface-2 border border-theme-border rounded-2xl text-theme-muted opacity-90">
                            <div>
                                <span class="block font-bold text-xs uppercase tracking-wider mb-1">{{
                                    __('frontend.academic_dashboard.upload_locked') }}</span>
                                <span class="text-xs opacity-80">
                                    {{ __('frontend.academic_dashboard.upload_locked_text') }}
                                </span>
                            </div>
                            <i class="fas fa-lock text-theme-muted"></i>
                        </div>
                        @endif

                        @php
                        $scanState = strtolower((string) ($currentProject->status ?? $currentProject->scanner_status ??
                        'draft'));
                        @endphp

                        @if(in_array($scanState, ['draft', 'scan_failed', 'scan_requested', 'pending', 'rejected']))
                        @php
                        $scanData = base64_encode(json_encode([
                        'platform_project_id' => $currentProject->project_id,
                        'project_name' => $currentProject->name,
                        'student_name' => $user->name,
                        'student_email' => $user->email,
                        'language' => $currentProject->primary_language ?? 'php',
                        ]));
                        @endphp

                        <a href="{{ env('SCANNER_PUBLIC_BASE_URL') }}/submit?data={{ urlencode($scanData) }}"
                            class="flex items-center justify-between p-2 bg-blue-600 text-white rounded-2xl transition-all hover:bg-blue-700 group">
                            <div>
                                <span class="block font-black uppercase text-xs tracking-wider">
                                    {{ __('frontend.academic_dashboard.technical_scan') }}
                                </span>
                                <span class="text-xs opacity-80">
                                    {{ __('frontend.academic_dashboard.scan_now_question') }}
                                </span>
                            </div>
                            <i class="fas fa-microscope group-hover:scale-110 transition-transform"></i>
                        </a>

                        @elseif(in_array($scanState, ['scan_completed', 'awaiting_manual_review', 'approved',
                        'published', 'active']))
                        @php
                        $scanData = base64_encode(json_encode([
                        'platform_project_id' => $currentProject->project_id,
                        'project_name' => $currentProject->name,
                        'student_name' => $user->name,
                        'student_email' => $user->email,
                        'language' => $currentProject->language ?? $currentProject->primary_language ?? 'php',
                        ]));
                        @endphp

                        <a href="{{ env('SCANNER_PUBLIC_BASE_URL') }}/submit?data={{ urlencode($scanData) }}"
                            class="flex items-center justify-between p-2 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition group">
                            <div>
                                <span class="block font-black uppercase text-xs tracking-wider">
                                    {{ __('frontend.academic_dashboard.technical_scan') }}
                                </span>
                                <span class="text-xs opacity-80">
                                    {{ __('frontend.academic_dashboard.scan_completed_open_workspace') }}
                                </span>
                            </div>
                            <i class="fas fa-check-circle group-hover:scale-110 transition-transform"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="h-1.5 w-full bg-theme-surface-2">
                <div class="h-full bg-brand-accent transition-all duration-1000" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        {{-- REQUESTS --}}
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-black text-theme-text uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fas fa-inbox text-brand-accent"></i> {{
                    __('frontend.academic_dashboard.supervisor_requests') }}
                </h2>
                <div class="text-xs text-theme-muted font-mono">
                    {{ __('frontend.academic_dashboard.total') }}: {{ $currentRequests->count() }}
                </div>
            </div>

            @if($currentRequests->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($currentRequests as $requestItem)
                @php
                $status = strtolower($requestItem->status ?? 'pending');
                $requestType = strtolower($requestItem->request_type ?? '');
                $statusClasses = match($status) {
                'completed' => 'bg-green-500/10 text-green-600 border-green-500/20',
                'cancelled' => 'bg-red-500/10 text-red-600 border-red-500/20',
                default => 'bg-yellow-500/10 text-yellow-700 border-yellow-500/20',
                };
                @endphp

                <div class="theme-panel rounded-[2rem] p-6">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <div class="text-theme-text text-lg font-black mb-1">{{ $requestItem->title }}</div>
                            <div class="text-theme-muted text-xs uppercase tracking-[0.15em] font-bold">
                                {{ ucfirst(str_replace('_', ' ', $requestItem->request_type)) }}
                            </div>
                        </div>

                        <span
                            class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] border {{ $statusClasses }}">
                            {{ ucfirst($requestItem->status) }}
                        </span>
                    </div>

                    <div class="text-theme-muted text-sm leading-relaxed mb-4 whitespace-pre-line">
                        {{ $requestItem->description ?: __('frontend.academic_dashboard.no_additional_details') }}
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5 text-sm">
                        <div class="theme-panel-soft rounded-2xl p-4">
                            <div class="text-theme-muted text-[10px] uppercase font-black tracking-widest mb-1">{{
                                __('frontend.academic_dashboard.supervisor') }}</div>
                            <div class="text-theme-text font-semibold">{{ $requestItem->supervisor->name ??
                                __('frontend.academic_dashboard.supervisor_fallback') }}</div>
                        </div>

                        <div class="theme-panel-soft rounded-2xl p-4">
                            <div class="text-theme-muted text-[10px] uppercase font-black tracking-widest mb-1">{{
                                __('frontend.academic_dashboard.due_date') }}</div>
                            <div class="text-theme-text font-semibold">
                                {{ $requestItem->due_date ? \Carbon\Carbon::parse($requestItem->due_date)->format('M d,
                                Y') : __('frontend.academic_dashboard.not_specified') }}
                            </div>
                        </div>
                    </div>

                    @if($requestItem->latestResponse)
                    <div class="mb-5 p-4 rounded-2xl border border-green-500/20 bg-green-500/5">
                        <div class="text-green-600 text-xs font-black uppercase tracking-[0.15em] mb-2">
                            {{ __('frontend.academic_dashboard.latest_response_sent') }}
                        </div>

                        @if($requestItem->latestResponse->response_text)
                        <div class="text-theme-text text-sm mb-2 whitespace-pre-line">
                            {{ $requestItem->latestResponse->response_text }}
                        </div>
                        @endif

                        <div class="flex flex-wrap gap-3">
                            @if($requestItem->latestResponse->response_link)
                            <a href="{{ $requestItem->latestResponse->response_link }}" target="_blank"
                                class="text-brand-accent text-sm font-bold hover:underline">
                                {{ __('frontend.academic_dashboard.open_submitted_link') }}
                            </a>
                            @endif

                            @if($requestItem->latestResponse->attachment_path)
                            <a href="{{ asset('storage/' . $requestItem->latestResponse->attachment_path) }}"
                                target="_blank" class="text-cyan-600 text-sm font-bold hover:underline">
                                {{ __('frontend.academic_dashboard.download_attachment') }}
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="flex flex-wrap gap-3">
                        <button type="button" onclick="openRequestResponseModal(
                                                '{{ route('student.requests.respond', $requestItem->id) }}',
                                                @js($requestItem->title),
                                                @js($requestType)
                                            )"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-brand-accent text-white font-black hover:bg-brand-accent-strong transition shadow-brand-soft">
                            <i class="fas fa-paper-plane"></i>
                            {{ $requestItem->latestResponse ? __('frontend.academic_dashboard.update_and_send') :
                            __('frontend.academic_dashboard.send_to_supervisor') }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="theme-panel rounded-[2rem] p-10 text-center text-theme-muted">
                {{ __('frontend.academic_dashboard.no_supervisor_requests') }}
            </div>
            @endif
        </section>

        {{-- MEETINGS --}}
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-black text-theme-text uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-brand-accent"></i> {{
                    __('frontend.academic_dashboard.meetings_demo_sessions') }}
                </h2>
                <div class="text-xs text-theme-muted font-mono">
                    {{ __('frontend.academic_dashboard.total') }}: {{ $currentMeetings->count() }}
                </div>
            </div>

            @if($currentMeetings->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($currentMeetings as $meeting)
                @php
                $meetingStatus = strtolower($meeting->status ?? 'scheduled');
                $meetingStatusClasses = match($meetingStatus) {
                'completed' => 'bg-green-500/10 text-green-600 border-green-500/20',
                'cancelled' => 'bg-red-500/10 text-red-600 border-red-500/20',
                default => 'bg-yellow-500/10 text-yellow-700 border-yellow-500/20',
                };
                @endphp

                <div class="theme-panel rounded-[2rem] p-6">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <div class="text-theme-text text-lg font-black mb-1">{{ $meeting->title }}</div>
                            <div class="text-theme-muted text-xs uppercase tracking-[0.15em] font-bold">
                                {{ ucfirst($meeting->meeting_type) }}
                            </div>
                        </div>

                        <span
                            class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] border {{ $meetingStatusClasses }}">
                            {{ ucfirst($meeting->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5 text-sm">
                        <div class="theme-panel-soft rounded-2xl p-4">
                            <div class="text-theme-muted text-[10px] uppercase font-black tracking-widest mb-1">{{
                                __('frontend.academic_dashboard.meeting_date') }}</div>
                            <div class="text-theme-text font-semibold">
                                {{ $meeting->meeting_date ? \Carbon\Carbon::parse($meeting->meeting_date)->format('M d,
                                Y') : __('frontend.academic_dashboard.not_set') }}
                            </div>
                        </div>

                        <div class="theme-panel-soft rounded-2xl p-4">
                            <div class="text-theme-muted text-[10px] uppercase font-black tracking-widest mb-1">{{
                                __('frontend.academic_dashboard.meeting_time') }}</div>
                            <div class="text-theme-text font-semibold">
                                {{ $meeting->meeting_time ? \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i
                                A') : __('frontend.academic_dashboard.not_set') }}
                            </div>
                        </div>
                    </div>

                    @if($meeting->notes)
                    <div class="mb-4 p-4 rounded-2xl border border-theme-border bg-theme-surface-2">
                        <div class="text-theme-muted text-[10px] uppercase font-black tracking-widest mb-2">{{
                            __('frontend.academic_dashboard.meeting_notes') }}</div>
                        <div class="text-theme-text text-sm whitespace-pre-line">{{ $meeting->notes }}</div>
                    </div>
                    @endif

                    <div class="flex flex-wrap gap-3">
                        @if($meeting->meeting_link)
                        <a href="{{ $meeting->meeting_link }}" target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-green-500 text-white font-black hover:bg-green-600 transition shadow-brand-soft">
                            <i class="fas fa-video"></i>
                            {{ __('frontend.academic_dashboard.join_meeting') }}
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="theme-panel rounded-[2rem] p-10 text-center text-theme-muted">
                {{ __('frontend.academic_dashboard.no_meetings') }}
            </div>
            @endif
        </section>

        {{-- Projects list --}}
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-black text-theme-text uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fas fa-layer-group text-brand-accent"></i> {{
                    __('frontend.academic_dashboard.your_projects') }}
                </h2>
                <div class="text-xs text-theme-muted font-mono">{{ __('frontend.academic_dashboard.total') }}: {{
                    $projects->count() }}</div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                @php
                $thumb = $project->getFirstMediaUrl('images');
                $imgCount = $project->getMedia('images')->count();
                $hasVideo = (bool) $project->getFirstMediaUrl('videos');
                $active = $project->project_id === $currentProject->project_id;
                $projectDecision = $project->final_decision ?? null;
                @endphp

                <a href="{{ route('frontend.projects.show', $project) }}"
                    class="block rounded-[2rem] overflow-hidden transition border {{ $active ? 'border-brand-accent shadow-brand-soft' : 'border-theme-border theme-panel hover:border-brand-accent/40' }}">
                    <div class="h-44 bg-theme-surface-2 relative">
                        @if($thumb)
                        <img src="{{ $thumb }}" class="w-full h-full object-cover"
                            alt="{{ __('frontend.academic_dashboard.project_thumbnail') }}">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-theme-muted">
                            <div class="text-center">
                                <i class="fas fa-image text-3xl mb-2"></i>
                                <div class="text-xs uppercase tracking-[0.2em] font-black">{{
                                    __('frontend.academic_dashboard.no_image') }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="absolute top-4 left-4 flex gap-2">
                            <span
                                class="px-3 py-1 rounded-xl bg-brand-accent-soft text-brand-accent text-[10px] font-black uppercase tracking-[0.15em] border border-brand-accent">
                                {{ $project->category ?? __('frontend.academic_dashboard.general') }}
                            </span>
                            @if($hasVideo)
                            <span
                                class="px-3 py-1 rounded-xl bg-theme-surface text-theme-text text-[10px] font-black uppercase tracking-[0.15em] border border-theme-border">
                                {{ __('frontend.academic_dashboard.video') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-theme-muted text-xs font-mono">PRJ-{{ $project->project_id + 1000
                                }}</span>
                            <span class="text-theme-muted text-xs font-mono">{{ $project->created_at?->format('M d, Y')
                                }}</span>
                        </div>

                        <h3 class="text-theme-text text-lg font-black leading-tight mb-3">
                            {{ \Illuminate\Support\Str::limit($project->name, 60) }}
                        </h3>

                        <div class="flex items-center justify-between text-sm">
                            <div class="text-theme-muted">
                                <span>{{ __('frontend.academic_dashboard.images') }}:</span> <span
                                    class="font-bold text-theme-text">{{ $imgCount }}</span>
                            </div>
                            <div class="text-theme-muted">
                                <span>{{ __('frontend.academic_dashboard.status') }}:</span>
                                <span class="font-bold text-theme-text">
                                    {{ $decisionLabel($projectDecision) ?? ($project->status === 'pending' ?
                                    __('frontend.academic_dashboard.reviewing') : ucfirst($project->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @else
        <div class="theme-panel rounded-[3rem] p-20 text-center mb-12">
            <div
                class="w-24 h-24 bg-brand-accent-soft rounded-full flex items-center justify-center mx-auto mb-8 text-brand-accent text-4xl shadow-brand-soft">
                <i class="fas fa-atom animate-spin-slow"></i>
            </div>
            <h2 class="text-3xl font-bold text-theme-text mb-3">{{ __('frontend.academic_dashboard.no_active_research')
                }}</h2>
            <p class="text-theme-muted mb-10 max-w-lg mx-auto leading-relaxed">
                {{ __('frontend.academic_dashboard.no_active_research_text') }}
            </p>
            <a href="{{ route('project.submit.step1') }}" class="{{ $btnPrimaryClass }}">
                {{ __('frontend.academic_dashboard.start_submission') }}
            </a>
        </div>
        @endif

    </div>
</div>

{{-- VIDEO MODAL --}}
<div id="videoModal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-black/70" onclick="closeVideoModal()"></div>
    <div class="relative z-10 h-full overflow-y-auto">
        <div class="min-h-full flex items-start justify-center px-4 py-10">
            <div class="w-full max-w-4xl theme-panel rounded-[2rem] overflow-hidden">
                <div class="p-5 border-b border-theme-border flex items-center justify-between">
                    <div class="text-theme-text font-black">{{ __('frontend.academic_dashboard.project_video') }}</div>
                    <button class="text-theme-muted hover:text-theme-text" onclick="closeVideoModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    <video id="videoPlayer" class="w-full rounded-2xl border border-theme-border bg-black" controls
                        playsinline>
                        <source id="videoSource" src="" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- UPLOAD MODAL --}}
<div id="uploadModal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-black/70" onclick="closeUploadModal()"></div>
    <div class="relative z-10 h-full overflow-y-auto">
        <div class="min-h-full flex items-start justify-center px-4 py-10">
            <div class="w-full max-w-2xl theme-panel rounded-[2rem] overflow-hidden">
                <div class="p-5 border-b border-theme-border flex items-center justify-between">
                    <div>
                        <div class="text-theme-text font-black">{{ __('frontend.academic_dashboard.upload_data') }}
                        </div>
                        <div class="text-theme-muted text-xs font-mono">{{
                            __('frontend.academic_dashboard.add_images_video') }}</div>
                    </div>
                    <button class="text-theme-muted hover:text-theme-text" onclick="closeUploadModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="uploadForm" class="p-6 space-y-5" method="POST" enctype="multipart/form-data" action="">
                    @csrf

                    <div class="theme-panel-soft p-4 rounded-2xl">
                        <label class="block text-sm font-bold text-theme-text mb-2">{{
                            __('frontend.academic_dashboard.add_photos_multiple') }}</label>
                        <input type="file" name="project_photos[]" multiple accept="image/*"
                            class="w-full p-2 rounded-lg border border-theme-border bg-theme-surface text-theme-text">
                    </div>

                    <div class="theme-panel-soft p-4 rounded-2xl">
                        <label class="block text-sm font-bold text-theme-text mb-2">{{
                            __('frontend.academic_dashboard.add_video_single') }}</label>
                        <input type="file" name="project_video" accept="video/*"
                            class="w-full p-2 rounded-lg border border-theme-border bg-theme-surface text-theme-text">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" onclick="closeUploadModal()"
                            class="px-6 py-3 rounded-2xl bg-theme-surface-2 hover:bg-brand-accent-soft border border-theme-border text-theme-text font-bold">
                            {{ __('frontend.academic_dashboard.cancel') }}
                        </button>
                        <button type="submit"
                            class="px-6 py-3 rounded-2xl bg-brand-accent text-white font-black hover:bg-brand-accent-strong transition">
                            {{ __('frontend.academic_dashboard.upload_now') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- REQUEST RESPONSE MODAL --}}
<div id="requestResponseModal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-black/70" onclick="closeRequestResponseModal()"></div>

    <div class="relative z-10 h-full overflow-y-auto">
        <div class="min-h-full flex items-start justify-center px-4 py-10">
            <div class="w-full max-w-3xl theme-panel rounded-[2rem] overflow-hidden">
                <div class="p-5 border-b border-theme-border flex items-center justify-between">
                    <div>
                        <div class="text-theme-text font-black" id="requestResponseModalTitle">{{
                            __('frontend.academic_dashboard.send_response_to_supervisor') }}</div>
                        <div class="text-theme-muted text-xs font-mono" id="requestResponseModalSubtitle">{{
                            __('frontend.academic_dashboard.submit_text_link_attachment') }}</div>
                    </div>

                    <button class="text-theme-muted hover:text-theme-text" onclick="closeRequestResponseModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="requestResponseForm" class="p-6 space-y-5" method="POST" enctype="multipart/form-data"
                    action="">
                    @csrf

                    <input type="hidden" name="response_text" id="generated_response_text">
                    <input type="hidden" name="response_link" id="generated_response_link">

                    <div id="normalRequestFields" class="space-y-5">
                        <div class="theme-panel-soft p-4 rounded-2xl">
                            <label class="block text-sm font-bold text-theme-text mb-2">{{
                                __('frontend.academic_dashboard.response_text') }}</label>
                            <textarea id="normal_response_text" rows="5"
                                class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                placeholder="{{ __('frontend.academic_dashboard.write_response_here') }}"></textarea>
                        </div>

                        <div class="theme-panel-soft p-4 rounded-2xl">
                            <label class="block text-sm font-bold text-theme-text mb-2">{{
                                __('frontend.academic_dashboard.response_link') }}</label>
                            <input type="url" id="normal_response_link"
                                class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                placeholder="{{ __('frontend.academic_dashboard.response_link_placeholder') }}">
                        </div>
                    </div>

                    <div id="systemVerificationFields" class="hidden space-y-5">
                        <div
                            class="bg-brand-accent-soft border border-brand-accent rounded-2xl p-4 text-theme-text text-sm">
                            {!! __('frontend.academic_dashboard.fill_at_least_four') !!}
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <label class="block text-sm font-bold text-theme-text mb-2">{{
                                    __('frontend.academic_dashboard.frontend_url') }}</label>
                                <input type="url" id="sv_frontend_url"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                    placeholder="https://your-frontend.com">
                            </div>

                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <label class="block text-sm font-bold text-theme-text mb-2">{{
                                    __('frontend.academic_dashboard.backend_url') }}</label>
                                <input type="url" id="sv_backend_url"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                    placeholder="https://your-backend.com">
                            </div>

                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <label class="block text-sm font-bold text-theme-text mb-2">{{
                                    __('frontend.academic_dashboard.api_health_url') }}</label>
                                <input type="url" id="sv_api_health_url"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                    placeholder="https://api.your-app.com/health">
                            </div>

                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <label class="block text-sm font-bold text-theme-text mb-2">{{
                                    __('frontend.academic_dashboard.admin_panel_url') }}</label>
                                <input type="url" id="sv_admin_panel_url"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                    placeholder="https://your-app.com/admin">
                            </div>

                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <label class="block text-sm font-bold text-theme-text mb-2">{{
                                    __('frontend.academic_dashboard.demo_account') }}</label>
                                <input type="text" id="sv_demo_account"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                    placeholder="demo@example.com">
                            </div>

                            <div class="theme-panel-soft p-4 rounded-2xl">
                                <label class="block text-sm font-bold text-theme-text mb-2">{{
                                    __('frontend.academic_dashboard.demo_password') }}</label>
                                <input type="text" id="sv_demo_password"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                    placeholder="{{ __('frontend.academic_dashboard.password_plain') }}">
                            </div>
                        </div>

                        <div class="theme-panel-soft p-4 rounded-2xl">
                            <label class="block text-sm font-bold text-theme-text mb-2">{{
                                __('frontend.academic_dashboard.deployment_notes') }}</label>
                            <textarea id="sv_deployment_notes" rows="5"
                                class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                placeholder="{{ __('frontend.academic_dashboard.deployment_notes_placeholder') }}"></textarea>
                        </div>
                    </div>

                    <div class="theme-panel-soft p-4 rounded-2xl">
                        <label class="block text-sm font-bold text-theme-text mb-3">{{
                            __('frontend.academic_dashboard.attachment') }}</label>

                        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                            <input type="file" name="attachment"
                                class="flex-1 p-2 rounded-lg border border-theme-border bg-theme-surface text-theme-text">

                            <button type="button" onclick="submitRequestResponseForm()"
                                class="px-6 py-3 rounded-xl bg-green-500 text-white font-black hover:bg-green-600 transition-all duration-300 flex items-center justify-center gap-2 shadow-brand-soft">
                                <i class="fas fa-paper-plane"></i>
                                {{ __('frontend.academic_dashboard.send_now') }}
                            </button>
                        </div>

                        <div class="text-theme-muted text-xs mt-2">
                            {{ __('frontend.academic_dashboard.allowed_attachment_types') }}
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" onclick="closeRequestResponseModal()"
                            class="px-6 py-3 rounded-2xl bg-theme-surface-2 hover:bg-brand-accent-soft border border-theme-border text-theme-text font-bold">
                            {{ __('frontend.academic_dashboard.cancel') }}
                        </button>

                        <button type="button" onclick="submitRequestResponseForm()"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-brand-accent text-white font-black hover:bg-brand-accent-strong transition shadow-brand-soft">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('frontend.academic_dashboard.send_to_supervisor') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let currentRequestMode = 'normal';

    document.addEventListener('DOMContentLoaded', function () {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!document.getElementById('vg-academic-dashboard-style')) {
            const style = document.createElement('style');
            style.id = 'vg-academic-dashboard-style';
            style.innerHTML = `
                @keyframes spin-slow {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }

                @keyframes vgSpin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }

                .animate-spin-slow {
                    animation: spin-slow 8s linear infinite;
                }

                .vg-progress-line {
                    position: fixed;
                    top: 0;
                    left: 0;
                    height: 3px;
                    width: 0%;
                    z-index: 9999;
                    pointer-events: none;
                    background: linear-gradient(90deg, rgba(99,102,241,0.98), rgba(34,197,94,0.98));
                    box-shadow: 0 0 18px rgba(99,102,241,0.28);
                    transition: width 0.08s linear;
                }
            `;
            document.head.appendChild(style);
        }

        // Reading progress
        const progress = document.createElement('div');
        progress.className = 'vg-progress-line';
        document.body.appendChild(progress);

        function updateProgress() {
            const scrollTop = window.scrollY || window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const percent = docHeight > 0 ? Math.min((scrollTop / docHeight) * 100, 100) : 0;
            progress.style.width = percent + '%';
        }

        updateProgress();
        window.addEventListener('scroll', updateProgress, { passive: true });
        window.addEventListener('resize', updateProgress);

        // Announcement memory
        try {
            const dismissed = sessionStorage.getItem('academic_dashboard_announcements_dismissed');
            const announcementSection = document.getElementById('announcementSection');
            if (dismissed === '1' && announcementSection) {
                announcementSection.remove();
            }
        } catch (e) {}

        // Premium entrance
        if (!prefersReducedMotion) {
            const header = document.querySelector('header.mb-10');
            const alerts = Array.from(document.querySelectorAll('.max-w-6xl.mx-auto.px-4.mb-6'));
            const hero = document.querySelector('.relative.overflow-hidden.theme-panel.rounded-\\[2\\.5rem\\].mb-10');
            const requestSections = Array.from(document.querySelectorAll('section.mb-12'));

            if (header) {
                header.style.opacity = '0';
                header.style.transform = 'translateY(34px)';
                header.style.transition = 'opacity 1.05s ease, transform 1.05s cubic-bezier(0.22, 1, 0.36, 1)';
                setTimeout(() => {
                    header.style.opacity = '1';
                    header.style.transform = 'translateY(0)';
                }, 120);
            }

            alerts.forEach((alert, index) => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(18px)';
                alert.style.transition = 'opacity 0.9s ease, transform 0.9s ease';
                setTimeout(() => {
                    alert.style.opacity = '1';
                    alert.style.transform = 'translateY(0)';
                }, 280 + (index * 130));
            });

            if (hero) {
                hero.style.opacity = '0';
                hero.style.transform = 'translateY(38px)';
                hero.style.transition = 'opacity 1.1s ease, transform 1.1s cubic-bezier(0.22, 1, 0.36, 1)';
                setTimeout(() => {
                    hero.style.opacity = '1';
                    hero.style.transform = 'translateY(0)';
                }, 420);
            }

            requestSections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(34px)';
                section.style.transition = 'opacity 1s ease, transform 1s cubic-bezier(0.22, 1, 0.36, 1)';
                setTimeout(() => {
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 740 + (index * 220));
            });
        }

        // Budget count-up
        function animateValue(el, finalValue, prefix = '$', duration = 1700) {
            const startTime = performance.now();

            function update(now) {
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const currentValue = Math.floor(finalValue * eased);
                el.textContent = prefix + currentValue.toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.textContent = prefix + finalValue.toLocaleString();
                }
            }

            requestAnimationFrame(update);
        }

        if (!prefersReducedMotion) {
            const budgetEl = Array.from(document.querySelectorAll('.theme-panel-soft p')).find(el => {
                return /^\$\d[\d,]*$/.test(el.textContent.trim());
            });

            if (budgetEl) {
                const value = parseInt(budgetEl.textContent.replace(/[^\d]/g, ''), 10);
                if (!isNaN(value)) {
                    budgetEl.textContent = '$0';
                    setTimeout(() => {
                        animateValue(budgetEl, value, '$', 1800);
                    }, 1050);
                }
            }
        }

        // Hover polish
        const hoverCards = document.querySelectorAll(
            '.theme-panel, .theme-panel-soft, section .grid > .theme-panel, .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3 > a'
        );

        hoverCards.forEach(card => {
            card.style.transition = 'transform 0.32s ease, box-shadow 0.32s ease, border-color 0.32s ease';

            card.addEventListener('mouseenter', function () {
                if (prefersReducedMotion) return;
                if (card.closest('#videoModal, #uploadModal, #requestResponseModal')) return;
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 22px 48px rgba(0,0,0,0.09)';
            });

            card.addEventListener('mouseleave', function () {
                card.style.transform = '';
                card.style.boxShadow = '';
            });
        });

        // Project cards stagger
        if (!prefersReducedMotion) {
            const projectCards = Array.from(document.querySelectorAll('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3 > a'));
            projectCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(24px)';
                card.style.transition = 'opacity 0.9s ease, transform 0.9s cubic-bezier(0.22, 1, 0.36, 1)';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 1220 + (index * 120));
            });
        }

        // Request / meeting cards stagger
        if (!prefersReducedMotion) {
            const sectionCards = Array.from(document.querySelectorAll('section .grid > .theme-panel'));
            sectionCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(22px)';
                card.style.transition = 'opacity 0.88s ease, transform 0.88s ease';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 980 + (index * 90));
            });
        }

        // Image preview lightbox
        const previewLinks = document.querySelectorAll('.project-preview-link img');
        if (previewLinks.length) {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div id="academicImagePreviewModal" style="
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,0.82);
                    display: none;
                    align-items: center;
                    justify-content: center;
                    z-index: 10000;
                    padding: 24px;
                    opacity: 0;
                    transition: opacity 0.25s ease;
                ">
                    <button type="button" id="academicImagePreviewClose" style="
                        position: absolute;
                        top: 18px;
                        right: 18px;
                        width: 46px;
                        height: 46px;
                        border: none;
                        border-radius: 14px;
                        background: rgba(255,255,255,0.12);
                        color: white;
                        font-size: 20px;
                        cursor: pointer;
                    ">×</button>
                    <img id="academicImagePreviewTarget" src="" alt="Preview" style="
                        max-width: 92vw;
                        max-height: 88vh;
                        border-radius: 20px;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.35);
                        transform: scale(0.96);
                        transition: transform 0.25s ease;
                    ">
                </div>
            `;
            document.body.appendChild(modal);

            const modalEl = document.getElementById('academicImagePreviewModal');
            const modalImg = document.getElementById('academicImagePreviewTarget');
            const closeBtn = document.getElementById('academicImagePreviewClose');

            previewLinks.forEach(img => {
                const link = img.closest('a');
                if (!link) return;

                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    modalImg.src = link.href;
                    modalEl.style.display = 'flex';

                    requestAnimationFrame(() => {
                        modalEl.style.opacity = '1';
                        modalImg.style.transform = 'scale(1)';
                    });

                    document.body.style.overflow = 'hidden';
                });
            });

            function closePreviewModal() {
                modalEl.style.opacity = '0';
                modalImg.style.transform = 'scale(0.96)';

                setTimeout(() => {
                    modalEl.style.display = 'none';
                    modalImg.src = '';
                    if (!isAnySystemModalOpen()) {
                        document.body.style.overflow = '';
                    }
                }, 220);
            }

            closeBtn.addEventListener('click', closePreviewModal);
            modalEl.addEventListener('click', function (e) {
                if (e.target === modalEl) closePreviewModal();
            });

            window.closeAcademicImagePreview = closePreviewModal;
        }

        // Upload form feedback
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function () {
                const submitBtn = uploadForm.querySelector('button[type="submit"]');
                if (!submitBtn) return;

                submitBtn.disabled = true;
                submitBtn.style.pointerEvents = 'none';
                submitBtn.style.opacity = '0.92';
                submitBtn.innerHTML = `
                    <span style="display:inline-flex;align-items:center;gap:10px;">
                        <span style="
                            width:16px;
                            height:16px;
                            border:2px solid rgba(255,255,255,0.45);
                            border-top-color:#ffffff;
                            border-radius:50%;
                            display:inline-block;
                            animation: vgSpin .7s linear infinite;
                        "></span>
                        Uploading...
                    </span>
                `;
            });
        }

        // Accessibility
        const interactive = document.querySelectorAll('a, button, input, textarea');
        interactive.forEach(el => {
            el.addEventListener('focus', function () {
                el.style.outline = 'none';
                el.style.boxShadow = '0 0 0 3px rgba(99,102,241,0.18)';
            });

            el.addEventListener('blur', function () {
                el.style.boxShadow = '';
            });
        });

        // Escape closes modals
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;

            if (!document.getElementById('requestResponseModal').classList.contains('hidden')) {
                closeRequestResponseModal();
                return;
            }

            if (!document.getElementById('uploadModal').classList.contains('hidden')) {
                closeUploadModal();
                return;
            }

            if (!document.getElementById('videoModal').classList.contains('hidden')) {
                closeVideoModal();
                return;
            }

            if (window.closeAcademicImagePreview) {
                const preview = document.getElementById('academicImagePreviewModal');
                if (preview && preview.style.display === 'flex') {
                    window.closeAcademicImagePreview();
                }
            }
        });
    });

    function isAnySystemModalOpen() {
        const videoModal = document.getElementById('videoModal');
        const uploadModal = document.getElementById('uploadModal');
        const requestModal = document.getElementById('requestResponseModal');

        return (videoModal && !videoModal.classList.contains('hidden')) ||
               (uploadModal && !uploadModal.classList.contains('hidden')) ||
               (requestModal && !requestModal.classList.contains('hidden'));
    }

    function dismissAnnouncements() {
        const section = document.getElementById('announcementSection');
        if (!section) return;

        try {
            sessionStorage.setItem('academic_dashboard_announcements_dismissed', '1');
        } catch (e) {}

        section.style.pointerEvents = 'none';
        section.style.transition = 'opacity 0.28s ease, transform 0.28s ease, max-height 0.28s ease, margin 0.28s ease';
        section.style.maxHeight = section.offsetHeight + 'px';
        section.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(-10px)';
            section.style.maxHeight = '0px';
            section.style.marginBottom = '0px';
        });

        setTimeout(() => {
            section.remove();
        }, 300);
    }

    function openVideoModal(url) {
        const modal = document.getElementById('videoModal');
        const player = document.getElementById('videoPlayer');
        const source = document.getElementById('videoSource');

        source.src = url;
        player.load();
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        const panel = modal.querySelector('.theme-panel');
        if (panel) {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(24px) scale(0.985)';
            panel.style.transition = 'opacity 0.32s ease, transform 0.32s ease';

            requestAnimationFrame(() => {
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0) scale(1)';
            });
        }
    }

    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        const player = document.getElementById('videoPlayer');

        player.pause();
        modal.classList.add('hidden');

        if (!isAnySystemModalOpen()) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openUploadModal(actionUrl) {
        const modal = document.getElementById('uploadModal');
        const form = document.getElementById('uploadForm');

        form.action = actionUrl;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        const panel = modal.querySelector('.theme-panel');
        if (panel) {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(24px) scale(0.985)';
            panel.style.transition = 'opacity 0.32s ease, transform 0.32s ease';

            requestAnimationFrame(() => {
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0) scale(1)';
            });
        }
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');

        if (!isAnySystemModalOpen()) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openRequestResponseModal(actionUrl, title, requestType) {
        const modal = document.getElementById('requestResponseModal');
        const form = document.getElementById('requestResponseForm');
        const titleEl = document.getElementById('requestResponseModalTitle');
        const subtitleEl = document.getElementById('requestResponseModalSubtitle');

        form.action = actionUrl;

        const normalizedType = (requestType || '').toLowerCase();
        const isSystemVerification = normalizedType === 'system_verification' || normalizedType === 'verification';

        if (isSystemVerification) {
            currentRequestMode = 'system';
            titleEl.textContent = '{{ __('frontend.academic_dashboard.complete_system_verification_request') }}: ' + title;
            subtitleEl.textContent = '{{ __('frontend.academic_dashboard.provide_technical_details') }}';
            document.getElementById('normalRequestFields').classList.add('hidden');
            document.getElementById('systemVerificationFields').classList.remove('hidden');
        } else {
            currentRequestMode = 'normal';
            titleEl.textContent = '{{ __('frontend.academic_dashboard.send_response_to_supervisor') }}: ' + title;
            subtitleEl.textContent = '{{ __('frontend.academic_dashboard.submit_text_link_attachment') }}';
            document.getElementById('normalRequestFields').classList.remove('hidden');
            document.getElementById('systemVerificationFields').classList.add('hidden');
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        const panel = modal.querySelector('.theme-panel');
        if (panel) {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(24px) scale(0.985)';
            panel.style.transition = 'opacity 0.32s ease, transform 0.32s ease';

            requestAnimationFrame(() => {
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0) scale(1)';
            });
        }
    }

    function closeRequestResponseModal() {
        document.getElementById('requestResponseModal').classList.add('hidden');

        if (!isAnySystemModalOpen()) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    function submitRequestResponseForm() {
        const form = document.getElementById('requestResponseForm');
        const generatedText = document.getElementById('generated_response_text');
        const generatedLink = document.getElementById('generated_response_link');

        if (currentRequestMode === 'system') {
            const frontendUrl = document.getElementById('sv_frontend_url').value.trim();
            const backendUrl = document.getElementById('sv_backend_url').value.trim();
            const apiHealthUrl = document.getElementById('sv_api_health_url').value.trim();
            const adminPanelUrl = document.getElementById('sv_admin_panel_url').value.trim();
            const demoAccount = document.getElementById('sv_demo_account').value.trim();
            const demoPassword = document.getElementById('sv_demo_password').value.trim();
            const deploymentNotes = document.getElementById('sv_deployment_notes').value.trim();

            const importantFields = [
                frontendUrl,
                backendUrl,
                apiHealthUrl,
                adminPanelUrl,
                demoAccount,
                demoPassword
            ];

            const filledCount = importantFields.filter(v => v !== '').length;

            if (filledCount < 4) {
                alert('{{ __('frontend.academic_dashboard.fill_four_alert') }}');
                return;
            }

            generatedText.value =
`{{ __('frontend.academic_dashboard.system_verification_response') }}

{{ __('frontend.academic_dashboard.frontend_url') }}: ${frontendUrl || '{{ __('frontend.academic_dashboard.not_provided') }}'}
{{ __('frontend.academic_dashboard.backend_url') }}: ${backendUrl || '{{ __('frontend.academic_dashboard.not_provided') }}'}
{{ __('frontend.academic_dashboard.api_health_url') }}: ${apiHealthUrl || '{{ __('frontend.academic_dashboard.not_provided') }}'}
{{ __('frontend.academic_dashboard.admin_panel_url') }}: ${adminPanelUrl || '{{ __('frontend.academic_dashboard.not_provided') }}'}
{{ __('frontend.academic_dashboard.demo_account') }}: ${demoAccount || '{{ __('frontend.academic_dashboard.not_provided') }}'}
{{ __('frontend.academic_dashboard.demo_password') }}: ${demoPassword || '{{ __('frontend.academic_dashboard.not_provided') }}'}

{{ __('frontend.academic_dashboard.deployment_notes') }}:
${deploymentNotes || '{{ __('frontend.academic_dashboard.no_deployment_notes') }}'}`;

            generatedLink.value = frontendUrl || backendUrl || apiHealthUrl || adminPanelUrl || '';
        } else {
            const normalText = document.getElementById('normal_response_text').value.trim();
            const normalLink = document.getElementById('normal_response_link').value.trim();

            generatedText.value = normalText;
            generatedLink.value = normalLink;
        }

        const actionButtons = form.querySelectorAll('button[onclick="submitRequestResponseForm()"]');
        actionButtons.forEach(btn => {
            btn.disabled = true;
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.92';
            btn.innerHTML = `
                <span style="display:inline-flex;align-items:center;gap:10px;">
                    <span style="
                        width:16px;
                        height:16px;
                        border:2px solid rgba(255,255,255,0.45);
                        border-top-color:#ffffff;
                        border-radius:50%;
                        display:inline-block;
                        animation: vgSpin .7s linear infinite;
                    "></span>
                    Sending...
                </span>
            `;
        });

        form.submit();
    }
</script>

@endsection