@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-lg p-10 rounded-2xl theme-panel shadow-brand-soft">

        <div class="text-center mb-8">
            <i class="fas fa-satellite-dish text-5xl text-brand-accent mb-4 block"
               style="filter: drop-shadow(0 0 10px var(--brand-accent-glow));"></i>
        </div>

        <h2 class="text-4xl font-bold text-center text-theme-text mb-2">
            {{ __('frontend.auth.sign_in_to') }} <span class="text-brand-accent">{{ __('frontend.auth.vertexgrad') }}</span>
        </h2>

        <p class="text-center text-theme-muted mb-10">
            {{ __('frontend.auth.login_subtitle') }}
        </p>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-400/40 text-red-500 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-theme-muted mb-2">
                    {{ __('frontend.auth.username_or_email') }}
                </label>
                <input
                    type="text"
                    name="login_id"
                    value="{{ old('login_id') }}"
                    required
                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface-2 text-theme-text placeholder:text-theme-muted focus:ring-0 focus:border-brand-accent"
                >
            </div>

            <div class="relative">
                <label class="block text-sm font-medium text-theme-muted mb-2">
                    {{ __('frontend.auth.password') }}
                </label>
                <input
                    type="password"
                    id="loginPassword"
                    name="password"
                    required
                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface-2 text-theme-text placeholder:text-theme-muted pr-10 focus:ring-0 focus:border-brand-accent"
                >
                <button
                    type="button"
                    id="passwordToggle"
                    class="absolute right-3 top-10 text-theme-muted hover:text-brand-accent transition"
                >
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-theme-muted cursor-pointer">
                    <input
                        type="checkbox"
                        name="remember"
                        class="mr-2 rounded border-theme-border bg-theme-surface-2 text-brand-accent focus:ring-brand-accent"
                    >
                    {{ __('frontend.auth.remember_me') }}
                </label>

                <a href="{{ route('password.request') }}" class="text-sm text-brand-accent hover:underline">
                    {{ __('frontend.auth.forgot_password_short') }}
                </a>
            </div>

            <button
                type="submit"
                class="w-full inline-flex items-center justify-center rounded-lg px-6 py-3 text-lg font-semibold bg-brand-accent text-white hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft"
            >
                {{ __('frontend.auth.log_in') }}
            </button>
        </form>

        <p class="mt-8 text-center text-theme-muted text-sm">
            {{ __('frontend.auth.no_account') }}
            <a href="{{ route('register.show') }}" class="text-brand-accent underline">
                {{ __('frontend.auth.register_here') }}
            </a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const panel = document.querySelector('.theme-panel');
    const iconTop = document.querySelector('.text-center i');
    const heading = document.querySelector('h2');
    const subtitle = document.querySelector('h2 + p');
    const errorBox = document.querySelector('.bg-red-500\\/10');
    const form = document.querySelector('form');
    const submitButton = form?.querySelector('button[type="submit"]');
    const passwordInput = document.getElementById('loginPassword');
    const passwordToggle = document.getElementById('passwordToggle');

    if (!document.getElementById('vg-login-style')) {
        const style = document.createElement('style');
        style.id = 'vg-login-style';
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

            .vg-login-panel {
                transition: box-shadow .28s ease, transform .28s ease;
            }

            .vg-login-panel:hover {
                box-shadow: 0 22px 52px rgba(0,0,0,.10);
            }

            .vg-login-input {
                transition:
                    border-color .2s ease,
                    box-shadow .2s ease,
                    background-color .2s ease;
            }

            .vg-login-input:focus {
                box-shadow: 0 0 0 4px rgba(99,102,241,.10);
            }

            .vg-password-toggle {
                transition: color .2s ease, transform .2s ease;
            }

            .vg-password-toggle:hover {
                transform: scale(1.06);
            }

            .vg-submit-btn {
                transition:
                    transform .22s ease,
                    box-shadow .22s ease,
                    background-color .22s ease,
                    opacity .22s ease;
            }

            .vg-submit-btn:hover {
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
                .vg-login-panel,
                .vg-login-input,
                .vg-password-toggle,
                .vg-submit-btn {
                    transition: none !important;
                    animation: none !important;
                    transform: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    const revealItems = [panel, iconTop, heading, subtitle, errorBox, form].filter(Boolean);

    revealItems.forEach((el, index) => {
        el.classList.add('vg-reveal');

        if (prefersReducedMotion) {
            el.classList.add('is-visible');
            return;
        }

        setTimeout(() => {
            el.classList.add('is-visible');
        }, 100 + (index * 110));
    });

    if (panel) {
        panel.classList.add('vg-login-panel');
    }

    document.querySelectorAll('input, select').forEach(el => {
        el.classList.add('vg-login-input', 'vg-focus-ring');
    });

    document.querySelectorAll('button, a').forEach(el => {
        el.classList.add('vg-focus-ring');
    });

    if (passwordToggle) {
        passwordToggle.classList.add('vg-password-toggle');

        passwordToggle.addEventListener('click', () => {
            if (!passwordInput) return;

            const icon = passwordToggle.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                passwordInput.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });
    }

    if (submitButton) {
        submitButton.classList.add('vg-submit-btn');

        form?.addEventListener('submit', () => {
            submitButton.classList.add('is-loading');
            submitButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    {{ __('frontend.auth.log_in') }}
                </span>
            `;
        });
    }
});
</script>
@endsection