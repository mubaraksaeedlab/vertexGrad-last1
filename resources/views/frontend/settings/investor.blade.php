@extends('frontend.layouts.app')

@section('content')
@php
    $btnPrimaryClass = 'inline-flex items-center justify-center rounded-2xl px-8 py-4 font-black bg-brand-accent text-white hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft';
    $btnSecondaryClass = 'inline-flex items-center justify-center rounded-2xl px-6 py-3 font-bold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300';
@endphp

<div class="min-h-screen pt-28 pb-12 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="mb-10">
            <div class="relative overflow-hidden theme-panel rounded-[2.5rem] shadow-brand-soft">
                <div class="p-8 md:p-10">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-8">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-5 flex-wrap">
                                <span class="px-4 py-1.5 rounded-xl bg-brand-accent-soft text-brand-accent text-[10px] font-black uppercase tracking-[0.15em] border border-brand-accent">
                                    {{ __('frontend.investor.profile_settings_badge') }}
                                </span>
                                <span class="text-theme-muted text-xs font-mono">
                                    ID: INV-{{ $user->id + 5000 }}
                                </span>
                            </div>

                            <h1 class="text-3xl md:text-5xl font-black text-theme-text tracking-tight leading-[1.1]">
                                {{ __('frontend.investor.settings_title_before') }}
                                <span class="text-brand-accent">{{ __('frontend.investor.settings_title_highlight') }}</span>
                            </h1>

                            <p class="text-theme-muted mt-4 text-sm md:text-base leading-7 max-w-3xl">
                                {{ __('frontend.investor.subtitle') }}
                            </p>

                            <div class="mt-6 flex flex-wrap items-center gap-3">
                                <a href="{{ route('dashboard.investor') }}" class="{{ $btnSecondaryClass }}">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    {{ __('frontend.investor.back_to_dashboard') }}
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 xl:min-w-[320px]">
                            <div class="theme-panel-soft rounded-2xl p-5 text-center">
                                <div class="text-3xl font-black text-theme-text">
                                    {{ isset($myInvestments) ? $myInvestments->count() : 0 }}
                                </div>
                                <div class="text-xs uppercase tracking-[0.18em] font-black text-theme-muted mt-2">
                                    {{ __('frontend.investor.total_interactions') }}
                                </div>
                            </div>

                            <div class="theme-panel-soft rounded-2xl p-5 text-center">
                                <div class="text-3xl font-black text-theme-text">
                                    {{ $approvedInvestments ?? 0 }}
                                </div>
                                <div class="text-xs uppercase tracking-[0.18em] font-black text-theme-muted mt-2">
                                    {{ __('frontend.investor.approved_investments') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-1.5 w-full bg-theme-surface-2">
                    <div class="h-full bg-brand-accent w-1/2"></div>
                </div>
            </div>
        </header>


        @if(session('error'))
            <div class="mb-6">
                <div class="p-4 rounded-2xl border border-red-500/40 bg-red-500/10 text-red-600 shadow-brand-soft">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6">
                <div class="p-5 rounded-2xl border border-red-500/40 bg-red-500/10 text-red-600 shadow-brand-soft">
                    <div class="font-black uppercase tracking-[0.14em] text-xs mb-3">
                        {{ __('frontend.investor.fix_issues') }}
                    </div>
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('settings.investor.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <section class="theme-panel rounded-[2.5rem] p-6 md:p-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="lg:w-[320px] shrink-0">
                        <div class="theme-panel-soft rounded-[2rem] p-6 h-full">
                            <div class="flex items-center justify-between gap-3 mb-5">
                                <h2 class="text-lg font-black text-theme-text uppercase tracking-[0.16em]">
                                    {{ __('frontend.investor.profile_photo') }}
                                </h2>
                            </div>

                            <div class="flex flex-col items-center text-center">
                                <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-theme-border shadow-brand-soft bg-theme-surface-2">
                                    <img
                                        src="{{ !empty($user->profile_image) ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.png') }}"
                                        alt="{{ $user->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                </div>

                                <div class="mt-5 text-theme-text font-bold">
                                    {{ $user->name }}
                                </div>
                                <div class="text-sm text-theme-muted mt-1 break-all">
                                    {{ $user->email }}
                                </div>

                                <div class="w-full mt-6">
                                    <label class="block text-sm font-bold text-theme-text mb-2 text-left">
                                        {{ __('frontend.investor.choose_new_photo') }}
                                    </label>
                                    <input
                                        type="file"
                                        name="profile_image"
                                        accept="image/*"
                                        class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                    >
                                    <p class="text-xs text-theme-muted mt-2 text-left">
                                        {{ __('frontend.investor.photo_hint') }}
                                    </p>
                                    @error('profile_image')
                                        <p class="text-sm text-red-500 mt-2 text-left">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                            <h2 class="text-xl font-black text-theme-text uppercase tracking-[0.16em]">
                                {{ __('frontend.investor.contact_info') }}
                            </h2>
                            <span class="text-xs font-mono text-theme-muted">
                                {{ __('frontend.investor.contact_info_hint') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="theme-panel-soft rounded-2xl p-4">
                                <label class="block text-sm font-bold text-theme-text mb-2">
                                    {{ __('frontend.investor.full_name') }}
                                </label>
                                <input
                                    type="text"
                                    name="full_name"
                                    value="{{ old('full_name', $user->name) }}"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                >
                                @error('full_name')
                                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="theme-panel-soft rounded-2xl p-4">
                                <label class="block text-sm font-bold text-theme-text mb-2">
                                    {{ __('frontend.investor.email') }}
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                >
                                @error('email')
                                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="theme-panel-soft rounded-2xl p-4">
                                <label class="block text-sm font-bold text-theme-text mb-2">
                                    {{ __('frontend.investor.contact_name') }}
                                </label>
                                <input
                                    type="text"
                                    name="contact_name"
                                    value="{{ old('contact_name', $user->contact_name ?? '') }}"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                >
                                @error('contact_name')
                                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="theme-panel-soft rounded-2xl p-4">
                                <label class="block text-sm font-bold text-theme-text mb-2">
                                    {{ __('frontend.investor.phone') }}
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $user->phone ?? '') }}"
                                    class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                                >
                                @error('phone')
                                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="theme-panel rounded-[2.5rem] p-6 md:p-8">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                    <h2 class="text-xl font-black text-theme-text uppercase tracking-[0.16em]">
                        {{ __('frontend.investor.investment_focus') }}
                    </h2>
                    <span class="text-xs font-mono text-theme-muted">
                        {{ __('frontend.investor.investment_focus_hint') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="theme-panel-soft rounded-2xl p-4">
                        <label class="block text-sm font-bold text-theme-text mb-2">
                            {{ __('frontend.investor.fund_name') }}
                        </label>
                        <input
                            type="text"
                            name="fund_name"
                            value="{{ old('fund_name', $user->fund_name ?? '') }}"
                            class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                        >
                        @error('fund_name')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="theme-panel-soft rounded-2xl p-4">
                        <label class="block text-sm font-bold text-theme-text mb-2">
                            {{ __('frontend.investor.min_investment') }}
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="min_investment"
                            value="{{ old('min_investment', $user->min_investment ?? '') }}"
                            class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                        >
                        @error('min_investment')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="theme-panel-soft rounded-2xl p-4 md:col-span-2">
                        <label class="block text-sm font-bold text-theme-text mb-2">
                            {{ __('frontend.investor.investment_focus_label') }}
                        </label>
                        <textarea
                            name="investment_focus"
                            rows="5"
                            class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                            placeholder="{{ __('frontend.investor.investment_focus_placeholder') }}"
                        >{{ old('investment_focus', $user->investment_focus ?? '') }}</textarea>
                        @error('investment_focus')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="theme-panel rounded-[2.5rem] p-6 md:p-8">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                    <h2 class="text-xl font-black text-theme-text uppercase tracking-[0.16em]">
                        {{ __('frontend.investor.compliance') }}
                    </h2>
                    <span class="text-xs font-mono text-theme-muted">
                        {{ __('frontend.investor.compliance_hint') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="theme-panel-soft rounded-2xl p-5">
                        <div class="text-xs font-black uppercase tracking-[0.15em] text-theme-muted mb-2">
                            {{ __('frontend.investor.verification_status') }}
                        </div>
                        <div class="text-green-600 font-black text-lg">
                            {{ __('frontend.investor.verified') }}
                        </div>
                    </div>

                    <div class="theme-panel-soft rounded-2xl p-5">
                        <div class="text-xs font-black uppercase tracking-[0.15em] text-theme-muted mb-2">
                            {{ __('frontend.investor.city') }}
                        </div>
                        <input
                            type="text"
                            name="city"
                            value="{{ old('city', $user->city ?? '') }}"
                            class="w-full p-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text"
                        >
                        @error('city')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

<section class="theme-panel rounded-[2.5rem] p-6 md:p-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-brand-accent-soft border border-brand-accent flex items-center justify-center shrink-0 text-brand-accent">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3l7 3v5c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-3z"/>
                    <path d="M9.5 12l1.8 1.8L15 10"/>
                </svg>
            </div>

            <div class="min-w-0">
                <div class="text-xs font-black uppercase tracking-[0.15em] text-brand-accent mb-2">
                    {{ __('frontend.security.badge') }}
                </div>

                <h2 class="text-xl font-black text-theme-text uppercase tracking-[0.16em]">
                    {{ __('frontend.security.security_center') }}
                </h2>

                <p class="text-sm text-theme-muted mt-2 max-w-2xl leading-7">
                    {{ __('frontend.security.security_card_text_investor') }}
                </p>
            </div>
        </div>

        <div class="flex lg:justify-end">
            <a href="{{ route('security.index') }}" class="{{ $btnSecondaryClass }}">
                <i class="fas fa-arrow-up-right-from-square mr-2"></i>
                {{ __('frontend.security.open_security_center') }}
            </a>
        </div>
    </div>
</section>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                <a href="{{ route('dashboard.investor') }}" class="{{ $btnSecondaryClass }}">
                    {{ __('frontend.investor.cancel') }}
                </a>

                <button type="submit" class="{{ $btnPrimaryClass }}">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('frontend.investor.save_preferences') }}
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const headerPanel = document.querySelector('header .theme-panel');
    const alerts = Array.from(document.querySelectorAll('.mb-6 > div'));
    const form = document.querySelector('form');
    const sections = Array.from(document.querySelectorAll('form > section.theme-panel'));
    const statNumbers = Array.from(document.querySelectorAll('header .text-3xl.font-black.text-theme-text'));
    const inputs = Array.from(document.querySelectorAll('input[type="text"], input[type="email"], input[type="number"], input[type="file"], textarea'));
    const submitButton = form?.querySelector('button[type="submit"]');
    const cancelLink = form?.querySelector('a[href]');
    const profileImageInput = document.querySelector('input[name="profile_image"]');
    const profilePreview = document.querySelector('.w-32.h-32 img');
    const minInvestmentInput = document.querySelector('input[name="min_investment"]');
    const verificationCard = Array.from(document.querySelectorAll('.theme-panel-soft')).find(card =>
        card.textContent.includes('{{ __("frontend.investor.verification_status") }}') ||
        card.textContent.trim().length > 0
    );

    if (!document.getElementById('vg-investor-settings-style')) {
        const style = document.createElement('style');
        style.id = 'vg-investor-settings-style';
        style.textContent = `
            .vg-reveal {
                opacity: 0;
                transform: translateY(22px);
                transition: opacity .7s ease, transform .7s cubic-bezier(.22,1,.36,1);
            }

            .vg-reveal.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .vg-settings-panel {
                transition: box-shadow .28s ease, transform .28s ease, border-color .28s ease;
            }

            .vg-settings-panel:hover {
                box-shadow: 0 22px 52px rgba(0,0,0,.08);
            }

            .vg-settings-field {
                transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
            }

            .vg-settings-field:focus {
                box-shadow: 0 0 0 4px rgba(99,102,241,.10);
            }

            .vg-file-ready {
                border-color: rgba(34,197,94,.45) !important;
                box-shadow: 0 0 0 4px rgba(34,197,94,.08);
            }

            .vg-field-ok {
                border-color: rgba(34,197,94,.45) !important;
                box-shadow: 0 0 0 4px rgba(34,197,94,.08);
            }

            .vg-status-card {
                transition: box-shadow .22s ease, transform .22s ease, background-color .22s ease;
            }

            .vg-status-card:hover {
                background-color: rgba(34,197,94,.05);
            }

            .vg-submit-btn {
                transition: transform .22s ease, box-shadow .22s ease, opacity .22s ease;
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
                border-radius: 14px;
            }

            @media (prefers-reduced-motion: reduce) {
                .vg-reveal,
                .vg-settings-panel,
                .vg-settings-field,
                .vg-status-card,
                .vg-submit-btn {
                    transition: none !important;
                    transform: none !important;
                    animation: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    function animateCount(el, finalValue, duration = 1200) {
        if (prefersReducedMotion) {
            el.textContent = finalValue.toLocaleString();
            return;
        }

        const startTime = performance.now();

        function frame(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(finalValue * eased);
            el.textContent = current.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(frame);
            } else {
                el.textContent = finalValue.toLocaleString();
            }
        }

        requestAnimationFrame(frame);
    }

    [headerPanel, ...alerts, ...sections].filter(Boolean).forEach((el, index) => {
        el.classList.add('vg-reveal');

        if (el.classList.contains('theme-panel')) {
            el.classList.add('vg-settings-panel');
        }

        if (prefersReducedMotion) {
            el.classList.add('is-visible');
            return;
        }

        setTimeout(() => {
            el.classList.add('is-visible');
        }, 100 + (index * 110));
    });

    statNumbers.forEach((el, index) => {
        const value = parseInt(el.textContent.replace(/[^\d]/g, ''), 10);
        if (Number.isNaN(value)) return;

        el.textContent = '0';
        setTimeout(() => animateCount(el, value, 1100), 250 + (index * 140));
    });

    inputs.forEach(input => {
        input.classList.add('vg-settings-field', 'vg-focus-ring');
    });

    if (cancelLink) {
        cancelLink.classList.add('vg-focus-ring');
    }

    if (profileImageInput && profilePreview) {
        profileImageInput.addEventListener('change', event => {
            const file = event.target.files?.[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = e => {
                profilePreview.src = e.target.result;
                profileImageInput.classList.add('vg-file-ready');
            };
            reader.readAsDataURL(file);
        });
    }

    if (minInvestmentInput) {
        const validateMinInvestment = () => {
            const value = parseFloat(minInvestmentInput.value);
            minInvestmentInput.classList.remove('vg-field-ok');

            if (!Number.isNaN(value) && value >= 0) {
                minInvestmentInput.classList.add('vg-field-ok');
            }
        };

        minInvestmentInput.addEventListener('input', validateMinInvestment);
        validateMinInvestment();
    }

    const statusCards = Array.from(document.querySelectorAll('.theme-panel-soft'));
    statusCards.forEach(card => {
        if (card.textContent.includes('{{ __('frontend.investor.verified') }}')) {
            card.classList.add('vg-status-card');
        }
    });

    if (submitButton) {
        submitButton.classList.add('vg-submit-btn', 'vg-focus-ring');

        form?.addEventListener('submit', () => {
            submitButton.classList.add('is-loading');
            submitButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    {{ __('frontend.investor.save_preferences') }}
                </span>
            `;
        });
    }
});
</script>
@endsection