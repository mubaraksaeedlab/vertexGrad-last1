<style>
    .brand-logo {
        transition: filter 0.3s ease, opacity 0.3s ease, transform 0.3s ease;
    }

    [data-theme="dark"] .brand-logo,
    [data-theme="brand"] .brand-logo {
        filter: brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(180deg);
        opacity: 0.9;
        transform: scale(1.08);
    }

    [data-theme="light"] .brand-logo {
        filter: none;
        opacity: 1;
        transform: scale(1);
    }

    .brand-logo:hover {
        transform: scale(1.15) rotate(6deg);
    }
</style>

<footer class="w-full border-t border-theme-border bg-theme-surface transition-colors duration-300">
    <div class="{{ config('design.classes.container') }} py-16 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-12 gap-y-10 text-theme-muted">

        {{-- Brand --}}
        <div class="space-y-4 col-span-1 sm:col-span-2 md:col-span-1">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img
                    src="{{ asset(config('design.brand.logo')) }}"
                    alt="{{ config('design.brand.name') }} logo"
                    class="brand-logo w-10 h-10 object-contain shrink-0"
                >

                <span class="font-extrabold text-2xl tracking-wider text-brand-accent transition-opacity duration-300 group-hover:opacity-90">
                    {{ config('design.brand.name') }}
                </span>
            </a>

            <p class="text-sm max-w-xs text-theme-muted leading-relaxed">
                {{ __('frontend.footer.tagline') }}
            </p>
        </div>

        {{-- Company --}}
        <nav aria-labelledby="footer-company-heading" class="footer-column space-y-4">
            <h4 id="footer-company-heading" class="font-bold text-theme-text uppercase tracking-wider mb-4 border-b border-theme-border pb-1">
                {{ __('frontend.footer.company') }}
            </h4>

            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('utility.about') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.about') }}</a></li>
                <li><a href="{{ route('utility.contact') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.contact') }}</a></li>
                <li><a href="{{ route('utility.partnerships') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.partnerships') }}</a></li>
            </ul>
        </nav>

        {{-- Resources --}}
        <nav aria-labelledby="footer-resources-heading" class="footer-column space-y-4">
            <h4 id="footer-resources-heading" class="font-bold text-theme-text uppercase tracking-wider mb-4 border-b border-theme-border pb-1">
                {{ __('frontend.footer.resources') }}
            </h4>

            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('frontend.projects.index') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.explore_projects') }}</a></li>
                <li><a href="{{ route('utility.how-it-works') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.how_it_works') }}</a></li>
                <li><a href="{{ route('project.submit.step1') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.submit_idea') }}</a></li>
                <li><a href="{{ route('utility.support') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.support') }}</a></li>
            </ul>
        </nav>

        {{-- Legal & Social --}}
        <div class="footer-column space-y-4">
            <nav aria-labelledby="footer-legal-heading">
                <h4 id="footer-legal-heading" class="font-bold text-theme-text uppercase tracking-wider mb-4 border-b border-theme-border pb-1">
                    {{ __('frontend.footer.legal') }}
                </h4>

                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('utility.terms') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.terms') }}</a></li>
                    <li><a href="{{ route('utility.privacy') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.privacy') }}</a></li>
                    <li><a href="{{ route('utility.disclosures') }}" class="footer-link hover-text-brand-accent">{{ __('frontend.footer.disclosures') }}</a></li>
                </ul>
            </nav>

            <div class="pt-2">
                <div class="flex gap-4">
                    <a href="#" class="footer-social text-xl text-theme-muted hover-text-brand-accent" aria-label="{{ __('frontend.footer.linkedin') }}">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="footer-social text-xl text-theme-muted hover-text-brand-accent" aria-label="{{ __('frontend.footer.twitter') }}">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="footer-social text-xl text-theme-muted hover-text-brand-accent" aria-label="{{ __('frontend.footer.facebook') }}">
                        <i class="fab fa-facebook"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full border-t border-theme-border py-6 text-center text-xs text-theme-muted bg-theme-surface-2 transition-colors duration-300">
        <div class="{{ config('design.classes.container') }}">
            &copy; {{ date('Y') }} {{ config('design.brand.name') }}. {{ __('frontend.footer.rights') }}
        </div>
    </div>
</footer>

<script>
(function () {
    function initVertexFooter() {
        const footer = document.querySelector('footer');

        if (!footer) {
            return;
        }

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const footerColumns = footer.querySelectorAll('.footer-column');

        revealFooter(footer, footerColumns, prefersReducedMotion);
        initFooterLinks(footer, prefersReducedMotion);
        initSocialLinks(footer, prefersReducedMotion);
    }

    function revealFooter(footer, footerColumns, prefersReducedMotion) {
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            return;
        }

        footer.style.opacity = '0';
        footer.style.transform = 'translateY(34px)';
        footer.style.transition = 'opacity 1s ease, transform 1s cubic-bezier(0.22, 1, 0.36, 1)';

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) {
                    return;
                }

                footer.style.opacity = '1';
                footer.style.transform = 'translateY(0)';

                footerColumns.forEach((column, index) => {
                    column.style.opacity = '0';
                    column.style.transform = 'translateY(20px)';
                    column.style.transition = 'opacity 0.85s ease, transform 0.85s ease';

                    setTimeout(() => {
                        column.style.opacity = '1';
                        column.style.transform = 'translateY(0)';
                    }, 140 + (index * 120));
                });

                obs.unobserve(footer);
            });
        }, {
            threshold: 0.15
        });

        observer.observe(footer);
    }

    function initFooterLinks(footer, prefersReducedMotion) {
        const links = footer.querySelectorAll('.footer-link');

        links.forEach(link => {
            link.style.transition = 'transform 0.25s ease, color 0.25s ease';

            link.addEventListener('mouseenter', () => {
                if (prefersReducedMotion) {
                    return;
                }

                link.style.transform = document.documentElement.dir === 'rtl'
                    ? 'translateX(-3px)'
                    : 'translateX(3px)';
            });

            link.addEventListener('mouseleave', () => {
                link.style.transform = '';
            });
        });
    }

    function initSocialLinks(footer, prefersReducedMotion) {
        const socialLinks = footer.querySelectorAll('.footer-social');

        socialLinks.forEach(link => {
            link.style.transition = 'transform 0.28s ease, color 0.25s ease';

            link.addEventListener('mouseenter', () => {
                if (prefersReducedMotion) {
                    return;
                }

                link.style.transform = 'translateY(-4px) scale(1.06)';
            });

            link.addEventListener('mouseleave', () => {
                link.style.transform = '';
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVertexFooter);
    } else {
        initVertexFooter();
    }
})();
</script>