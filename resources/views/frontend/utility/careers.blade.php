@php
    $openRoles = [
        ['title' => 'Vetting Analyst (Biotech Focus)', 'location' => 'Remote / London', 'type' => 'Full-time'],
        ['title' => 'Senior Backend Engineer (Laravel/PHP)', 'location' => 'Remote / Zurich', 'type' => 'Full-time'],
        ['title' => 'UX/UI Designer', 'location' => 'Remote', 'type' => 'Contract'],
    ];
@endphp

@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-20 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-theme-text mb-4">
                {{ __('frontend.careers.title_before') }}
                <span class="text-brand-accent">{{ __('frontend.careers.title_highlight') }}</span>
            </h1>
            <p class="text-xl text-theme-muted max-w-3xl mx-auto">
                {{ __('frontend.careers.subtitle') }}
            </p>
        </header>

        <h2 class="text-3xl font-bold text-theme-text mb-8 border-b border-theme-border pb-3">
            {{ __('frontend.careers.open_positions') }} ({{ count($openRoles) }})
        </h2>

        <div class="space-y-4">
            @foreach($openRoles as $role)
                <div class="theme-panel p-6 rounded-xl flex justify-between items-center hover:shadow-brand-soft transition duration-300">
                    <div>
                        <h3 class="text-2xl font-semibold text-theme-text">{{ $role['title'] }}</h3>
                        <p class="text-theme-muted mt-1">
                            {{ $role['location'] }} &middot; <span class="text-brand-accent">{{ $role['type'] }}</span>
                        </p>
                    </div>

                    <a href="/careers/apply/{{ strtolower(str_replace([' ', '(', ')'], '-', $role['title'])) }}"
                       class="text-brand-accent hover:text-theme-text transition-colors duration-300 font-medium">
                        {{ __('frontend.careers.view_apply') }} <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-16 p-8 bg-brand-accent-soft border border-brand-accent/30 rounded-xl text-center">
            <h3 class="text-2xl font-bold text-theme-text">{{ __('frontend.careers.no_match_title') }}</h3>
            <p class="text-lg text-brand-accent mt-2">
                {{ __('frontend.careers.no_match_text') }}
                <a href="/contact" class="hover:underline">{{ __('frontend.careers.contact_hr') }}</a>
            </p>
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
    const sectionTitle = document.querySelector('h2.text-3xl');
    const roleCards = Array.from(document.querySelectorAll('.space-y-4 > .theme-panel'));
    const bottomBanner = document.querySelector('.mt-16.p-8');
    const allPanels = Array.from(document.querySelectorAll('.theme-panel'));

    function animateValue(el, finalValue, duration = 1500) {
        const startTime = performance.now();

        function update(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.floor(finalValue * eased);
            el.textContent = currentValue.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                el.textContent = finalValue.toLocaleString();
            }
        }

        requestAnimationFrame(update);
    }

    if (!prefersReducedMotion) {
        if (header) {
            header.classList.add('vg-reveal-up');
            setTimeout(() => header.classList.add('vg-visible'), 120);
        }

        if (sectionTitle) {
            sectionTitle.style.opacity = '0';
            sectionTitle.style.transform = 'translateY(34px)';
            sectionTitle.style.transition = 'opacity 1.05s ease, transform 1.05s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                sectionTitle.style.opacity = '1';
                sectionTitle.style.transform = 'translateY(0)';
            }, 420);
        }

        roleCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(34px)';
            card.style.transition = 'opacity 1s ease, transform 1s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 720 + (index * 180));
        });

        if (bottomBanner) {
            bottomBanner.style.opacity = '0';
            bottomBanner.style.transform = 'translateY(36px)';
            bottomBanner.style.transition = 'opacity 1.05s ease, transform 1.05s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                bottomBanner.style.opacity = '1';
                bottomBanner.style.transform = 'translateY(0)';
            }, 1450);
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

    roleCards.forEach(card => {
        const applyLink = card.querySelector('a[href]');
        if (!applyLink) return;

        const originalHTML = applyLink.innerHTML;

        applyLink.addEventListener('click', function () {
            applyLink.style.pointerEvents = 'none';
            applyLink.style.opacity = '0.9';
            applyLink.innerHTML = `
                <span style="display:inline-flex;align-items:center;gap:10px;">
                    <span style="
                        width:14px;
                        height:14px;
                        border:2px solid rgba(99,102,241,0.35);
                        border-top-color:currentColor;
                        border-radius:50%;
                        display:inline-block;
                        animation: vgSpin .7s linear infinite;
                    "></span>
                    Opening...
                </span>
            `;

            setTimeout(() => {
                applyLink.style.pointerEvents = '';
                applyLink.style.opacity = '';
                applyLink.innerHTML = originalHTML;
            }, 1600);
        });
    });

    const countText = sectionTitle ? sectionTitle.textContent : '';
    const countMatch = countText.match(/\((\d+)\)/);

    if (!prefersReducedMotion && sectionTitle && countMatch) {
        const finalCount = parseInt(countMatch[1], 10);
        if (!isNaN(finalCount)) {
            const prefix = countText.replace(/\(\d+\)/, '(');
            const suffix = ')';

            sectionTitle.innerHTML = sectionTitle.innerHTML.replace(/\(\d+\)/, '(0)');

            const countNode = Array.from(sectionTitle.childNodes).find(node => node.nodeType === Node.TEXT_NODE);
            const originalText = sectionTitle.textContent;

            let tempSpan = document.createElement('span');
            tempSpan.textContent = '0';
            sectionTitle.innerHTML = originalText.replace(/\(\d+\)/, '(0)');

            const startTime = performance.now();

            function update(now) {
                const progress = Math.min((now - startTime) / 1500, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.floor(finalCount * eased);
                sectionTitle.textContent = originalText.replace(/\(\d+\)/, `(${current})`);

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    sectionTitle.textContent = originalText.replace(/\(\d+\)/, `(${finalCount})`);
                }
            }

            setTimeout(() => requestAnimationFrame(update), 900);
        }
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