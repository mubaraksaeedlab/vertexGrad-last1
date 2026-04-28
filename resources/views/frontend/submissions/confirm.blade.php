@php
    $projectData = session()->get('project_data', []);
    $userData = session()->get('user_data', []);

    $na = __('frontend.common.not_available');

    $transValue = function ($group, $value) use ($na) {
        if (empty($value)) {
            return $na;
        }

        $key = "frontend.{$group}.{$value}";
        $translated = __($key);

        return $translated === $key ? $value : $translated;
    };

    $transCommon = function ($value) use ($na) {
        if (empty($value)) {
            return $na;
        }

        $key = "frontend.common.{$value}";
        $translated = __($key);

        return $translated === $key ? $value : $translated;
    };

    $transSubmitStep1 = function ($prefix, $value) use ($na) {
        if (empty($value)) {
            return $na;
        }

        $key = "frontend.submit_step1.{$prefix}_{$value}";
        $translated = __($key);

        return $translated === $key ? $value : $translated;
    };

    $transSubmitStep2 = function ($prefix, $value) use ($na) {
        if (empty($value)) {
            return $na;
        }

        $key = "frontend.submit_step2.{$prefix}_{$value}";
        $translated = __($key);

        return $translated === $key ? $value : $translated;
    };

    $transSubmitStep3 = function ($prefix, $value) use ($na) {
        if (empty($value)) {
            return $na;
        }

        $key = "frontend.submit_step3.{$prefix}_{$value}";
        $translated = __($key);

        return $translated === $key ? $value : $translated;
    };
@endphp

