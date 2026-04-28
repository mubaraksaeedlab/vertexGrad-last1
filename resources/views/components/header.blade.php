@php
    $transitionBase = config('design.classes.transition_base');
    $user = auth('web')->user();
    $isLoggedIn = auth('web')->check();

    if ($user) {
        if ($user->role === 'Investor') {
            $navLinks = [
                ['href' => route('frontend.projects.index'), 'label' => __('frontend.header.marketplace')],
                ['href' => route('home') . '#advantage', 'label' => __('frontend.header.why_vertexgrad')],
            ];
        } else {
            $navLinks = [
                ['href' => route('project.submit.step1'), 'label' => __('frontend.header.submit_project')],
                ['href' => route('home') . '#advantage', 'label' => __('frontend.header.why_vertexgrad')],
            ];
        }
    } else {
        $navLinks = [
            ['href' => route('frontend.projects.index'), 'label' => __('frontend.header.browse_projects')],
            ['href' => route('home') . '#advantage', 'label' => __('frontend.header.why_vertexgrad')],
            ['href' => route('utility.support'), 'label' => __('frontend.header.help_center')],
        ];
    }

    $currentLocale = app()->getLocale();
    $nextLocale = $currentLocale === 'ar' ? 'en' : 'ar';
    $languageLabel = $currentLocale === 'ar' ? 'English' : 'العربية';
@endphp

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

