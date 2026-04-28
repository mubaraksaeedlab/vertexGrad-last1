@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen bg-theme-bg transition-colors duration-300 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 h-72 w-72 rounded-full blur-3xl opacity-20"
             style="background: radial-gradient(circle, var(--brand-accent) 0%, transparent 70%);"></div>
        <div class="absolute bottom-0 right-0 h-64 w-64 rounded-full blur-3xl opacity-10"
             style="background: radial-gradient(circle, var(--brand-accent) 0%, transparent 70%);"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="theme-panel rounded-2xl shadow-brand-soft border border-theme-border/60 p-8 backdrop-blur-sm">

                <div class="text-center mb-8">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-accent/10 border border-brand-accent/20">
                        <i class="fas fa-lock text-3xl text-brand-accent"></i>
                    </div>

                    <h2 class="text-3xl font-bold text-theme-text mb-2">
                        {{ __('frontend.auth.forgot_password_title') }}
                    </h2>

                    <p class="text-theme-muted leading-relaxed">
                        {{ __('frontend.auth.forgot_password_subtitle') }}
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-green-400/30 bg-green-500/10 px-4 py-3 text-sm text-green-500">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-circle-check mt-0.5"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                @error('email')
                    <div class="mb-6 rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-500">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-triangle-exclamation mt-0.5"></i>
                            <span>{{ $message }}</span>
                        </div>
                    </div>
                @enderror

                <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm" class="space-y-5">
                    @csrf

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
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                class="w-full rounded-xl border border-theme-border bg-theme-surface-2 py-3 pl-11 pr-4 text-theme-text placeholder:text-theme-muted focus:border-brand-accent focus:ring-0"
                                placeholder="{{ __('frontend.auth.email') }}"
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-base font-semibold bg-brand-accent text-white hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft"
                    >
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ __('frontend.auth.send_reset_link') }}</span>
                    </button>
                </form>

                <div class="mt-8 border-t border-theme-border/60 pt-6 text-center">
                    <p class="text-sm text-theme-muted">
                        {{ __('frontend.auth.remember_password') }}
                        <a href="{{ route('login.show') }}" class="ml-1 font-medium text-brand-accent hover:underline">
                            {{ __('frontend.auth.log_in') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const panel = document.querySelector('.theme-panel');
    const form = document.getElementById('forgotPasswordForm');
    const submitButton = form?.querySelector('button[type="submit"]');
    const revealItems = [
        document.querySelector('.theme-panel .mx-auto'),
        document.querySelector('h2'),
        document.querySelector('h2 + p'),
        document.querySelector('form'),
        document.querySelector('.border-t')
    ].filter(Boolean);

    if (!document.getElementById('vg-forgot-password-style')) {
        const style = document.createElement('style');
        style.id = 'vg-forgot-password-style';
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

            @media (prefers-reduced-motion: reduce) {
                .vg-reveal,
                .vg-auth-panel,
                .vg-auth-btn {
                    transition: none !important;
                    transform: none !important;
                    animation: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    if (panel) panel.classList.add('vg-auth-panel');

    revealItems.forEach((el, index) => {
        el.classList.add('vg-reveal');

        if (prefersReducedMotion) {
            el.classList.add('is-visible');
            return;
        }

        setTimeout(() => el.classList.add('is-visible'), 100 + (index * 100));
    });

    if (submitButton) {
        submitButton.classList.add('vg-auth-btn');

        form?.addEventListener('submit', () => {
            submitButton.classList.add('is-loading');
            submitButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    {{ __('frontend.auth.send_reset_link') }}
                </span>
            `;
        });
    }
});
</script>
@endsection