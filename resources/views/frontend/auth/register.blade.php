@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-20 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-5xl p-4 lg:p-8 text-center">

        <h2 class="text-5xl font-extrabold text-theme-text mb-4">
            {{ __('frontend.auth.join_the') }} <span class="text-brand-accent">{{ __('frontend.auth.vertexgrad_ecosystem') }}</span>
        </h2>

        <p class="text-xl text-theme-muted mb-16 max-w-3xl mx-auto">
            {{ __('frontend.auth.choose_registration_type') }}
        </p>

        @if ($errors->any())
            <div class="max-w-md mx-auto mb-8 p-4 rounded-lg bg-red-500/10 border border-red-400/40 text-red-500 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <a href="{{ route('register.investor') }}"
               class="p-10 rounded-2xl theme-panel hover:bg-theme-surface-2 transition duration-300 shadow-brand-soft block group">
                <i class="fas fa-hand-holding-usd text-6xl text-brand-accent mb-4"
                   style="filter: drop-shadow(0 0 8px var(--brand-accent-glow));"></i>

                <h3 class="text-3xl font-bold text-theme-text mb-3">
                    {{ __('frontend.auth.investor_fund_manager') }}
                </h3>

                <p class="text-theme-muted mb-6">
                    {{ __('frontend.auth.investor_register_text') }}
                </p>

                <span class="inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold bg-brand-accent text-white group-hover:bg-brand-accent-strong transition duration-300 shadow-brand-soft">
                    {{ __('frontend.auth.register_as_investor') }} <i class="fas fa-arrow-right ml-2"></i>
                </span>
            </a>

            <a href="{{ route('register.academic') }}"
               class="p-10 rounded-2xl theme-panel hover:bg-theme-surface-2 transition duration-300 shadow-brand-soft block group">
                <i class="fas fa-flask text-6xl text-brand-accent mb-4"
                   style="filter: drop-shadow(0 0 8px var(--brand-accent-glow));"></i>

                <h3 class="text-3xl font-bold text-theme-text mb-3">
                    {{ __('frontend.auth.academic_project_creator') }}
                </h3>

                <p class="text-theme-muted mb-6">
                    {{ __('frontend.auth.academic_register_text') }}
                </p>

                <span class="inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold border border-brand-accent text-theme-text group-hover:bg-brand-accent group-hover:text-white transition duration-300">
                    {{ __('frontend.auth.register_as_academic') }} <i class="fas fa-rocket ml-2"></i>
                </span>
            </a>

        </div>

        <p class="mt-12 text-center text-theme-muted text-sm">
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

    const heading = document.querySelector('h2');
    const subtitle = document.querySelector('p.text-xl');
    const errorBox = document.querySelector('.bg-red-500\\/10');
    const cards = Array.from(document.querySelectorAll('.grid a.theme-panel'));
    const loginLink = document.querySelector('a[href*="login"]');

    if (!document.getElementById('vg-register-choice-style')) {
        const style = document.createElement('style');
        style.id = 'vg-register-choice-style';
        style.textContent = `
            .vg-reveal {
                opacity: 0;
                transform: translateY(24px);
                transition: opacity .7s ease, transform .7s cubic-bezier(.22,1,.36,1);
            }

            .vg-reveal.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .vg-choice-card {
                position: relative;
                overflow: hidden;
                transition:
                    transform .28s ease,
                    box-shadow .28s ease,
                    border-color .28s ease,
                    background-color .28s ease;
            }

            .vg-choice-card::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(99,102,241,.06), transparent 45%, rgba(99,102,241,.03));
                opacity: 0;
                transition: opacity .28s ease;
                pointer-events: none;
            }

            .vg-choice-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 20px 48px rgba(0,0,0,.10);
            }

            .vg-choice-card:hover::before {
                opacity: 1;
            }

            .vg-choice-icon {
                transition: transform .28s ease, filter .28s ease;
            }

            .vg-choice-card:hover .vg-choice-icon {
                transform: translateY(-2px) scale(1.04);
            }

            .vg-choice-cta {
                transition:
                    transform .22s ease,
                    box-shadow .22s ease,
                    background-color .22s ease,
                    color .22s ease,
                    border-color .22s ease;
            }

            .vg-choice-card:hover .vg-choice-cta {
                transform: translateY(-1px);
            }

            .vg-focus-ring:focus-visible {
                outline: none;
                box-shadow: 0 0 0 3px rgba(99,102,241,.16);
                border-radius: 14px;
            }

            @media (max-width: 767px) {
                .vg-choice-card:hover {
                    transform: none;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .vg-reveal,
                .vg-choice-card,
                .vg-choice-card::before,
                .vg-choice-icon,
                .vg-choice-cta {
                    transition: none !important;
                    animation: none !important;
                    transform: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    const revealItems = [heading, subtitle, errorBox, ...cards].filter(Boolean);

    revealItems.forEach((el, index) => {
        el.classList.add('vg-reveal');

        if (prefersReducedMotion) {
            el.classList.add('is-visible');
            return;
        }

        setTimeout(() => {
            el.classList.add('is-visible');
        }, 100 + (index * 120));
    });

    cards.forEach(card => {
        card.classList.add('vg-choice-card', 'vg-focus-ring');

        const icon = card.querySelector('i');
        const cta = card.querySelector('span');

        if (icon) icon.classList.add('vg-choice-icon');
        if (cta) cta.classList.add('vg-choice-cta');

        if (!prefersReducedMotion) {
            card.addEventListener('mouseenter', () => {
                const otherCards = cards.filter(item => item !== card);
                otherCards.forEach(other => {
                    other.style.opacity = '0.92';
                });
            });

            card.addEventListener('mouseleave', () => {
                cards.forEach(item => {
                    item.style.opacity = '';
                });
            });
        }
    });

    if (loginLink) {
        loginLink.classList.add('vg-focus-ring');
    }
});
</script>
@endsection