@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-16 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-5xl mx-auto p-10 rounded-2xl theme-panel shadow-brand-soft">

        <div class="mb-8">
            <h3 class="text-xl font-semibold text-theme-text mb-2">{{ __('frontend.submit_review.step_title') }}</h3>
            <div class="h-2 bg-theme-surface-2 rounded-full overflow-hidden">
                <div class="h-full bg-brand-accent" style="width: 100%;"></div>
            </div>
        </div>

        <h2 class="text-4xl font-bold text-theme-text mb-2">{{ __('frontend.submit_review.page_title') }}</h2>
        <p class="text-lg text-theme-muted mb-10">
            {{ __('frontend.submit_review.page_subtitle') }}
        </p>

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl border border-red-500/40 bg-red-500/10 text-red-600">
                <strong class="block font-bold mb-1">{{ __('frontend.submit_review.error') }}</strong>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl alert-success-theme">
                <strong class="block font-bold mb-1">{{ __('frontend.submit_review.success') }}</strong>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl border border-yellow-500/40 bg-yellow-500/10 text-yellow-700">
                <strong class="block font-bold mb-1">{{ __('frontend.submit_review.validation') }}</strong>
                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('project.submit.final') }}" method="POST" class="space-y-8">
            @csrf

            <div class="space-y-6">
                <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-2xl font-semibold text-brand-accent">{{ __('frontend.submit_review.section1_title') }}</h4>
                        <a href="{{ route('project.submit.step1') }}" class="text-brand-accent text-sm hover:underline">{{ __('frontend.common.edit') }}</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-theme-text">
                        <p><strong>{{ __('frontend.submit_review.project_title') }}:</strong> {{ $projectData['project_title'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.discipline') }}:</strong> {{ $transSubmitStep1('discipline', $projectData['discipline'] ?? null) }}</p>
                        <p><strong>{{ __('frontend.submit_review.project_type') }}:</strong> {{ $transSubmitStep1('type', $projectData['project_type'] ?? null) }}</p>
                        <p><strong>{{ __('frontend.submit_review.project_nature') }}:</strong> {{ $transSubmitStep1('nature', $projectData['project_nature'] ?? null) }}</p>
                    </div>

                    <div class="mt-4 space-y-3 text-theme-text">
                        <p><strong>{{ __('frontend.submit_review.summary') }}:</strong> {{ $projectData['abstract'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.problem_statement') }}:</strong> {{ $projectData['problem_statement'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.target_beneficiaries') }}:</strong> {{ $projectData['target_beneficiaries'] ?? $na }}</p>
                    </div>
                </div>

                <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-2xl font-semibold text-brand-accent">{{ __('frontend.submit_review.section2_title') }}</h4>
                        <a href="{{ route('project.submit.step2') }}" class="text-brand-accent text-sm hover:underline">{{ __('frontend.common.edit') }}</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-theme-text">
                        <p><strong>{{ __('frontend.submit_review.student_name') }}:</strong> {{ $projectData['student_name'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.academic_level') }}:</strong> {{ $transSubmitStep2('level', $projectData['academic_level'] ?? null) }}</p>
                        <p><strong>{{ __('frontend.submit_review.supervisor_name') }}:</strong> {{ $projectData['supervisor_name'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.supervisor_title') }}:</strong> {{ $projectData['supervisor_title'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.university') }}:</strong> {{ $projectData['university_name'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.college') }}:</strong> {{ $projectData['college_name'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.department') }}:</strong> {{ $projectData['department'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.governorate') }}:</strong> {{ $transValue('governorates', $projectData['governorate'] ?? null) }}</p>
                    </div>
                </div>

                <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-2xl font-semibold text-brand-accent">{{ __('frontend.submit_review.section3_title') }}</h4>
                        <a href="{{ route('project.submit.step3') }}" class="text-brand-accent text-sm hover:underline">{{ __('frontend.common.edit') }}</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-theme-text">
                        <p><strong>{{ __('frontend.submit_review.project_feasibility') }}:</strong> {{ $transCommon($projectData['is_feasible'] ?? null) }}</p>
                        <p><strong>{{ __('frontend.submit_review.implementable_in_yemen') }}:</strong> {{ $transCommon($projectData['local_implementation'] ?? null) }}</p>
                        <p><strong>{{ __('frontend.submit_review.needs_funding') }}:</strong> {{ $transCommon($projectData['needs_funding'] ?? null) }}</p>
                        <p><strong>{{ __('frontend.submit_review.support_type') }}:</strong> {{ $transSubmitStep3('support', $projectData['support_type'] ?? null) }}</p>
                        <p><strong>{{ __('frontend.submit_review.estimated_funding') }}:</strong> ${{ number_format((float) ($projectData['requested_amount'] ?? 0)) }}</p>
                        <p><strong>{{ __('frontend.submit_review.estimated_duration') }}:</strong> {{ $projectData['duration_months'] ?? '0' }} {{ __('frontend.submit_review.months') }}</p>
                    </div>

                    <div class="mt-4 space-y-3 text-theme-text">
                        <p><strong>{{ __('frontend.submit_review.expected_impact') }}:</strong> {{ $projectData['expected_impact'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.community_benefit') }}:</strong> {{ $projectData['community_benefit'] ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.budget_plan') }}:</strong> {{ $projectData['budget_breakdown'] ?? $na }}</p>
                    </div>
                </div>

                <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-2xl font-semibold text-brand-accent">{{ __('frontend.submit_review.section4_title') }}</h4>
                        <a href="{{ route('project.submit.step4') }}" class="text-brand-accent text-sm hover:underline">{{ __('frontend.common.edit') }}</a>
                    </div>

                    <div class="space-y-3 text-theme-text">
                        <p><strong>{{ __('frontend.submit_review.account_email') }}:</strong> {{ $userData['email'] ?? auth()->user()->email ?? $na }}</p>
                        <p><strong>{{ __('frontend.submit_review.data_confirmation') }}:</strong> {{ !empty($userData['data_confirmation']) ? __('frontend.submit_review.confirmed') : __('frontend.submit_review.confirmed_previous_step') }}</p>
                        <p><strong>{{ __('frontend.submit_review.terms_agreement') }}:</strong> {{ !empty($userData['terms_agreement']) ? __('frontend.submit_review.accepted') : __('frontend.submit_review.accepted_previous_step') }}</p>
                    </div>
                </div>

                <div class="p-5 rounded-xl border border-brand-accent/30 bg-brand-accent-soft text-theme-text">
                    <h5 class="text-lg font-semibold text-brand-accent mb-2">{{ __('frontend.submit_review.important_note') }}</h5>
                    <p class="leading-7">
                        {!! __('frontend.submit_review.important_note_text') !!}
                    </p>
                </div>
            </div>

            <hr class="border-t border-theme-border my-8">

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('project.submit.step4') }}"
                   class="inline-flex items-center justify-center rounded-lg px-8 py-3 text-lg font-semibold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('frontend.common.back') }}
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg px-8 py-3 text-lg font-bold bg-green-600 text-white hover:bg-green-700 transition duration-300 shadow-brand-soft">
                    <i class="fas fa-shield-alt mr-2"></i> {{ __('frontend.submit_review.start_technical_scan') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const pagePanel = document.querySelector('.theme-panel');
    const progressLabel = document.querySelector('.mb-8 h3');
    const progressBar = document.querySelector('.mb-8 .bg-brand-accent');
    const heading = document.querySelector('h2');
    const subtitle = document.querySelector('h2 + p');
    const alerts = Array.from(document.querySelectorAll('.mb-6.p-4, .mb-6 > .p-4'));
    const form = document.querySelector('form');
    const sections = Array.from(document.querySelectorAll('form .border.border-theme-border'));
    const noteBox = document.querySelector('.border-brand-accent\\/30');
    const editLinks = Array.from(document.querySelectorAll('a[href*="project.submit.step"]'));
    const submitButton = form?.querySelector('button[type="submit"]');
    const backLink = form?.querySelector('a[href*="project.submit.step4"]');

    if (!document.getElementById('vg-submit-review-style')) {
        const style = document.createElement('style');
        style.id = 'vg-submit-review-style';
        style.textContent = `
            .vg-reveal {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity .68s ease, transform .68s cubic-bezier(.22,1,.36,1);
            }

            .vg-reveal.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .vg-section-card {
                transition: box-shadow .24s ease, border-color .24s ease, background-color .24s ease;
            }

            .vg-section-card:hover {
                box-shadow: 0 18px 42px rgba(0,0,0,.06);
                border-color: rgba(99,102,241,.16);
            }

            .vg-edit-link {
                transition: color .2s ease, opacity .2s ease, transform .2s ease;
            }

            .vg-edit-link:hover {
                transform: translateX(2px);
            }

            .vg-note-box {
                transition: box-shadow .24s ease, border-color .24s ease;
            }

            .vg-note-box:hover {
                box-shadow: 0 16px 36px rgba(99,102,241,.08);
            }

            .vg-submit-btn,
            .vg-back-link {
                transition: transform .22s ease, box-shadow .22s ease, opacity .22s ease;
            }

            .vg-submit-btn:hover,
            .vg-back-link:hover {
                transform: translateY(-1px);
            }

            .vg-submit-btn.is-loading {
                pointer-events: none;
                opacity: .92;
            }

            .vg-focus-ring:focus-visible {
                outline: none;
                box-shadow: 0 0 0 3px rgba(99,102,241,.16);
                border-radius: 12px;
            }

            @media (prefers-reduced-motion: reduce) {
                .vg-reveal,
                .vg-section-card,
                .vg-edit-link,
                .vg-note-box,
                .vg-submit-btn,
                .vg-back-link {
                    transition: none !important;
                    transform: none !important;
                    animation: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    [pagePanel, progressLabel, heading, subtitle, ...alerts, ...sections, noteBox].filter(Boolean).forEach((el, index) => {
        el.classList.add('vg-reveal');

        if (el.classList.contains('border') && el.classList.contains('bg-theme-surface-2')) {
            el.classList.add('vg-section-card');
        }

        if (el === noteBox) {
            el.classList.add('vg-note-box');
        }

        if (prefersReducedMotion) {
            el.classList.add('is-visible');
            return;
        }

        setTimeout(() => el.classList.add('is-visible'), 80 + (index * 90));
    });

    if (progressBar && !prefersReducedMotion) {
        progressBar.style.width = '0%';
        progressBar.style.transition = 'width 1s cubic-bezier(.22,1,.36,1)';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                progressBar.style.width = '100%';
            });
        });
    }

    editLinks.forEach(link => link.classList.add('vg-edit-link', 'vg-focus-ring'));

    if (backLink) backLink.classList.add('vg-back-link', 'vg-focus-ring');

    if (submitButton) {
        submitButton.classList.add('vg-submit-btn', 'vg-focus-ring');

        form?.addEventListener('submit', () => {
            submitButton.classList.add('is-loading');
            submitButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    {{ __('frontend.submit_review.start_technical_scan') }}
                </span>
            `;
        });
    }
});
</script>
@endsection