@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen bg-theme-bg transition-colors duration-300 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 h-72 w-72 rounded-full blur-3xl opacity-20"
             style="background: radial-gradient(circle, var(--brand-accent) 0%, transparent 70%);"></div>
        <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full blur-3xl opacity-10"
             style="background: radial-gradient(circle, var(--brand-accent) 0%, transparent 70%);"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="theme-panel rounded-2xl shadow-brand-soft border border-theme-border/60 p-8 backdrop-blur-sm">

                <div class="text-center mb-8">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-accent/10 border border-brand-accent/20">
                        <i class="fas fa-key text-3xl text-brand-accent"></i>
                    </div>

                    <h2 class="text-3xl font-bold text-theme-text mb-2">
                        {{ __('frontend.auth.reset_password_title') }}
                    </h2>

                    <p class="text-theme-muted leading-relaxed">
                        {{ __('frontend.auth.reset_password_subtitle') }}
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-500">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST" id="resetPasswordForm" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token ?? '' }}">

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-theme-muted">
                            {{ __('frontend.auth.email') }}
                        </label>

                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-theme-muted">
                                <i class="fas fa-envelope"></i>
                            </span>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                value="{{ $email ?? old('email') }}"
                                readonly
                                class="w-full rounded-xl border border-theme-border bg-theme-surface-2 py-3 pl-11 pr-4 text-theme-text opacity-70 focus:border-brand-accent focus:ring-0"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-theme-muted">
                            {{ __('frontend.auth.new_password') }}
                        </label>

                        <div class="relative password-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-theme-border bg-theme-surface-2 py-3 pl-4 pr-12 text-theme-text focus:border-brand-accent focus:ring-0"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-theme-muted">
                            {{ __('frontend.auth.confirm_new_password') }}
                        </label>

                        <div class="relative password-wrapper">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-theme-border bg-theme-surface-2 py-3 pl-4 pr-12 text-theme-text focus:border-brand-accent focus:ring-0"
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-base font-semibold bg-brand-accent text-white hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft"
                    >
                        <i class="fas fa-rotate"></i>
                        <span>{{ __('frontend.auth.reset_password_button') }}</span>
                    </button>
                </form>

                <div class="mt-8 border-t border-theme-border/60 pt-6 text-center">
                    <a href="{{ route('login.show') }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-accent hover:underline">
                        <i class="fas fa-arrow-left"></i>
                        <span>{{ __('frontend.auth.back_to_login') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const panel = document.querySelector('.theme-panel');
    const form = document.getElementById('resetPasswordForm');
    const submitButton = form?.querySelector('button[type="submit"]');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    if (!document.getElementById('vg-reset-password-style')) {
        const style = document.createElement('style');
        style.id = 'vg-reset-password-style';
        style.textContent = `
            .vg-reveal {
                opacity: 0;
                transform: translateY(18px);
                transition: opacity .65s ease, transform .65s cubic-bezier(.22,1,.36,1);
            }

            .vg-reveal.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .vg-auth-panel {
                transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
            }

            .vg-auth-panel:hover {
                transform: translateY(-2px);
                box-shadow: 0 22px 52px rgba(0,0,0,.10);
            }

            .vg-auth-btn {
                transition: transform .22s ease, opacity .22s ease, box-shadow .22s ease;
            }

            .vg-auth-btn:hover {
                transform: translateY(-1px);
            }

            .vg-auth-btn.is-loading {
                pointer-events: none;
                opacity: .92;
            }

            .vg-field-ok {
                border-color: rgba(34,197,94,.45) !important;
            }

            .vg-field-mismatch {
                border-color: rgba(239,68,68,.45) !important;
                box-shadow: 0 0 0 4px rgba(239,68,68,.08);
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
                transition: opacity .2s ease, transform .2s ease;
            }

            .vg-password-toggle:hover {
                opacity: 1;
            }

            @media (prefers-reduced-motion: reduce) {
                .vg-reveal,
                .vg-auth-panel,
                .vg-auth-btn,
                .vg-password-toggle {
                    transition: none !important;
                    transform: none !important;
                    animation: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    const revealItems = [
        document.querySelector('.theme-panel .mx-auto'),
        document.querySelector('h2'),
        document.querySelector('h2 + p'),
        document.querySelector('form'),
        document.querySelector('.border-t')
    ].filter(Boolean);

    if (panel) panel.classList.add('vg-auth-panel');

    revealItems.forEach((el, index) => {
        el.classList.add('vg-reveal');

        if (prefersReducedMotion) {
            el.classList.add('is-visible');
            return;
        }

        setTimeout(() => el.classList.add('is-visible'), 100 + (index * 100));
    });

    function addPasswordToggle(input) {
        if (!input || input.dataset.toggleApplied === 'true') return;

        const wrapper = input.closest('.password-wrapper');
        if (!wrapper) return;

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

        confirmPasswordInput.classList.remove('vg-field-ok', 'vg-field-mismatch');

        if (!confirmPasswordInput.value) return;

        if (passwordInput.value === confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('vg-field-ok');
        } else {
            confirmPasswordInput.classList.add('vg-field-mismatch');
        }
    }

    passwordInput?.addEventListener('input', validatePasswords);
    confirmPasswordInput?.addEventListener('input', validatePasswords);

    if (submitButton) {
        submitButton.classList.add('vg-auth-btn');

        form?.addEventListener('submit', () => {
            submitButton.classList.add('is-loading');
            submitButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    {{ __('frontend.auth.reset_password_button') }}
                </span>
            `;
        });
    }
});
</script>
@endsection