@extends('frontend.layouts.app')

@section('content')
@php
    $btnSecondaryClass = 'inline-flex items-center justify-center rounded-2xl px-6 py-3 font-bold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300';
@endphp

<div class="min-h-screen pt-28 pb-12 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="mb-12 flex flex-col md:flex-row md:justify-between md:items-end gap-6">
            <div>
                <h1 class="text-5xl font-black text-theme-text tracking-tighter uppercase">
                    {{ __('frontend.investor_dashboard.investor') }}
                    <span class="text-brand-accent">{{ __('frontend.investor_dashboard.dashboard') }}</span>
                </h1>

                <p class="text-theme-muted text-xs font-bold tracking-[0.3em] mt-2 uppercase">
                    {{ __('frontend.investor_dashboard.subtitle') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('settings.investor') }}" class="{{ $btnSecondaryClass }}">
                    <i class="fas fa-cog mr-2"></i>
                    {{ __('frontend.investor_dashboard.settings', [], app()->getLocale()) }}
                </a>

                <a href="{{ route('investor.investments') }}" class="{{ $btnSecondaryClass }}">
                    <i class="fas fa-briefcase mr-2"></i>
                    {{ __('frontend.investments.my_investments', [], app()->getLocale()) }}
                </a>
            </div>
        </header>

        <div class="mb-8 md:hidden">
            <div class="theme-panel rounded-[2rem] p-5">
                <p class="text-theme-muted text-[10px] font-black uppercase tracking-widest">
                    {{ __('frontend.investor_dashboard.total_approved_funding') }}
                </p>
                <p class="text-3xl font-black text-green-600 mt-2">
                    ${{ number_format($totalDeployed) }}
                </p>
            </div>
        </div>

        <div class="hidden md:flex justify-end mb-8">
            <div class="text-right">
                <p class="text-theme-muted text-[10px] font-black uppercase tracking-widest">
                    {{ __('frontend.investor_dashboard.total_approved_funding') }}
                </p>
                <p class="text-3xl font-black text-green-600 mt-1">
                    ${{ number_format($totalDeployed) }}
                </p>
            </div>
        </div>

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
                            <div class="hidden sm:flex w-14 h-14 rounded-2xl border border-brand-accent bg-brand-accent-soft text-brand-accent items-center justify-center shrink-0">
                                <i class="fas fa-bullhorn text-lg"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-theme-surface-2 text-theme-muted border border-theme-border text-[11px] font-black uppercase tracking-[0.16em]">
                                        {{ __('frontend.investor_dashboard.announcement') }}
                                    </span>

                                    @if($featuredAnnouncement->is_pinned)
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-400/10 text-amber-500 border border-amber-400/20 text-[11px] font-black uppercase tracking-[0.16em]">
                                            <i class="fas fa-thumbtack text-[10px]"></i>
                                            {{ __('frontend.investor_dashboard.pinned') }}
                                        </span>
                                    @endif

                                    @if($featuredAnnouncement->expires_at)
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-red-500/10 text-red-500 border border-red-500/20 text-[11px] font-black uppercase tracking-[0.16em]">
                                            <i class="fas fa-hourglass-half text-[10px]"></i>
                                            {{ __('frontend.investor_dashboard.until') }} {{ $featuredAnnouncement->expires_at->format('M d, Y • h:i A') }}
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

                            <button type="button"
                                    onclick="dismissAnnouncements()"
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
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-400/10 text-amber-500 border border-amber-400/20 shrink-0">
                                                        <i class="fas fa-thumbtack text-[11px]"></i>
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-theme-muted text-sm leading-6">
                                                {{ \Illuminate\Support\Str::limit($announcement->body, 160) }}
                                            </p>

                                            @if($announcement->expires_at)
                                                <div class="mt-3 text-[11px] text-red-500 font-semibold">
                                                    {{ __('frontend.investor_dashboard.visible_until') }} {{ $announcement->expires_at->format('M d, Y • h:i A') }}
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2 space-y-8">

                {{-- MY INTERESTED / APPROVED PROJECTS --}}
                <section class="theme-panel p-10 rounded-[3rem]">
                    <h3 class="text-xl font-black text-theme-text mb-8 flex items-center uppercase tracking-widest">
                        <i class="fas fa-briefcase mr-4 text-brand-accent"></i>
                        {{ __('frontend.investor_dashboard.my_investment_activity') }}
                    </h3>

                    <div class="space-y-4">
                        @forelse($myInvestments as $investment)
                            @php
                                $image = $investment->getFirstMediaUrl('images');
                                $video = $investment->getFirstMediaUrl('videos');
                                $pivotStatus = $investment->pivot->status ?? 'interested';
                            @endphp

                            <a href="{{ route('frontend.projects.show', $investment) }}"
                               class="p-6 bg-theme-surface-2 rounded-2xl border border-theme-border flex items-center justify-between hover:border-brand-accent/40 transition-all">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-theme-surface flex items-center justify-center shrink-0">
                                        @if($image)
                                            <img src="{{ $image }}" alt="{{ $investment->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-theme-muted text-lg"></i>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <h4 class="text-theme-text font-bold truncate">
                                            {{ $investment->name }}
                                        </h4>

                                        <p class="text-xs text-theme-muted mt-1 truncate">
                                            {{ __('frontend.investor_dashboard.lead') }}:
                                            {{ $investment->student?->name ?? __('frontend.investor_dashboard.not_available') }}
                                        </p>

                                        <div class="flex items-center gap-3 mt-2 flex-wrap">
                                            @if($video)
                                                <span class="text-xs text-brand-accent flex items-center gap-1">
                                                    <i class="fas fa-video"></i>
                                                    {{ __('frontend.investor_dashboard.video_available') }}
                                                </span>
                                            @endif

                                            @if($investment->getMedia('images')->count() > 1)
                                                <span class="text-xs text-theme-muted">
                                                    {{ $investment->getMedia('images')->count() }} {{ __('frontend.investor_dashboard.images') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right shrink-0 ml-4">
                                    <span class="text-brand-accent font-bold text-sm">
                                        {{ strtoupper($pivotStatus) }}
                                    </span>

                                    <p class="text-[10px] text-theme-muted uppercase mt-1">
                                        {{ __('frontend.investor_dashboard.budget') }}:
                                        ${{ number_format($investment->budget ?? 0) }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-10 text-theme-muted italic text-sm">
                                {{ __('frontend.investor_dashboard.no_interest_yet') }}
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- SMART RECOMMENDATIONS --}}
                <section class="theme-panel p-10 rounded-[3rem]">
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-theme-text uppercase tracking-widest">
                            {{ __('frontend.investor_dashboard.suggested_for_you') }}
                        </h3>
                        <p class="text-sm text-theme-muted mt-2">
                            Personalized opportunities based on your recent interests and investment activity.
                        </p>
                    </div>

                    <div class="space-y-4">
                        @forelse($suggestedProjects as $deal)
                            @php
                                $image = $deal->getFirstMediaUrl('images');
                                $video = $deal->getFirstMediaUrl('videos');
                                $interestedCount = $deal->investors->count();
                                $interestedUsers = $deal->investors->take(3);
                            @endphp

                            <a href="{{ route('frontend.projects.show', $deal) }}"
                               class="flex items-center justify-between p-6 bg-theme-surface-2 rounded-2xl border border-theme-border hover:border-brand-accent/40 transition-all group">

                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-theme-surface flex items-center justify-center shrink-0">
                                        @if($image)
                                            <img src="{{ $image }}" alt="{{ $deal->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-theme-muted text-lg"></i>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-theme-text font-semibold group-hover:text-brand-accent transition-colors truncate">
                                            {{ $deal->name }}
                                        </div>

                                        <div class="flex items-center gap-3 mt-2 flex-wrap">
                                            <span class="text-xs text-theme-muted truncate">
                                                {{ $deal->student?->name ?? __('frontend.investor_dashboard.researcher') }}
                                            </span>

                                            @if($video)
                                                <span class="text-xs text-brand-accent flex items-center gap-1">
                                                    <i class="fas fa-video"></i>
                                                    {{ __('frontend.investor_dashboard.video') }}
                                                </span>
                                            @endif

                                            <span class="text-xs text-theme-muted uppercase">
                                                {{ $deal->status }}
                                            </span>
                                        </div>

                                        @if($interestedCount > 0)
                                            <div class="flex items-center gap-2 mt-3">
                                                <div class="flex -space-x-2">
                                                    @foreach($interestedUsers as $investor)
                                                        <div class="w-7 h-7 rounded-full bg-brand-accent-soft border border-brand-accent text-brand-accent flex items-center justify-center text-[10px] font-black">
                                                            {{ strtoupper(substr($investor->name ?? 'I', 0, 1)) }}
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <span class="text-xs text-theme-muted">
                                                    {{ $interestedCount }}
                                                    {{ __('frontend.investor_dashboard.interested_investor_label', ['count' => $interestedCount]) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right shrink-0 ml-4">
                                    <span class="text-green-600 font-mono text-sm font-bold block">
                                        ${{ number_format($deal->budget ?? 0) }}
                                    </span>
                                    <span class="text-[10px] text-theme-muted uppercase tracking-wider mt-1 block">
                                        Matched for you
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-10 text-theme-muted italic text-sm">
                                {{ __('frontend.investor_dashboard.no_open_opportunities') }}
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6 pt-6 border-t border-theme-border">
                        <a href="{{ route('frontend.projects.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl px-6 py-3 font-bold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300">
                            <i class="fas fa-compass mr-2"></i>
                            {{ __('frontend.investor_dashboard.explore_all') }}
                        </a>
                    </div>
                </section>
            </div>

            <div class="space-y-8">
                <div class="theme-panel p-10 rounded-[3rem]">
                    <h3 class="text-theme-text font-black uppercase tracking-widest text-sm mb-6">
                        {{ __('frontend.investor_dashboard.marketplace_overview') }}
                    </h3>

                    <div class="space-y-5 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-theme-muted">{{ __('frontend.investor_dashboard.active_projects') }}</span>
                            <span class="text-brand-accent font-bold">{{ $marketplaceStats['active_projects'] }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-theme-muted">{{ __('frontend.investor_dashboard.interested') }}</span>
                            <span class="text-theme-text font-bold">{{ $marketplaceStats['interested_count'] }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-theme-muted">{{ __('frontend.investor_dashboard.funding_requested') }}</span>
                            <span class="text-theme-text font-bold">{{ $marketplaceStats['requested_count'] }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-theme-muted">{{ __('frontend.investor_dashboard.approved_investments') }}</span>
                            <span class="text-green-600 font-bold">{{ $marketplaceStats['approved_count'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="theme-panel p-10 rounded-[3rem]">
                    <h3 class="text-theme-text font-black uppercase tracking-widest text-sm mb-6">
                        {{ __('frontend.investor_dashboard.investor_summary') }}
                    </h3>

                    <div class="space-y-4 text-xs text-theme-muted leading-relaxed">
                        <p>
                            <span class="text-brand-accent">{{ __('frontend.investor_dashboard.focus') }}:</span>
                            {{ __('frontend.investor_dashboard.focus_text') }}
                        </p>

                        <p>
                            <span class="text-brand-accent">{{ __('frontend.investor_dashboard.status') }}:</span>
                            {{ __('frontend.investor_dashboard.status_text') }}
                        </p>

                        <p>
                            <span class="text-brand-accent">{{ __('frontend.investor_dashboard.action') }}:</span>
                            {{ __('frontend.investor_dashboard.action_text') }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function dismissAnnouncements() {
        const section = document.getElementById('announcementSection');
        if (!section) return;

        try {
            sessionStorage.setItem('investor_dashboard_announcements_dismissed', '1');
        } catch (e) {}

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (prefersReducedMotion) {
            section.remove();
            return;
        }

        section.style.pointerEvents = 'none';
        section.style.overflow = 'hidden';
        section.style.transition = 'opacity 0.28s ease, transform 0.28s ease, max-height 0.28s ease, margin 0.28s ease';
        section.style.maxHeight = section.offsetHeight + 'px';

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

    document.addEventListener('DOMContentLoaded', function () {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        try {
            const dismissed = sessionStorage.getItem('investor_dashboard_announcements_dismissed');
            const announcementSection = document.getElementById('announcementSection');
            if (dismissed === '1' && announcementSection) {
                announcementSection.remove();
            }
        } catch (e) {}

        if (!prefersReducedMotion) {
            const animatedElements = [
                document.querySelector('header'),
                document.getElementById('announcementSection'),
                ...document.querySelectorAll('.theme-panel')
            ].filter(Boolean);

            animatedElements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(24px) scale(0.985)';
                el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';

                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0) scale(1)';
                }, 120 + (index * 120));
            });
        }

        const projectCards = document.querySelectorAll('a[href].p-6, a[href].group');
        projectCards.forEach(card => {
            card.style.transition = 'transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease';

            card.addEventListener('mouseenter', () => {
                if (prefersReducedMotion) return;
                card.style.transform = 'translateY(-4px)';
                card.style.boxShadow = '0 16px 36px rgba(0,0,0,0.08)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = 'none';
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                const section = document.getElementById('announcementSection');
                if (section) dismissAnnouncements();
            }
        });

        if (!prefersReducedMotion) {
            const numberElements = Array.from(document.querySelectorAll('span, p')).filter(el => {
                const text = el.textContent.trim();
                return /^[$]?\d[\d,]*$/.test(text);
            });

            numberElements.forEach((el, index) => {
                const originalText = el.textContent.trim();
                const hasDollar = originalText.startsWith('$');
                const numericValue = parseInt(originalText.replace(/[^\d]/g, ''), 10);

                if (isNaN(numericValue)) return;

                el.textContent = hasDollar ? '$0' : '0';

                setTimeout(() => {
                    const duration = 1000;
                    const startTime = performance.now();

                    function animate(currentTime) {
                        const progress = Math.min((currentTime - startTime) / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        const current = Math.floor(numericValue * eased);

                        el.textContent = (hasDollar ? '$' : '') + current.toLocaleString();

                        if (progress < 1) {
                            requestAnimationFrame(animate);
                        } else {
                            el.textContent = (hasDollar ? '$' : '') + numericValue.toLocaleString();
                        }
                    }

                    requestAnimationFrame(animate);
                }, 250 + (index * 80));
            });
        }

        const interactiveItems = document.querySelectorAll('a[href], button');
        interactiveItems.forEach(item => {
            item.addEventListener('focus', () => {
                item.style.outline = 'none';
                item.style.boxShadow = '0 0 0 3px rgba(99, 102, 241, 0.18)';
            });

            item.addEventListener('blur', () => {
                item.style.boxShadow = '';
            });
        });
    });
</script>
@endsection