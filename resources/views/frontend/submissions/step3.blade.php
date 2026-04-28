@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-16 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-4xl mx-auto p-10 rounded-2xl theme-panel shadow-brand-soft">

        <div class="mb-8">
            <h3 class="text-xl font-semibold text-theme-text mb-2">{{ __('frontend.submit_step3.step_title') }}</h3>
            <div class="h-2 bg-theme-surface-2 rounded-full overflow-hidden">
                <div class="h-full bg-brand-accent" style="width: 60%;"></div>
            </div>
        </div>

        <h2 class="text-4xl font-bold text-theme-text mb-2">{{ __('frontend.submit_step3.page_title') }}</h2>
        <p class="text-lg text-theme-muted mb-10">
            {{ __('frontend.submit_step3.page_subtitle') }}
        </p>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/40 text-red-600 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('project.submit.step3.post') }}" method="POST" class="space-y-8">
            @csrf

            <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                <h4 class="text-2xl font-semibold text-brand-accent mb-4">{{ __('frontend.submit_step3.feasibility_overview') }}</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="is_feasible" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step3.is_feasible') }} <span class="text-brand-accent">*</span>
                        </label>
                        <select
                            id="is_feasible"
                            name="is_feasible"
                            required
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                            <option value="" disabled {{ old('is_feasible', session('project_data.is_feasible')) ? '' : 'selected' }}>{{ __('frontend.submit_step3.select_option') }}</option>
                            <option value="yes" {{ old('is_feasible', session('project_data.is_feasible')) == 'yes' ? 'selected' : '' }}>{{ __('frontend.common.yes') }}</option>
                            <option value="partially" {{ old('is_feasible', session('project_data.is_feasible')) == 'partially' ? 'selected' : '' }}>{{ __('frontend.common.partially') }}</option>
                            <option value="no" {{ old('is_feasible', session('project_data.is_feasible')) == 'no' ? 'selected' : '' }}>{{ __('frontend.common.no') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="local_implementation" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step3.local_implementation') }} <span class="text-brand-accent">*</span>
                        </label>
                        <select
                            id="local_implementation"
                            name="local_implementation"
                            required
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                            <option value="" disabled {{ old('local_implementation', session('project_data.local_implementation')) ? '' : 'selected' }}>{{ __('frontend.submit_step3.select_option') }}</option>
                            <option value="yes" {{ old('local_implementation', session('project_data.local_implementation')) == 'yes' ? 'selected' : '' }}>{{ __('frontend.common.yes') }}</option>
                            <option value="partially" {{ old('local_implementation', session('project_data.local_implementation')) == 'partially' ? 'selected' : '' }}>{{ __('frontend.common.partially') }}</option>
                            <option value="no" {{ old('local_implementation', session('project_data.local_implementation')) == 'no' ? 'selected' : '' }}>{{ __('frontend.common.no') }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="expected_impact" class="block text-sm font-medium text-theme-muted mb-2">
                        {{ __('frontend.submit_step3.expected_impact') }} <span class="text-brand-accent">*</span>
                    </label>
                    <textarea
                        id="expected_impact"
                        name="expected_impact"
                        required
                        rows="4"
                        placeholder="{{ __('frontend.submit_step3.expected_impact_placeholder') }}"
                        class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                    >{{ old('expected_impact', session('project_data.expected_impact')) }}</textarea>
                </div>

                <div class="mt-6">
                    <label for="community_benefit" class="block text-sm font-medium text-theme-muted mb-2">
                        {{ __('frontend.submit_step3.community_benefit') }} <span class="text-brand-accent">*</span>
                    </label>
                    <textarea
                        id="community_benefit"
                        name="community_benefit"
                        required
                        rows="4"
                        placeholder="{{ __('frontend.submit_step3.community_benefit_placeholder') }}"
                        class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                    >{{ old('community_benefit', session('project_data.community_benefit')) }}</textarea>
                </div>
            </div>

            <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                <h4 class="text-2xl font-semibold text-brand-accent mb-4">{{ __('frontend.submit_step3.funding_resources') }}</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="needs_funding" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step3.needs_funding') }} <span class="text-brand-accent">*</span>
                        </label>
                        <select
                            id="needs_funding"
                            name="needs_funding"
                            required
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                            <option value="" disabled {{ old('needs_funding', session('project_data.needs_funding')) ? '' : 'selected' }}>{{ __('frontend.submit_step3.select_option') }}</option>
                            <option value="yes" {{ old('needs_funding', session('project_data.needs_funding')) == 'yes' ? 'selected' : '' }}>{{ __('frontend.common.yes') }}</option>
                            <option value="no" {{ old('needs_funding', session('project_data.needs_funding')) == 'no' ? 'selected' : '' }}>{{ __('frontend.common.no') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="requested_amount" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step3.requested_amount') }} <span class="text-brand-accent">*</span>
                        </label>
                        <input
                            type="number"
                            id="requested_amount"
                            name="requested_amount"
                            required
                            min="0"
                            step="100"
                            value="{{ old('requested_amount', session('project_data.requested_amount')) }}"
                            placeholder="{{ __('frontend.submit_step3.requested_amount_placeholder') }}"
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                    </div>

                    <div>
                        <label for="duration_months" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step3.duration_months') }} <span class="text-brand-accent">*</span>
                        </label>
                        <input
                            type="number"
                            id="duration_months"
                            name="duration_months"
                            required
                            min="1"
                            max="60"
                            value="{{ old('duration_months', session('project_data.duration_months')) }}"
                            placeholder="{{ __('frontend.submit_step3.duration_months_placeholder') }}"
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                    </div>

                    <div>
                        <label for="support_type" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step3.support_type') }} <span class="text-brand-accent">*</span>
                        </label>
                        <select
                            id="support_type"
                            name="support_type"
                            required
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                            <option value="" disabled {{ old('support_type', session('project_data.support_type')) ? '' : 'selected' }}>{{ __('frontend.submit_step3.select_support_type') }}</option>
                            <option value="financial" {{ old('support_type', session('project_data.support_type')) == 'financial' ? 'selected' : '' }}>{{ __('frontend.submit_step3.support_financial') }}</option>
                            <option value="technical" {{ old('support_type', session('project_data.support_type')) == 'technical' ? 'selected' : '' }}>{{ __('frontend.submit_step3.support_technical') }}</option>
                            <option value="partnership" {{ old('support_type', session('project_data.support_type')) == 'partnership' ? 'selected' : '' }}>{{ __('frontend.submit_step3.support_partnership') }}</option>
                            <option value="incubation" {{ old('support_type', session('project_data.support_type')) == 'incubation' ? 'selected' : '' }}>{{ __('frontend.submit_step3.support_incubation') }}</option>
                            <option value="mixed" {{ old('support_type', session('project_data.support_type')) == 'mixed' ? 'selected' : '' }}>{{ __('frontend.submit_step3.support_mixed') }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="budget_breakdown" class="block text-sm font-medium text-theme-muted mb-2">
                        {{ __('frontend.submit_step3.budget_breakdown') }} <span class="text-brand-accent">*</span>
                    </label>
                    <textarea
                        id="budget_breakdown"
                        name="budget_breakdown"
                        required
                        rows="4"
                        placeholder="{{ __('frontend.submit_step3.budget_breakdown_placeholder') }}"
                        class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                    >{{ old('budget_breakdown', session('project_data.budget_breakdown')) }}</textarea>
                </div>
            </div>

            <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                <h4 class="text-2xl font-semibold text-brand-accent mb-4">{{ __('frontend.submit_step3.execution_milestones') }}</h4>
                <p class="text-sm text-theme-muted mb-4">
                    {{ __('frontend.submit_step3.execution_milestones_text') }}
                </p>

                <div class="space-y-4">
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div class="md:col-span-4">
                                <label for="milestone_{{ $i }}" class="block text-sm font-medium text-theme-muted mb-2">
                                    {{ __('frontend.submit_step3.milestone') }} {{ $i }} <span class="text-brand-accent">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="milestone_{{ $i }}"
                                    name="milestone_{{ $i }}"
                                    required
                                    value="{{ old('milestone_'.$i, session('project_data.milestone_'.$i)) }}"
                                    placeholder="{{ __('frontend.submit_step3.milestone_placeholder') }}"
                                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                                >
                            </div>

                            <div class="md:col-span-1">
                                <label for="milestone_{{ $i }}_month" class="block text-sm font-medium text-theme-muted mb-2">
                                    {{ __('frontend.submit_step3.month') }} <span class="text-brand-accent">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="milestone_{{ $i }}_month"
                                    name="milestone_{{ $i }}_month"
                                    required
                                    min="1"
                                    max="60"
                                    value="{{ old('milestone_'.$i.'_month', session('project_data.milestone_'.$i.'_month')) }}"
                                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                                >
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <a
                    href="{{ route('project.submit.step2') }}"
                    class="inline-flex items-center justify-center rounded-lg px-8 py-3 text-lg font-semibold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300"
                >
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('frontend.common.back') }}
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg px-8 py-3 text-lg font-semibold bg-brand-accent text-white hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft"
                >
                    {{ __('frontend.common.save_continue') }} <i class="fas fa-arrow-right ml-2"></i>
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
    const errorBox = document.querySelector('.bg-red-500\\/10');
    const form = document.querySelector('form');
    const cards = Array.from(document.querySelectorAll('form .border.border-theme-border'));
    const fields = Array.from(document.querySelectorAll('input, select, textarea'));
    const requestedAmountInput = document.getElementById('requested_amount');
    const durationInput = document.getElementById('duration_months');
    const milestoneMonthInputs = Array.from(document.querySelectorAll('input[id^="milestone_"][id$="_month"]'));
    const backLink = form?.querySelector('a[href*="step2"]');
    const submitButton = form?.querySelector('button[type="submit"]');

    if (!document.getElementById('vg-submit-step3-style')) {
        const style = document.createElement('style');
        style.id = 'vg-submit-step3-style';
        style.textContent = `
            .vg-reveal {
                opacity: 0;
                transform: translateY(18px);
                transition: opacity .66s ease, transform .66s cubic-bezier(.22,1,.36,1);
            }

            .vg-reveal.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .vg-card {
                transition: box-shadow .22s ease, border-color .22s ease;
            }

            .vg-card:hover {
                box-shadow: 0 16px 36px rgba(0,0,0,.05);
                border-color: rgba(99,102,241,.14);
            }

            .vg-field {
                transition: border-color .2s ease, box-shadow .2s ease;
            }

            .vg-field:focus {
                box-shadow: 0 0 0 4px rgba(99,102,241,.10);
            }

            .vg-field.is-valid {
                border-color: rgba(34,197,94,.42);
            }

            .vg-field.is-warning {
                border-color: rgba(245,158,11,.45);
                box-shadow: 0 0 0 4px rgba(245,158,11,.08);
            }

            .vg-inline-note {
                margin-top: 8px;
                font-size: 12px;
                color: var(--theme-muted, #6b7280);
            }

            .vg-btn,
            .vg-back-link {
                transition: transform .22s ease, opacity .22s ease, box-shadow .22s ease;
            }

            .vg-btn:hover,
            .vg-back-link:hover {
                transform: translateY(-1px);
            }

            .vg-btn.is-loading {
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
                .vg-card,
                .vg-field,
                .vg-btn,
                .vg-back-link {
                    transition: none !important;
                    transform: none !important;
                    animation: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    [pagePanel, progressLabel, heading, subtitle, errorBox, ...cards].filter(Boolean).forEach((el, index) => {
        el.classList.add('vg-reveal');

        if (cards.includes(el)) el.classList.add('vg-card');

        if (prefersReducedMotion) {
            el.classList.add('is-visible');
            return;
        }

        setTimeout(() => el.classList.add('is-visible'), 80 + (index * 90));
    });

    if (progressBar && !prefersReducedMotion) {
        progressBar.style.width = '0%';
        progressBar.style.transition = 'width .95s cubic-bezier(.22,1,.36,1)';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                progressBar.style.width = '60%';
            });
        });
    }

    fields.forEach(field => {
        field.classList.add('vg-field', 'vg-focus-ring');

        const syncState = () => {
            const value = (field.value || '').trim();
            field.classList.toggle('is-valid', value.length > 0);
        };

        syncState();
        field.addEventListener('input', syncState);
        field.addEventListener('change', syncState);
    });

    function addNote(input, formatter) {
        if (!input) return null;
        const note = document.createElement('div');
        note.className = 'vg-inline-note';
        input.insertAdjacentElement('afterend', note);

        const update = () => {
            note.textContent = formatter(input.value);
        };

        update();
        input.addEventListener('input', update);
        return note;
    }

addNote(requestedAmountInput, value => {
    const number = parseFloat(value);
    if (Number.isNaN(number) || value === '') {
        return @json(__('frontend.submit_step3.enter_estimated_amount'));
    }

    return @json(__('frontend.submit_step3.estimated_funding')) + ': $' + number.toLocaleString();
});

addNote(durationInput, value => {
    const number = parseInt(value, 10);
    if (Number.isNaN(number) || value === '') {
        return @json(__('frontend.submit_step3.set_expected_duration'));
    }

    return @json(__('frontend.submit_step3.estimated_duration')) + ': ' + number + ' ' + @json(__('frontend.submit_step3.months'));
});

    milestoneMonthInputs.forEach(input => {
        const validateMonth = () => {
            const value = parseInt(input.value, 10);
            input.classList.remove('is-warning');

            if (!Number.isNaN(value) && durationInput?.value) {
                const duration = parseInt(durationInput.value, 10);
                if (!Number.isNaN(duration) && value > duration) {
                    input.classList.add('is-warning');
                }
            }
        };

        input.addEventListener('input', validateMonth);
        durationInput?.addEventListener('input', validateMonth);
        validateMonth();
    });

    if (backLink) backLink.classList.add('vg-back-link', 'vg-focus-ring');

    if (submitButton) {
        submitButton.classList.add('vg-btn', 'vg-focus-ring');

        form?.addEventListener('submit', () => {
            submitButton.classList.add('is-loading');
            submitButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    {{ __('frontend.common.save_continue') }}
                </span>
            `;
        });
    }
});
</script>
@endsection