<header class="w-full fixed top-0 left-0 z-50 border-b header-shell transition-colors duration-300">
    <div class="{{ config('design.classes.container') }} flex items-center justify-between h-20 gap-4">

        {{-- LOGO --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 min-w-0">
            <img
                src="{{ config('design.brand.logo') ? asset(config('design.brand.logo')) : asset('images/logo.png') }}"
                alt="{{ config('design.brand.name', 'VertexGrad') }}"
                class="brand-logo w-10 h-10 object-contain shrink-0"
            >
            <span class="font-extrabold text-2xl tracking-tight text-brand-accent whitespace-nowrap">
                {{ config('design.brand.name', 'VertexGrad') }}
            </span>
        </a>

        {{-- DESKTOP NAV --}}
        <nav class="hidden lg:flex items-center gap-8 min-w-0">
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] }}"
                   class="{{ $transitionBase }} text-theme-text hover-text-brand-accent relative group uppercase text-[11px] tracking-[0.16em] font-bold whitespace-nowrap">
                    {{ $link['label'] }}
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-brand-accent transform scale-x-0 group-hover:scale-x-100 {{ $transitionBase }}"></span>
                </a>
            @endforeach
        </nav>

        {{-- DESKTOP ACTIONS --}}
        <div class="hidden md:flex items-center gap-3 shrink-0">

            {{-- THEME SWITCHER --}}
            <div
                class="relative"
                x-data="{
                    open: false,
                    currentTheme: localStorage.getItem('vertexgrad_theme') || '{{ config('design.default_theme', 'brand') }}',
                    setTheme(theme) {
                        this.currentTheme = theme;
                        window.VertexGradUI.applyTheme(theme);
                        this.open = false;
                    }
                }"
            >
                <button
                    type="button"
                    @click="open = !open"
                    class="h-10 px-4 rounded-xl border border-theme-border bg-theme-surface text-theme-text hover-border-brand-accent hover-text-brand-accent transition-all text-[11px] font-black uppercase tracking-widest shadow-brand-soft whitespace-nowrap"
                >
                    {{ __('frontend.header.theme') }}
                </button>

                <div
                    x-show="open"
                    x-cloak
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    style="display:none;"
                    class="absolute right-0 top-12 w-56 rounded-2xl border border-theme-border bg-theme-surface shadow-2xl overflow-hidden"
                >
                    <button
                        type="button"
                        @click="setTheme('brand')"
                        class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-brand-accent-soft transition"
                    >
                        <span class="text-sm font-semibold text-theme-text">{{ __('frontend.header.vertexgrad_theme') }}</span>
                        <span class="w-4 h-4 rounded-full border border-cyan-400 bg-cyan-400"></span>
                    </button>

                    <button
                        type="button"
                        @click="setTheme('dark')"
                        class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-brand-accent-soft transition border-t border-theme-border"
                    >
                        <span class="text-sm font-semibold text-theme-text">{{ __('frontend.header.dark_theme') }}</span>
                        <span class="w-4 h-4 rounded-full border border-slate-500 bg-slate-950"></span>
                    </button>

                    <button
                        type="button"
                        @click="setTheme('light')"
                        class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-brand-accent-soft transition border-t border-theme-border"
                    >
                        <span class="text-sm font-semibold text-theme-text">{{ __('frontend.header.light_theme') }}</span>
                        <span class="w-4 h-4 rounded-full border border-slate-300 bg-white"></span>
                    </button>
                </div>
            </div>

            {{-- LANGUAGE SWITCHER --}}
            <a
                href="{{ route('frontend.language.switch', $nextLocale) }}"
                class="h-10 px-4 rounded-xl border border-theme-border bg-theme-surface text-theme-text hover-border-brand-accent hover-text-brand-accent transition-all text-[11px] font-black uppercase tracking-widest shadow-brand-soft inline-flex items-center justify-center whitespace-nowrap"
            >
                {{ $languageLabel }}
            </a>

            @guest('web')
                <a href="{{ route('login.show') }}"
                   class="text-theme-muted hover-text-brand-accent text-xs font-bold uppercase tracking-widest {{ $transitionBase }} whitespace-nowrap">
                    {{ __('frontend.header.sign_in') }}
                </a>

                <a href="{{ route('frontend.projects.index') }}"
                   class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-xs font-extrabold bg-brand-accent text-white hover-bg-brand-accent-strong transition-all shadow-brand-soft whitespace-nowrap">
                    {{ __('frontend.header.browse_opportunities') }}
                </a>
            @endguest

            @auth('web')
                @php
                    $isInvestor = $user->role === 'Investor';
                    $dashboardRoute = $isInvestor ? route('dashboard.investor') : route('dashboard.academic');

                    $initialUnread = $user->unreadNotifications()->count();
                    $latestNotifications = $user->notifications()->latest()->take(5)->get();
                @endphp

                {{-- NOTIFICATIONS --}}
                <div
                    id="frontend-notification-bell"
                    class="relative flex items-center"
                    x-data="{ open:false }"
                    data-latest-url="{{ route('frontend.notifications.latest') }}"
                >
                    <button type="button"
                            @click="open = !open"
                            class="relative flex items-center justify-center w-10 h-10 rounded-full bg-brand-accent-soft border border-theme-border text-brand-accent hover:bg-brand-accent hover:text-white transition-all focus:outline-none shadow-brand-soft">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[17px] h-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                        </svg>

                        <span
                            id="frontendUnreadBadge"
                            class="absolute -top-1 -right-1 {{ $initialUnread > 0 ? 'flex' : 'hidden' }} h-4 min-w-[16px] px-1 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white shadow"
                        >
                            {{ $initialUnread > 9 ? '9+' : $initialUnread }}
                        </span>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open=false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         style="display:none;"
                         class="absolute right-0 top-12 w-80 bg-theme-surface border border-theme-border rounded-2xl shadow-2xl z-[9999] overflow-hidden">

                        <div class="p-4 border-b border-theme-border bg-brand-accent-soft flex justify-between items-center">
                            <h3 class="text-xs font-black uppercase tracking-widest text-brand-accent">
                                {{ __('frontend.header.alerts') }}
                            </h3>
                            <span class="text-[10px] text-theme-muted">
                                <span id="frontendUnreadText">{{ $initialUnread }}</span> {{ __('frontend.header.unread') }}
                            </span>
                        </div>

                        <div id="frontendNotificationList" class="max-h-80 overflow-y-auto">
                            @forelse($latestNotifications as $n)
                                @php
                                    $title = $n->data['title'] ?? __('frontend.header.notification');
                                    $message = $n->data['message'] ?? '';
                                    $url = $n->data['url'] ?? null;
                                    $icon = $n->data['icon'] ?? 'fas fa-circle';
                                @endphp

                                <form method="POST"
                                      action="{{ route('frontend.notifications.read', $n->id) }}"
                                      class="block">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ $url }}">

                                    <button type="submit"
                                            class="w-full text-left block p-4 border-b border-theme-border hover:bg-brand-accent-soft transition {{ $n->read_at ? 'opacity-50' : '' }}">
                                        <div class="flex gap-3">
                                            <div class="text-brand-accent mt-1">
                                                <i class="{{ $icon }} text-xs"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-theme-text truncate">{{ $title }}</p>
                                                <p class="text-[10px] text-theme-muted mt-0.5 line-clamp-2">
                                                    {{ $message }}
                                                </p>
                                                <p class="text-[10px] text-theme-muted/80 mt-1">
                                                    {{ $n->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            @empty
                                <div class="p-8 text-center text-theme-muted text-xs italic">
                                    {{ __('frontend.header.no_alerts') }}
                                </div>
                            @endforelse
                        </div>

                        <div class="grid grid-cols-2 border-t border-theme-border bg-brand-accent-soft">
                            <a href="{{ route('frontend.notifications.index') }}"
                               class="p-3 text-center text-[10px] font-black text-theme-muted hover-text-brand-accent transition border-r border-theme-border">
                                {{ __('frontend.header.view_all') }}
                            </a>
                            <form method="POST" action="{{ route('frontend.notifications.markAllRead') }}">
                                @csrf
                                <button type="submit" class="p-3 w-full text-center text-[10px] font-black text-brand-accent hover:text-theme-text transition">
                                    {{ __('frontend.header.mark_all_read') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="{{ $dashboardRoute }}"
                   class="px-5 py-2.5 bg-theme-surface border border-theme-border rounded-xl text-theme-text hover:bg-brand-accent hover:text-white font-black uppercase text-[10px] tracking-widest transition-all shadow-brand-soft whitespace-nowrap">
                    {{ __('frontend.header.dashboard') }}
                </a>

                <div class="flex items-center gap-3 ml-3 pl-3 border-l border-theme-border">
                    <div title="{{ $user->role }}" class="shrink-0">
                        @if(!empty($user->profile_image))
                            <img
                                src="{{ asset('storage/' . $user->profile_image) }}"
                                alt="{{ $user->name }}"
                                class="w-10 h-10 rounded-full object-cover border border-brand-accent/30 shadow-brand-soft"
                            >
                        @else
                            <div class="w-10 h-10 rounded-full bg-brand-accent-soft border border-brand-accent text-brand-accent flex items-center justify-center font-black text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('frontend.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-xl border border-red-400/30 text-red-400 hover:bg-red-500 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest whitespace-nowrap">
                            {{ __('frontend.header.logout') }}
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        {{-- MOBILE ACTIONS --}}
        <div class="md:hidden flex items-center gap-2">
            <a
                href="{{ route('frontend.language.switch', $nextLocale) }}"
                class="w-auto min-w-[74px] h-10 px-3 rounded-xl border border-theme-border bg-theme-surface text-theme-text flex items-center justify-center shadow-brand-soft text-[11px] font-black uppercase tracking-widest hover-border-brand-accent hover-text-brand-accent transition-all"
            >
                {{ $languageLabel }}
            </a>

            <div
                class="relative"
                x-data="{
                    open: false,
                    setTheme(theme) {
                        window.VertexGradUI.applyTheme(theme);
                        this.open = false;
                    }
                }"
            >
                <button
                    type="button"
                    @click="open = !open"
                    class="w-10 h-10 rounded-xl border border-theme-border bg-theme-surface text-theme-text flex items-center justify-center shadow-brand-soft"
                >
                    <i class="fas fa-palette text-sm"></i>
                </button>

                <div
                    x-show="open"
                    x-cloak
                    @click.away="open = false"
                    style="display:none;"
                    class="absolute right-0 top-12 w-44 rounded-2xl border border-theme-border bg-theme-surface shadow-2xl overflow-hidden"
                >
                    <button type="button" @click="setTheme('brand')" class="w-full px-4 py-3 text-left text-sm font-semibold text-theme-text hover:bg-brand-accent-soft transition">
                        {{ __('frontend.header.vertexgrad_theme') }}
                    </button>
                    <button type="button" @click="setTheme('dark')" class="w-full px-4 py-3 text-left text-sm font-semibold text-theme-text hover:bg-brand-accent-soft transition border-t border-theme-border">
                        {{ __('frontend.header.dark_theme') }}
                    </button>
                    <button type="button" @click="setTheme('light')" class="w-full px-4 py-3 text-left text-sm font-semibold text-theme-text hover:bg-brand-accent-soft transition border-t border-theme-border">
                        {{ __('frontend.header.light_theme') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
(function () {
    function initVertexHeader() {
        const header = document.querySelector('header.header-shell');
        if (!header) return;

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!document.getElementById('vg-shell-motion-style')) {
            const style = document.createElement('style');
            style.id = 'vg-shell-motion-style';
            style.innerHTML = `
                .vg-header-ready {
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                }

                .vg-footer-ready {
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                }
            `;
            document.head.appendChild(style);
        }

        // visible first-load entrance
        if (!prefersReducedMotion) {
            header.style.opacity = '0';
            header.style.transform = 'translateY(-22px)';
            header.style.transition = 'opacity 1s ease, transform 1s cubic-bezier(0.22, 1, 0.36, 1)';

            requestAnimationFrame(() => {
                setTimeout(() => {
                    header.classList.add('vg-header-ready');
                }, 120);
            });
        }

        // scroll polish
        function updateHeaderState() {
            const y = window.scrollY || window.pageYOffset;

            if (y > 12) {
                header.style.backdropFilter = 'blur(14px)';
                header.style.webkitBackdropFilter = 'blur(14px)';
                header.style.boxShadow = '0 14px 32px rgba(0,0,0,0.10)';
            } else {
                header.style.backdropFilter = '';
                header.style.webkitBackdropFilter = '';
                header.style.boxShadow = '';
            }
        }

        updateHeaderState();
        window.addEventListener('scroll', updateHeaderState, { passive: true });

        // nav hover polish
        const navLinks = header.querySelectorAll('nav a');
        navLinks.forEach(link => {
            link.style.transition = 'transform 0.28s ease, color 0.28s ease';

            link.addEventListener('mouseenter', function () {
                if (prefersReducedMotion) return;
                link.style.transform = 'translateY(-2px)';
            });

            link.addEventListener('mouseleave', function () {
                link.style.transform = '';
            });
        });

        // button hover polish
        const actionButtons = header.querySelectorAll('a, button');
        actionButtons.forEach(el => {
            if (el.closest('nav')) return;

            el.style.transition = 'transform 0.28s ease, box-shadow 0.28s ease';

            el.addEventListener('mouseenter', function () {
                if (prefersReducedMotion) return;
                el.style.transform = 'translateY(-2px)';
            });

            el.addEventListener('mouseleave', function () {
                el.style.transform = '';
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVertexHeader);
    } else {
        initVertexHeader();
    }
})();
</script>