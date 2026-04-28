@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen pt-28 pb-12 bg-theme-bg transition-colors duration-300">
    <div class="{{ config('design.classes.container') }}">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-theme-text">
                    {{ __('frontend.investments.my') }} <span class="text-brand-accent">{{ __('frontend.investments.investments') }}</span>
                </h1>
                <p class="text-theme-muted mt-2">
                    {{ __('frontend.investments.subtitle') }}
                </p>
            </div>
        </div>

        <div class="theme-panel rounded-3xl overflow-hidden shadow-brand-soft">
            @if($projects->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-theme-surface-2 border-b border-theme-border">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-theme-muted">{{ __('frontend.investments.project') }}</th>
                                <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-theme-muted">{{ __('frontend.investments.student') }}</th>
                                <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-theme-muted">{{ __('frontend.investments.status') }}</th>
                                <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-theme-muted">{{ __('frontend.investments.amount') }}</th>
                                <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-theme-muted">{{ __('frontend.investments.date') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($projects as $project)
                                @php
                                    $status = strtolower($project->pivot->status ?? 'interested');
                                    $statusClasses = match($status) {
                                        'approved' => 'bg-green-500/10 text-green-600 border-green-500/20',
                                        'requested' => 'bg-yellow-500/10 text-yellow-700 border-yellow-500/20',
                                        'rejected' => 'bg-red-500/10 text-red-600 border-red-500/20',
                                        default => 'bg-brand-accent-soft text-brand-accent border-brand-accent/20',
                                    };
                                @endphp

                                <tr class="border-b border-theme-border last:border-b-0">
                                    <td class="px-6 py-5">
                                        <a href="{{ route('frontend.projects.show', $project) }}"
                                           class="font-bold text-theme-text hover:text-brand-accent transition">
                                            {{ $project->name }}
                                        </a>
                                    </td>

                                    <td class="px-6 py-5 text-theme-muted">
                                        {{ $project->student->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-black uppercase tracking-widest border {{ $statusClasses }}">
                                            {{ ucfirst($project->pivot->status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 font-bold text-theme-text">
                                        ${{ number_format($project->pivot->amount ?? 0) }}
                                    </td>

                                    <td class="px-6 py-5 text-theme-muted">
                                        {{ $project->pivot->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 mx-auto rounded-full bg-brand-accent-soft text-brand-accent flex items-center justify-center mb-4">
                        <i class="fas fa-briefcase text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-theme-text mb-2">{{ __('frontend.investments.no_investments_yet') }}</h3>
                    <p class="text-theme-muted mb-6">{{ __('frontend.investments.no_investments_text') }}</p>
                    <a href="{{ route('frontend.projects.index') }}"
                       class="inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold bg-brand-accent text-white hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft">
                        {{ __('frontend.investments.explore_projects') }}
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const pageHeader = document.querySelector('.flex.flex-col.md\\:flex-row.md\\:items-center.md\\:justify-between.gap-4.mb-8');
    const panel = document.querySelector('.theme-panel');
    const tableWrap = document.querySelector('.overflow-x-auto');
    const tableHead = document.querySelector('thead');
    const rows = Array.from(document.querySelectorAll('tbody tr'));
    const emptyState = document.querySelector('.p-12.text-center');
    const emptyButton = emptyState?.querySelector('a[href]');
    const projectLinks = document.querySelectorAll('tbody a[href]');

    if (!document.getElementById('vg-investments-ui-style')) {
        const style = document.createElement('style');
        style.id = 'vg-investments-ui-style';
        style.textContent = `
            .vg-reveal {
                opacity: 0;
                transform: translateY(18px);
                transition: opacity .7s ease, transform .7s cubic-bezier(.22,1,.36,1);
            }

            .vg-reveal.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .vg-row {
                transition:
                    background-color .22s ease,
                    box-shadow .22s ease,
                    border-color .22s ease;
            }

            .vg-row:hover {
                background-color: rgba(99, 102, 241, 0.035);
                box-shadow: inset 3px 0 0 rgba(99, 102, 241, 0.18);
            }

            .vg-link {
                transition: color .2s ease, opacity .2s ease;
            }

            .vg-link:hover {
                opacity: .92;
            }

            .vg-scroll-hint {
                transition: opacity .3s ease, transform .3s ease;
            }

            .vg-scroll-hint.is-hidden {
                opacity: 0;
                transform: translateY(-4px);
                pointer-events: none;
            }

            .vg-focus-ring:focus-visible {
                outline: none;
                box-shadow: 0 0 0 3px rgba(99,102,241,.16);
                border-radius: 12px;
            }

            @media (prefers-reduced-motion: reduce) {
                .vg-reveal,
                .vg-row,
                .vg-link,
                .vg-scroll-hint {
                    transition: none !important;
                    animation: none !important;
                    transform: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    function revealElements() {
        const elements = [pageHeader, panel, tableHead, emptyState].filter(Boolean);

        elements.forEach((el, index) => {
            el.classList.add('vg-reveal');

            if (prefersReducedMotion) {
                el.classList.add('is-visible');
                return;
            }

            setTimeout(() => {
                el.classList.add('is-visible');
            }, 100 + (index * 120));
        });

        rows.forEach((row, index) => {
            row.classList.add('vg-row', 'vg-reveal');

            if (prefersReducedMotion) {
                row.classList.add('is-visible');
                return;
            }

            setTimeout(() => {
                row.classList.add('is-visible');
            }, 260 + (index * 70));
        });
    }

    function animateValue(el, finalValue, prefix = '$', duration = 1100) {
        if (prefersReducedMotion) {
            el.textContent = prefix + finalValue.toLocaleString();
            return;
        }

        const start = performance.now();

        function frame(now) {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(finalValue * eased);
            el.textContent = prefix + current.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(frame);
            } else {
                el.textContent = prefix + finalValue.toLocaleString();
            }
        }

        requestAnimationFrame(frame);
    }

    function initAmountAnimation() {
        const amountCells = Array.from(document.querySelectorAll('tbody td.font-bold.text-theme-text'))
            .filter(cell => /^\$\d[\d,]*$/.test(cell.textContent.trim()));

        amountCells.forEach((cell, index) => {
            const value = parseInt(cell.textContent.replace(/[^\d]/g, ''), 10);
            if (Number.isNaN(value)) return;

            cell.textContent = '$0';

            setTimeout(() => {
                animateValue(cell, value);
            }, 350 + (index * 90));
        });
    }

    function initLinks() {
        projectLinks.forEach(link => {
            link.classList.add('vg-link', 'vg-focus-ring');
        });

        if (emptyButton) {
            emptyButton.classList.add('vg-focus-ring');
        }
    }

    function initMobileScrollHint() {
        if (!tableWrap || window.innerWidth >= 768) return;

        const hint = document.createElement('div');
        hint.className = 'vg-scroll-hint text-xs text-theme-muted px-4 pt-4';
        hint.innerHTML = `
            <span class="inline-flex items-center gap-2">
                <i class="fas fa-arrow-right text-brand-accent"></i>
                Scroll horizontally to view full table
            </span>
        `;

        tableWrap.parentNode.insertBefore(hint, tableWrap);

        const updateHint = () => {
            if (tableWrap.scrollLeft > 12) {
                hint.classList.add('is-hidden');
            } else {
                hint.classList.remove('is-hidden');
            }
        };

        updateHint();
        tableWrap.addEventListener('scroll', updateHint, { passive: true });
    }

    function initEmptyButtonFeedback() {
        if (!emptyButton) return;

        const originalHtml = emptyButton.innerHTML;

        emptyButton.addEventListener('click', () => {
            emptyButton.style.opacity = '0.9';
            emptyButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-arrow-right"></i>
                    Opening...
                </span>
            `;

            setTimeout(() => {
                emptyButton.style.opacity = '';
                emptyButton.innerHTML = originalHtml;
            }, 1200);
        });
    }

    revealElements();
    initAmountAnimation();
    initLinks();
    initMobileScrollHint();
    initEmptyButtonFeedback();
});
</script>
@endsection