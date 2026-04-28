@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-20 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-theme-text mb-4">
                {{ __('frontend.support.title_before') }}
                <span class="text-brand-accent">{{ __('frontend.support.title_highlight') }}</span>
            </h1>
            <p class="text-xl text-theme-muted max-w-2xl mx-auto">
                {{ __('frontend.support.subtitle_before') }}
                <a href="/contact" class="text-brand-accent hover:underline">{{ __('frontend.support.contact_link') }}</a>.
            </p>
        </header>

        <div class="space-y-6">
            <div class="theme-panel p-6 rounded-xl group cursor-pointer">
                <h3 class="text-xl font-semibold text-theme-text mb-2 flex justify-between items-center">
                    {{ __('frontend.support.q1') }}
                    <i class="fas fa-chevron-down text-brand-accent group-hover:rotate-180 transition-transform"></i>
                </h3>
                <p class="text-theme-muted mt-2 hidden group-hover:block transition-all duration-300">
                    {{ __('frontend.support.a1') }}
                </p>
            </div>

            <div class="theme-panel p-6 rounded-xl group cursor-pointer">
                <h3 class="text-xl font-semibold text-theme-text mb-2 flex justify-between items-center">
                    {{ __('frontend.support.q2') }}
                    <i class="fas fa-chevron-down text-brand-accent group-hover:rotate-180 transition-transform"></i>
                </h3>
                <p class="text-theme-muted mt-2 hidden group-hover:block transition-all duration-300">
                    {{ __('frontend.support.a2') }}
                </p>
            </div>

            <div class="theme-panel p-6 rounded-xl group cursor-pointer">
                <h3 class="text-xl font-semibold text-theme-text mb-2 flex justify-between items-center">
                    {{ __('frontend.support.q3') }}
                    <i class="fas fa-chevron-down text-brand-accent group-hover:rotate-180 transition-transform"></i>
                </h3>
                <p class="text-theme-muted mt-2 hidden group-hover:block transition-all duration-300">
                    {{ __('frontend.support.a3') }}
                </p>
            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!document.getElementById('vg-support-motion-style')) {
        const style = document.createElement('style');
        style.id = 'vg-support-motion-style';
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
    const faqCards = Array.from(document.querySelectorAll('.space-y-6 > .theme-panel'));

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

        faqCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 1s ease, transform 1s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 520 + (index * 170));
        });
    }

    faqCards.forEach((card, index) => {
        const title = card.querySelector('h3');
        const icon = card.querySelector('i');
        const answer = card.querySelector('p');

        if (!title || !icon || !answer) return;

        card.style.transition = 'transform 0.32s ease, box-shadow 0.32s ease';
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-expanded', index === 0 ? 'true' : 'false');

        answer.classList.remove('hidden', 'group-hover:block');
        answer.style.overflow = 'hidden';
        answer.style.transition = 'max-height 0.38s ease, opacity 0.32s ease, margin-top 0.32s ease';
        answer.style.maxHeight = index === 0 ? answer.scrollHeight + 'px' : '0px';
        answer.style.opacity = index === 0 ? '1' : '0';
        answer.style.marginTop = index === 0 ? '0.5rem' : '0';

        if (index === 0) {
            icon.style.transform = 'rotate(180deg)';
        }

        function closeAll() {
            faqCards.forEach(otherCard => {
                const otherAnswer = otherCard.querySelector('p');
                const otherIcon = otherCard.querySelector('i');
                if (!otherAnswer || !otherIcon) return;
                otherAnswer.style.maxHeight = '0px';
                otherAnswer.style.opacity = '0';
                otherAnswer.style.marginTop = '0';
                otherIcon.style.transform = 'rotate(0deg)';
                otherIcon.style.transition = 'transform 0.32s ease';
                otherCard.setAttribute('aria-expanded', 'false');
            });
        }

        function toggleCard() {
            const isOpen = card.getAttribute('aria-expanded') === 'true';
            if (isOpen) {
                answer.style.maxHeight = '0px';
                answer.style.opacity = '0';
                answer.style.marginTop = '0';
                icon.style.transform = 'rotate(0deg)';
                card.setAttribute('aria-expanded', 'false');
            } else {
                closeAll();
                answer.style.maxHeight = answer.scrollHeight + 'px';
                answer.style.opacity = '1';
                answer.style.marginTop = '0.5rem';
                icon.style.transform = 'rotate(180deg)';
                icon.style.transition = 'transform 0.32s ease';
                card.setAttribute('aria-expanded', 'true');
            }
        }

        card.addEventListener('click', toggleCard);
        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleCard();
            }
        });

        card.addEventListener('mouseenter', function () {
            if (prefersReducedMotion) return;
            card.style.transform = 'translateY(-6px)';
            card.style.boxShadow = '0 22px 48px rgba(0,0,0,0.09)';
        });

        card.addEventListener('mouseleave', function () {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '';
        });
    });
});
</script>
@endsection