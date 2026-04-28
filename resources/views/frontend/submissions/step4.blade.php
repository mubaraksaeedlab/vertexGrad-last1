@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-16 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-4xl mx-auto p-10 rounded-2xl theme-panel shadow-brand-soft">

        <div class="mb-8">
            <h3 class="text-xl font-semibold text-theme-text mb-2">{{ __('frontend.submit_step4.step_title') }}</h3>
            <div class="h-2 bg-theme-surface-2 rounded-full overflow-hidden">
                <div class="h-full bg-brand-accent" style="width: 80%;"></div>
            </div>
        </div>

        <h2 class="text-4xl font-bold text-theme-text mb-2">{{ __('frontend.submit_step4.page_title') }}</h2>
        <p class="text-lg text-theme-muted mb-10">
            {{ __('frontend.submit_step4.page_subtitle') }}
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

        <form action="{{ route('project.submit.step4.post') }}" method="POST" class="space-y-8">
            @csrf

            <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                <h4 class="text-2xl font-semibold text-brand-accent mb-4">{{ __('frontend.submit_step4.account_information') }}</h4>
                <p class="text-sm text-theme-muted mb-6">
                    {{ __('frontend.submit_step4.account_information_text') }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step4.email') }} <span class="text-brand-accent">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            value="{{ old('email', session('user_data.email')) }}"
                            placeholder="{{ __('frontend.submit_step4.email_placeholder') }}"
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step4.password') }} <span class="text-brand-accent">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-theme-muted mb-2">
                            {{ __('frontend.submit_step4.confirm_password') }} <span class="text-brand-accent">*</span>
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface text-theme-text focus:ring-0 focus:border-brand-accent"
                        >
                    </div>
                </div>
            </div>

            <div class="border border-theme-border p-6 rounded-lg bg-theme-surface-2">
                <h4 class="text-2xl font-semibold text-brand-accent mb-4">{{ __('frontend.submit_step4.submission_confirmation') }}</h4>

                <div class="space-y-4">
                    <label class="flex items-start text-theme-text">
                        <input
                            type="checkbox"
                            name="data_confirmation"
                            value="1"
                            required
                            class="form-checkbox h-5 w-5 mt-1 text-brand-accent border-theme-border bg-theme-surface rounded focus:ring-brand-accent"
                        >
                        <span class="ml-3 text-sm leading-6">
                            {{ __('frontend.submit_step4.data_confirmation_text') }}
                        </span>
                    </label>

                    <label class="flex items-start text-theme-text">
                        <input
                            type="checkbox"
                            name="terms_agreement"
                            value="1"
                            required
                            class="form-checkbox h-5 w-5 mt-1 text-brand-accent border-theme-border bg-theme-surface rounded focus:ring-brand-accent"
                        >
                        <span class="ml-3 text-sm leading-6">
                            {!! __('frontend.submit_step4.terms_agreement_text') !!}
                        </span>
                    </label>
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <a
                    href="{{ route('project.submit.step3') }}"
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
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const checkboxes = Array.from(document.querySelectorAll('input[type="checkbox"]'));
    const backLink = form?.querySelector('a[href*="step3"]');
    const submitButton = form?.querySelector('button[type="submit"]');

    if (!document.getElementById('vg-submit-step4-style')) {
        const style = document.createElement('style');
        style.id = 'vg-submit-step4-style';
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

            .vg-field.is-ok {
                border-color: rgba(34,197,94,.42);
            }

            .vg-field.is-mismatch {
                border-color: rgba(239,68,68,.45);
                box-shadow: 0 0 0 4px rgba(239,68,68,.08);
            }

            .vg-check-row {
                transition: background-color .2s ease, box-shadow .2s ease, border-radius .2s ease;
            }

            .vg-check-row.is-checked {
                background-color: rgba(99,102,241,.05);
                box-shadow: inset 0 0 0 1px rgba(99,102,241,.16);
                border-radius: 14px;
            }

            .vg-password-wrap {
                position: relative;
            }

            .vg-password-toggle {
                position: absolute;
                right: 14px;
                top: 50%;
                transform: translateY(-50%);
                background: transparent;
                border: 0;
                color: inherit;
                cursor: pointer;
                opacity: .75;
                transition: opacity .2s ease;
            }

            .vg-password-toggle:hover {
                opacity: 1;
            }

            .vg-password-input {
                padding-right: 42px !important;
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
                .vg-check-row,
                .vg-password-toggle,
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
                progressBar.style.width = '80%';
            });
        });
    }

    [emailInput, passwordInput, confirmPasswordInput].filter(Boolean).forEach(field => {
        field.classList.add('vg-field', 'vg-focus-ring');
    });

    function addPasswordToggle(input) {
        if (!input || input.dataset.toggleApplied === 'true') return;

        const wrapper = document.createElement('div');
        wrapper.className = 'vg-password-wrap';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        input.classList.add('vg-password-input');

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'vg-password-toggle';
        toggle.innerHTML = '<i class="fas fa-eye"></i>';
        wrapper.appendChild(toggle);

        toggle.addEventListener('click', () => {
            const icon = toggle.querySelector('i');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            if (icon) {
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            }
        });

        input.dataset.toggleApplied = 'true';
    }

    addPasswordToggle(passwordInput);
    addPasswordToggle(confirmPasswordInput);

    function validatePasswords() {
        if (!passwordInput || !confirmPasswordInput) return;

        confirmPasswordInput.classList.remove('is-ok', 'is-mismatch');

        if (!confirmPasswordInput.value) return;

        if (passwordInput.value === confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('is-ok');
        } else {
            confirmPasswordInput.classList.add('is-mismatch');
        }
    }

    passwordInput?.addEventListener('input', validatePasswords);
    confirmPasswordInput?.addEventListener('input', validatePasswords);

    checkboxes.forEach(checkbox => {
        const label = checkbox.closest('label');
        if (!label) return;

        label.classList.add('vg-check-row', 'vg-focus-ring');

        const syncState = () => {
            label.classList.toggle('is-checked', checkbox.checked);
        };

        syncState();
        checkbox.addEventListener('change', syncState);
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