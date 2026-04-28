@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-20 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-theme-text mb-4">
                {{ __('frontend.about.title_before') }}
                <span class="text-brand-accent">{{ __('frontend.about.title_highlight') }}</span>
            </h1>
            <p class="text-xl text-theme-muted max-w-3xl mx-auto">
                {{ __('frontend.about.subtitle') }}
            </p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <div class="theme-panel p-8 rounded-2xl">
                <h2 class="text-3xl font-bold text-theme-text mb-4">{{ __('frontend.about.mission_title') }}</h2>
                <p class="text-theme-muted leading-8">
                    {{ __('frontend.about.mission_text') }}
                </p>
            </div>

            <div class="theme-panel p-8 rounded-2xl">
                <h2 class="text-3xl font-bold text-theme-text mb-4">{{ __('frontend.about.vision_title') }}</h2>
                <p class="text-theme-muted leading-8">
                    {{ __('frontend.about.vision_text') }}
                </p>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="theme-panel p-6 rounded-xl text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-brand-accent-soft flex items-center justify-center text-brand-accent text-2xl">
                    <i class="fas fa-microscope"></i>
                </div>
                <h3 class="text-2xl font-semibold text-theme-text mb-2">{{ __('frontend.about.card1_title') }}</h3>
                <p class="text-theme-muted text-sm">
                    {{ __('frontend.about.card1_text') }}
                </p>
            </div>

            <div class="theme-panel p-6 rounded-xl text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-brand-accent-soft flex items-center justify-center text-brand-accent text-2xl">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 class="text-2xl font-semibold text-theme-text mb-2">{{ __('frontend.about.card2_title') }}</h3>
                <p class="text-theme-muted text-sm">
                    {{ __('frontend.about.card2_text') }}
                </p>
            </div>

            <div class="theme-panel p-6 rounded-xl text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-brand-accent-soft flex items-center justify-center text-brand-accent text-2xl">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-2xl font-semibold text-theme-text mb-2">{{ __('frontend.about.card3_title') }}</h3>
                <p class="text-theme-muted text-sm">
                    {{ __('frontend.about.card3_text') }}
                </p>
            </div>
        </div>

        <div class="mt-16 text-center">
            <h2 class="text-4xl font-bold text-theme-text mb-4">{{ __('frontend.about.cta_title') }}</h2>
            <p class="text-xl text-theme-muted mb-8 max-w-2xl mx-auto">
                {{ __('frontend.about.cta_text') }}
            </p>

            <a href="/contact"
               class="inline-flex items-center justify-center rounded-lg px-10 py-3 text-lg font-semibold bg-brand-accent text-white hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft">
                {{ __('frontend.about.cta_button') }} <i class="fas fa-arrow-right ml-3"></i>
            </a>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

            .vg-visible {
                opacity: 1 !important;
                transform: translateY(0) !important;
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
    const missionVisionCards = Array.from(document.querySelectorAll('.grid.grid-cols-1.lg\\:grid-cols-2 > .theme-panel'));
    const featureCards = Array.from(document.querySelectorAll('.mt-10.grid .theme-panel'));
    const ctaSection = document.querySelector('.mt-16.text-center');
    const ctaButton = ctaSection ? ctaSection.querySelector('a[href]') : null;
    const allPanels = Array.from(document.querySelectorAll('.theme-panel'));

    if (!prefersReducedMotion) {
        if (header) {
            header.classList.add('vg-reveal-up');
            setTimeout(() => header.classList.add('vg-visible'), 120);
        }

        missionVisionCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = index === 0 ? 'translateX(-40px)' : 'translateX(40px)';
            card.style.transition = 'opacity 1.1s ease, transform 1.1s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateX(0)';
            }, 500 + (index * 200));
        });

        featureCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(34px) scale(0.988)';
            card.style.transition = 'opacity 1s ease, transform 1s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            }, 900 + (index * 180));
        });

        if (ctaSection) {
            ctaSection.style.opacity = '0';
            ctaSection.style.transform = 'translateY(36px)';
            ctaSection.style.transition = 'opacity 1.05s ease, transform 1.05s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                ctaSection.style.opacity = '1';
                ctaSection.style.transform = 'translateY(0)';
            }, 1500);
        }
    }

    allPanels.forEach(panel => {
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

    const iconCircles = document.querySelectorAll('.w-14.h-14');
    iconCircles.forEach(circle => {
        circle.style.transition = 'transform 0.35s ease, box-shadow 0.35s ease';

        circle.addEventListener('mouseenter', function () {
            if (prefersReducedMotion) return;
            circle.style.transform = 'translateY(-4px) scale(1.06)';
            circle.style.boxShadow = '0 12px 28px rgba(99,102,241,0.18)';
        });

        circle.addEventListener('mouseleave', function () {
            circle.style.transform = '';
            circle.style.boxShadow = '';
        });
    });

    if (ctaButton) {
        const originalHTML = ctaButton.innerHTML;

        ctaButton.addEventListener('click', function () {
            ctaButton.style.pointerEvents = 'none';
            ctaButton.style.opacity = '0.92';
            ctaButton.innerHTML = `
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
                    Opening...
                </span>
            `;

            setTimeout(() => {
                ctaButton.style.pointerEvents = '';
                ctaButton.style.opacity = '';
                ctaButton.innerHTML = originalHTML;
            }, 1800);
        });
    }

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