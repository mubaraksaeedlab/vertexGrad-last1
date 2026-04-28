@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-20 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="text-center mb-16">
            <h1 class="text-6xl font-extrabold text-theme-text mb-4">
                {{ __('frontend.process.title_before') }}
                <span class="text-brand-accent">{{ __('frontend.process.title_highlight') }}</span>
            </h1>
            <p class="text-xl text-theme-muted max-w-3xl mx-auto">
                {{ __('frontend.process.subtitle') }}
            </p>
        </header>

        <div class="space-y-12 relative">
            <div class="absolute left-1/2 w-0.5 bg-brand-accent h-full transform -translate-x-1/2 opacity-30"></div>

            <div class="relative flex justify-start md:justify-around items-center">
                <div class="hidden md:block w-5/12"></div>
                <div class="absolute w-8 h-8 rounded-full bg-brand-accent border-4 border-theme-bg z-10 left-1/2 transform -translate-x-1/2 flex items-center justify-center text-white font-bold">1</div>
                <div class="w-full md:w-5/12 bg-theme-surface-2/70 p-6 rounded-xl border border-brand-accent/30 shadow-lg ml-10 md:ml-0 md:mr-10">
                    <h3 class="text-2xl font-semibold text-theme-text mb-2">{{ __('frontend.process.step1_title') }}</h3>
                    <p class="text-theme-muted">
                        {{ __('frontend.process.step1_text') }}
                    </p>
                </div>
            </div>

            <div class="relative flex justify-end md:justify-around items-center">
                <div class="absolute w-8 h-8 rounded-full bg-brand-accent border-4 border-theme-bg z-10 left-1/2 transform -translate-x-1/2 flex items-center justify-center text-white font-bold">2</div>
                <div class="w-full md:w-5/12 bg-theme-surface-2/70 p-6 rounded-xl border border-brand-accent/30 shadow-lg mr-10 md:mr-0 md:ml-10 order-2 md:order-1">
                    <h3 class="text-2xl font-semibold text-theme-text mb-2">{{ __('frontend.process.step2_title') }}</h3>
                    <p class="text-theme-muted">
                        {{ __('frontend.process.step2_text') }}
                    </p>
                </div>
                <div class="hidden md:block w-5/12 order-1 md:order-2"></div>
            </div>

            <div class="relative flex justify-start md:justify-around items-center">
                <div class="hidden md:block w-5/12"></div>
                <div class="absolute w-8 h-8 rounded-full bg-brand-accent border-4 border-theme-bg z-10 left-1/2 transform -translate-x-1/2 flex items-center justify-center text-white font-bold">3</div>
                <div class="w-full md:w-5/12 bg-theme-surface-2/70 p-6 rounded-xl border border-brand-accent/30 shadow-lg ml-10 md:ml-0 md:mr-10">
                    <h3 class="text-2xl font-semibold text-theme-text mb-2">{{ __('frontend.process.step3_title') }}</h3>
                    <p class="text-theme-muted">
                        {{ __('frontend.process.step3_text') }}
                    </p>
                </div>
            </div>

            <div class="relative flex justify-end md:justify-around items-center">
                <div class="absolute w-8 h-8 rounded-full bg-brand-accent border-4 border-theme-bg z-10 left-1/2 transform -translate-x-1/2 flex items-center justify-center text-white font-bold">4</div>
                <div class="w-full md:w-5/12 bg-theme-surface-2/70 p-6 rounded-xl border border-brand-accent/30 shadow-lg mr-10 md:mr-0 md:ml-10 order-2 md:order-1">
                    <h3 class="text-2xl font-semibold text-theme-text mb-2">{{ __('frontend.process.step4_title') }}</h3>
                    <p class="text-theme-muted">
                        {{ __('frontend.process.step4_text') }}
                    </p>
                </div>
                <div class="hidden md:block w-5/12 order-1 md:order-2"></div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!document.getElementById('vg-process-motion-style')) {
        const style = document.createElement('style');
        style.id = 'vg-process-motion-style';
        style.innerHTML = `
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
            @keyframes vgPulseStep {
                0%, 100% { transform: translateX(-50%) scale(1); }
                50% { transform: translateX(-50%) scale(1.08); }
            }
        `;
        document.head.appendChild(style);
    }

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

    const header = document.querySelector('header');
    const timelineLine = document.querySelector('.absolute.left-1\\/2.w-0\\.5');
    const stepRows = Array.from(document.querySelectorAll('.space-y-12 > .relative.flex'));
    const stepDots = Array.from(document.querySelectorAll('.absolute.w-8.h-8.rounded-full.bg-brand-accent'));
    const stepCards = stepRows.map(row => row.querySelector('.w-full.md\\:w-5\\/12'));

    if (!prefersReducedMotion) {
        if (header) {
            header.style.opacity = '0';
            header.style.transform = 'translateY(44px)';
            header.style.transition = 'opacity 1.15s ease, transform 1.15s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                header.style.opacity = '1';
                header.style.transform = 'translateY(0)';
            }, 120);
        }

        if (timelineLine) {
            timelineLine.style.transform = 'translateX(-50%) scaleY(0)';
            timelineLine.style.transformOrigin = 'top';
            timelineLine.style.transition = 'transform 1.4s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                timelineLine.style.transform = 'translateX(-50%) scaleY(1)';
            }, 420);
        }

        stepRows.forEach((row, index) => {
            const card = stepCards[index];
            const dot = stepDots[index];

            if (card) {
                card.style.opacity = '0';
                card.style.transform = index % 2 === 0 ? 'translateX(-42px)' : 'translateX(42px)';
                card.style.transition = 'opacity 1.05s ease, transform 1.05s cubic-bezier(0.22, 1, 0.36, 1)';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateX(0)';
                }, 760 + (index * 260));
            }

            if (dot) {
                dot.style.opacity = '0';
                dot.style.transition = 'opacity 0.8s ease';
                setTimeout(() => {
                    dot.style.opacity = '1';
                    dot.style.animation = 'vgPulseStep 2.2s ease-in-out infinite';
                }, 980 + (index * 260));
            }
        });
    }

    stepCards.forEach(card => {
        if (!card) return;
        card.style.transition = 'transform 0.32s ease, box-shadow 0.32s ease';
        card.addEventListener('mouseenter', function () {
            if (prefersReducedMotion) return;
            card.style.transform = 'translateY(-6px)';
            card.style.boxShadow = '0 22px 48px rgba(0,0,0,0.12)';
        });
        card.addEventListener('mouseleave', function () {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '';
        });
    });
});
</script>
@endsection