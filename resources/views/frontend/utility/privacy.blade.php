@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-20 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-theme-text mb-4">
                {{ __('frontend.privacy.title_before') }}
                <span class="text-brand-accent">{{ __('frontend.privacy.title_highlight') }}</span>
            </h1>
            <p class="text-sm text-theme-muted">
                {{ __('frontend.privacy.effective_date') }}
            </p>
        </header>

        <div class="theme-panel p-10 rounded-xl space-y-8 text-theme-muted">
            <section>
                <h3 class="text-3xl font-semibold text-brand-accent mb-4">{{ __('frontend.privacy.section1_title') }}</h3>
                <p>{{ __('frontend.privacy.section1_text') }}</p>
            </section>

            <section>
                <h3 class="text-3xl font-semibold text-brand-accent mb-4">{{ __('frontend.privacy.section2_title') }}</h3>
                <ul class="list-disc list-inside space-y-2 ml-4">
                    <li>{{ __('frontend.privacy.section2_point1') }}</li>
                    <li>{{ __('frontend.privacy.section2_point2') }}</li>
                    <li>{{ __('frontend.privacy.section2_point3') }}</li>
                    <li>{{ __('frontend.privacy.section2_point4') }}</li>
                </ul>
            </section>

            <section>
                <h3 class="text-3xl font-semibold text-brand-accent mb-4">{{ __('frontend.privacy.section3_title') }}</h3>
                <p>{{ __('frontend.privacy.section3_text') }}</p>
            </section>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!document.getElementById('vg-privacy-motion-style')) {
        const style = document.createElement('style');
        style.id = 'vg-privacy-motion-style';
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
    const panel = document.querySelector('.theme-panel');
    const sections = Array.from(document.querySelectorAll('.theme-panel section'));

    if (!prefersReducedMotion) {
        if (header) {
            header.style.opacity = '0';
            header.style.transform = 'translateY(40px)';
            header.style.transition = 'opacity 1.1s ease, transform 1.1s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                header.style.opacity = '1';
                header.style.transform = 'translateY(0)';
            }, 120);
        }

        if (panel) {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(34px)';
            panel.style.transition = 'opacity 1.05s ease, transform 1.05s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0)';
            }, 420);
        }

        sections.forEach((section, index) => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(26px)';
            section.style.transition = 'opacity 0.95s ease, transform 0.95s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }, 720 + (index * 170));
        });
    }

    if (panel) {
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
    }
});
</script>
@endsection