@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-10 pt-28 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-7xl mx-auto px-4">

        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('frontend.projects.show', $project) }}" class="text-brand-accent hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('frontend.investor_summary.back_to_project') }}
                </a>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('investor.projects.pitch-deck.download', $project) }}"
                   class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-brand-accent text-white font-bold hover:bg-brand-accent-strong transition">
                    <i class="fas fa-file-powerpoint mr-2"></i>
                    {{ __('frontend.investor_summary.download_powerpoint') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <span class="text-brand-accent font-bold uppercase tracking-widest text-xs">
                        {{ $summary['category'] ?: __('frontend.investor_summary.uncategorized') }}
                    </span>

                    <h1 class="text-4xl font-bold text-theme-text mt-2">
                        {{ $summary['title'] ?: __('frontend.investor_summary.project_summary') }}
                    </h1>

                    <div class="mt-8">
                        <h3 class="text-theme-text font-bold text-xl mb-4">
                            {{ __('frontend.investor_summary.executive_summary') }}
                        </h3>
                        <p class="text-theme-muted leading-relaxed italic text-lg">
                            "{{ $summary['description'] ?: __('frontend.investor_summary.not_available') }}"
                        </p>
                    </div>
                </div>

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <h3 class="text-theme-text font-bold text-xl mb-4">
                        {{ __('frontend.investor_summary.problem_statement') }}
                    </h3>
                    <p class="text-theme-muted leading-relaxed">
                        {{ $summary['problem_statement'] ?: __('frontend.investor_summary.not_available') }}
                    </p>
                </div>

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <h3 class="text-theme-text font-bold text-xl mb-4">
                        {{ __('frontend.investor_summary.target_beneficiaries') }}
                    </h3>
                    <p class="text-theme-muted leading-relaxed">
                        {{ $summary['target_beneficiaries'] ?: __('frontend.investor_summary.not_available') }}
                    </p>
                </div>

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <h3 class="text-theme-text font-bold text-xl mb-4">
                        {{ __('frontend.investor_summary.expected_impact') }}
                    </h3>
                    <p class="text-theme-muted leading-relaxed">
                        {{ $summary['expected_impact'] ?: __('frontend.investor_summary.not_available') }}
                    </p>
                </div>

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <h3 class="text-theme-text font-bold text-xl mb-4">
                        {{ __('frontend.investor_summary.community_benefit') }}
                    </h3>
                    <p class="text-theme-muted leading-relaxed">
                        {{ $summary['community_benefit'] ?: __('frontend.investor_summary.not_available') }}
                    </p>
                </div>

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <h3 class="text-theme-text font-bold text-xl mb-6">
                        {{ __('frontend.investor_summary.milestones') }}
                    </h3>

                    <div class="space-y-4">
                        @forelse($summary['milestones'] as $milestone)
                            <div class="p-5 rounded-2xl border border-theme-border bg-theme-surface-2">
                                <div class="font-bold text-theme-text">
                                    {{ $milestone['title'] }}
                                </div>
                                <div class="text-sm text-theme-muted mt-2">
                                    {{ __('frontend.investor_summary.month') }}: {{ $milestone['month'] ?: '-' }}
                                </div>
                            </div>
                        @empty
                            <p class="text-theme-muted italic">
                                {{ __('frontend.investor_summary.no_milestones') }}
                            </p>
                        @endforelse
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <p class="text-theme-muted text-xs uppercase font-bold mb-1">
                        {{ __('frontend.investor_summary.estimated_budget') }}
                    </p>

                    <h2 class="text-4xl font-black text-green-600">
                        ${{ is_numeric($summary['budget']) ? number_format($summary['budget']) : '0' }}
                    </h2>

                    <div class="mt-6 pt-6 border-t border-theme-border space-y-4">
                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.student') }}:</span>
                            <span class="text-brand-accent font-bold text-right">{{ $summary['student_name'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.academic_level') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['academic_level'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.supervisor') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['supervisor_name'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.university') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['university_name'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.department') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['department'] ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <h3 class="text-theme-text font-bold text-xl mb-4">
                        {{ __('frontend.investor_summary.funding_overview') }}
                    </h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.needs_funding') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['needs_funding'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.duration_months') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['duration_months'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.support_type') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['support_type'] ?: '-' }}</span>
                        </div>

                        <div>
                            <div class="text-theme-muted mb-2">{{ __('frontend.investor_summary.budget_breakdown') }}:</div>
                            <div class="text-theme-text font-semibold leading-relaxed">
                                {{ $summary['budget_breakdown'] ?: __('frontend.investor_summary.not_available') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <h3 class="text-theme-text font-bold text-xl mb-4">
                        {{ __('frontend.investor_summary.readiness') }}
                    </h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.scanner_status') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['scanner_status'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.scan_score') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['scan_score'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.final_decision') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['final_decision'] ?: '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.approved_reviews') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['approved_reviews_count'] ?? 0 }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.approved_investments') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $summary['approved_investments_count'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                    <h3 class="text-theme-text font-bold text-xl mb-4">
                        {{ __('frontend.investor_summary.latest_deck') }}
                    </h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.status') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $latestDeck->status ?? __('frontend.investor_summary.not_generated_yet') }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.version') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ $latestDeck->version ?? '-' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-theme-muted">{{ __('frontend.investor_summary.generated_at') }}:</span>
                            <span class="text-theme-text font-bold text-right">{{ optional($latestDeck?->generated_at)->format('Y-m-d H:i') ?: '-' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // =====================================
    // Unified style system
    // =====================================
    if (!document.getElementById('vg-unified-motion-style')) {
        const style = document.createElement('style');
        style.id = 'vg-unified-motion-style';
        style.innerHTML = `
            @keyframes vgSpin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
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

            .vg-reveal-up {
                opacity: 0;
                transform: translateY(42px);
                transition: opacity 1.15s ease, transform 1.15s cubic-bezier(0.22, 1, 0.36, 1);
                will-change: opacity, transform;
            }

            .vg-reveal-left {
                opacity: 0;
                transform: translateX(-42px);
                transition: opacity 1.15s ease, transform 1.15s cubic-bezier(0.22, 1, 0.36, 1);
                will-change: opacity, transform;
            }

            .vg-reveal-right {
                opacity: 0;
                transform: translateX(42px);
                transition: opacity 1.15s ease, transform 1.15s cubic-bezier(0.22, 1, 0.36, 1);
                will-change: opacity, transform;
            }

            .vg-visible {
                opacity: 1 !important;
                transform: translate(0, 0) scale(1) !important;
            }
        `;
        document.head.appendChild(style);
    }

    // =====================================
    // Reading progress
    // =====================================
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

    // =====================================
    // Main structure
    // =====================================
    const topBar = document.querySelector('.mb-6.flex');
    const title = document.querySelector('h1');
    const grid = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-3.gap-8');
    const leftColumn = grid ? grid.children[0] : null;
    const rightColumn = grid ? grid.children[1] : null;

    const panels = Array.from(document.querySelectorAll('.theme-panel'));
    const milestoneCards = Array.from(document.querySelectorAll('.theme-panel .p-5.rounded-2xl.border'));

    // =====================================
    // Calm page-load reveal
    // =====================================
    if (!prefersReducedMotion) {
        if (topBar) topBar.classList.add('vg-reveal-up');
        if (title) title.classList.add('vg-reveal-up');
        if (leftColumn) leftColumn.classList.add('vg-reveal-left');
        if (rightColumn) rightColumn.classList.add('vg-reveal-right');

        setTimeout(() => topBar && topBar.classList.add('vg-visible'), 120);
        setTimeout(() => title && title.classList.add('vg-visible'), 320);
        setTimeout(() => leftColumn && leftColumn.classList.add('vg-visible'), 620);
        setTimeout(() => rightColumn && rightColumn.classList.add('vg-visible'), 860);

        panels.forEach((panel, index) => {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(34px)';
            panel.style.transition = 'opacity 1.05s ease, transform 1.05s cubic-bezier(0.22, 1, 0.36, 1)';

            setTimeout(() => {
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0)';
            }, 700 + (index * 180));
        });

        milestoneCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(26px) scale(0.988)';
            card.style.transition = 'opacity 0.95s ease, transform 0.95s cubic-bezier(0.22, 1, 0.36, 1)';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            }, 1500 + (index * 180));
        });
    }

    // =====================================
    // Unified hover polish
    // =====================================
    panels.forEach(panel => {
        panel.style.transition = 'transform 0.32s ease, box-shadow 0.32s ease';

        panel.addEventListener('mouseenter', function () {
            if (prefersReducedMotion) return;
            panel.style.transform = 'translateY(-6px)';
            panel.style.boxShadow = '0 22px 48px rgba(0,0,0,0.09)';
        });

        panel.addEventListener('mouseleave', function () {
            panel.style.transform = 'translateY(0)';
            panel.style.boxShadow = '';
        });
    });

    milestoneCards.forEach(card => {
        card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease';

        card.addEventListener('mouseenter', function () {
            if (prefersReducedMotion) return;
            card.style.transform = 'translateY(-4px)';
            card.style.boxShadow = '0 16px 32px rgba(0,0,0,0.07)';
            card.style.borderColor = 'rgba(99,102,241,0.22)';
        });

        card.addEventListener('mouseleave', function () {
            card.style.transform = '';
            card.style.boxShadow = '';
            card.style.borderColor = '';
        });
    });

    // =====================================
    // Unified number animation
    // =====================================
    function animateValue(el, finalValue, prefix = '', duration = 1500) {
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
        const numericEls = Array.from(document.querySelectorAll('h2, span')).filter(el => {
            const text = el.textContent.trim();
            return /^[$]?\d[\d,]*$/.test(text);
        });

        numericEls.forEach((el, index) => {
            const text = el.textContent.trim();
            const hasDollar = text.startsWith('$');
            const value = parseInt(text.replace(/[^\d]/g, ''), 10);

            if (isNaN(value)) return;

            el.textContent = hasDollar ? '$0' : '0';

            setTimeout(() => {
                animateValue(el, value, hasDollar ? '$' : '', hasDollar ? 1700 : 1450);
            }, 1200 + (index * 120));
        });
    }

    // =====================================
    // Unified download feedback
    // =====================================
    const downloadButton = Array.from(document.querySelectorAll('a[href]')).find(link =>
        link.href.includes('pitch-deck') || link.href.includes('download')
    );

    if (downloadButton) {
        const originalHTML = downloadButton.innerHTML;

        downloadButton.addEventListener('click', function () {
            downloadButton.style.pointerEvents = 'none';
            downloadButton.style.opacity = '0.92';
            downloadButton.innerHTML = `
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
                    Preparing...
                </span>
            `;

            setTimeout(() => {
                downloadButton.style.pointerEvents = '';
                downloadButton.style.opacity = '';
                downloadButton.innerHTML = originalHTML;
            }, 2200);
        });
    }

    // =====================================
    // Unified focus style
    // =====================================
    const interactive = document.querySelectorAll('a, button');
    interactive.forEach(el => {
        el.addEventListener('focus', function () {
            el.style.outline = 'none';
            el.style.boxShadow = '0 0 0 3px rgba(99,102,241,0.18)';
        });

        el.addEventListener('blur', function () {
            el.style.boxShadow = '';
        });
    });
});
</script>
@endsection