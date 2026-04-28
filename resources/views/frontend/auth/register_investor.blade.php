@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-lg p-10 rounded-2xl theme-panel shadow-brand-soft">

        <div class="text-center mb-6">
            <i class="fas fa-hand-holding-usd text-5xl text-brand-accent mb-4 block"
               style="filter: drop-shadow(0 0 10px var(--brand-accent-glow));"></i>

            <h2 class="text-3xl font-bold text-theme-text">
                {{ __('frontend.auth.investor') }}
                <span class="text-brand-accent">{{ __('frontend.auth.registration') }}</span>
            </h2>

            <p class="text-theme-muted mt-2">
                {{ __('frontend.auth.investor_register_text') }}
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-400/40 text-red-500 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.investor.post') }}" class="space-y-4" id="investorRegisterForm">
            @csrf

            <div>
                <label class="block text-sm font-medium text-theme-muted mb-1">
                    {{ __('frontend.auth.full_name_entity_name') }}
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface-2 text-theme-text focus:ring-0 focus:border-brand-accent"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-theme-muted mb-1">
                    {{ __('frontend.auth.username') }}
                </label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autocomplete="username"
                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface-2 text-theme-text focus:ring-0 focus:border-brand-accent"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-theme-muted mb-1">
                    {{ __('frontend.auth.business_email') }}
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface-2 text-theme-text focus:ring-0 focus:border-brand-accent"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-theme-muted mb-1">
                    {{ __('frontend.auth.password') }}
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface-2 text-theme-text focus:ring-0 focus:border-brand-accent"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-theme-muted mb-1">
                    {{ __('frontend.auth.confirm_password') }}
                </label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full p-3 rounded-lg border border-theme-border bg-theme-surface-2 text-theme-text focus:ring-0 focus:border-brand-accent"
                >
            </div>

            <button
                type="submit"
                class="w-full inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold bg-brand-accent text-white hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft mt-4"
            >
                {{ __('frontend.auth.create_investor_account') }}
            </button>
        </form>

        <p class="mt-6 text-center text-theme-muted text-sm">
            {{ __('frontend.auth.already_have_account') }}
            <a href="{{ route('login.show') }}" class="text-brand-accent font-medium ml-1">
                {{ __('frontend.auth.log_in') }}
            </a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const panel = document.querySelector('.theme-panel');
    const heading = document.querySelector('h2');
    const subtitle = document.querySelector('h2 + p');
    const errorBox = document.querySelector('.bg-red-500\\/10');
    const form = document.getElementById('investorRegisterForm');
    const inputs = Array.from(document.querySelectorAll('input'));
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
    const submitButton = form?.querySelector('button[type="submit"]');

    if (!document.getElementById('vg-investor-register-style')) {
        const style = document.createElement('style');
        style.id = 'vg-investor-register-style';
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
                transition: box-shadow .28s ease, transform .28s ease;
            }

            .vg-auth-panel:hover {
                box-shadow: 0 22px 52px rgba(0,0,0,.10);
            }

            .vg-auth-input {
                transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
            }

            .vg-auth-input:focus {
                box-shadow: 0 0 0 4px rgba(99,102,241,.10);
            }

            .vg-field-ok {
                border-color: rgba(34,197,94,.45) !important;
            }

            .vg-field-mismatch {
                border-color: rgba(239,68,68,.45) !important;
                box-shadow: 0 0 0 4px rgba(239,68,68,.08);
            }

            .vg-auth-btn {
                transition: transform .22s ease, box-shadow .22s ease, opacity .22s ease;
            }

            .vg-auth-btn:hover {
                transform: translateY(-1px);
            }

            .vg-auth-btn.is-loading {
                pointer-events: none;
                opacity: .92;
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
                transition: opacity .2s ease, transform .2s ease;
            }

            .vg-password-toggle:hover {
                opacity: 1;
            }

            .vg-password-input {
                padding-right: 42px !important;
            }

            .vg-focus-ring:focus-visible {
                outline: none;
                box-shadow: 0 0 0 3px rgba(99,102,241,.16);
                border-radius: 12px;
            }

            @media (prefers-reduced-motion: reduce) {
                .vg-reveal,
                .vg-auth-panel,
                .vg-auth-input,
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

    [panel, heading, subtitle, errorBox, form].filter(Boolean).forEach((el, index) => {
        el.classList.add('vg-reveal');

        if (prefersReducedMotion) {
            el.classList.add('is-visible');
            return;
        }

        setTimeout(() => el.classList.add('is-visible'), 100 + (index * 110));
    });

    if (panel) panel.classList.add('vg-auth-panel');

    inputs.forEach((input, index) => {
        input.classList.add('vg-auth-input', 'vg-focus-ring');

        if (!prefersReducedMotion) {
            input.style.opacity = '0';
            input.style.transform = 'translateY(10px)';
            input.style.transition = 'opacity .5s ease, transform .5s ease, border-color .2s ease, box-shadow .2s ease';

            setTimeout(() => {
                input.style.opacity = '1';
                input.style.transform = 'translateY(0)';
            }, 220 + (index * 70));
        }
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
        submitButton.classList.add('vg-auth-btn', 'vg-focus-ring');

        form?.addEventListener('submit', () => {
            submitButton.classList.add('is-loading');
            submitButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    {{ __('frontend.auth.create_investor_account') }}
                </span>
            `;
        });
    }
});
</script>
@endsection