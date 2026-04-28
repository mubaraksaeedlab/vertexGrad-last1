@php
    // --- Configuration Access ---
    $design = config('design');
    $containerClass = $design['classes']['container'];
    $headingClass = $design['classes']['heading_primary'];
    $textAccentClass = $design['classes']['text_accent'];

    $btnPrimaryClass = $design['classes']['btn_base'] . ' ' . $design['classes']['btn_primary'];
    $btnSecondaryClass = $design['classes']['btn_base'] . ' ' . $design['classes']['btn_secondary'];

    $primaryColor = $design['colors']['primary'];
    $darkBg = $design['colors']['dark'];
    $darkestBg = $design['colors']['darkest'] ?? '#030712';
    $cardLight = $design['colors']['cardLight'];

    $sectionYClass = $design['classes']['section_y'] ?? 'py-20 lg:py-32';

    $user = auth('web')->user();
    $isLoggedIn = auth('web')->check();
    $isInvestor = $isLoggedIn && $user->role === 'Investor';
    $isStudent = $isLoggedIn && $user->role === 'Student';

    // Fallback if controller sends fewer projects
    $featuredProjects = $featuredProjects ?? collect();
@endphp

@extends('frontend.layouts.app')

@section('content')

{{-- ---------------------------------------------------------------- --}}
{{-- START OF SECTION 1: HERO SECTION --}}
{{-- ---------------------------------------------------------------- --}}

<section class="relative w-full h-[700px] lg:h-[850px] overflow-hidden flex items-center justify-center border-b border-theme-border bg-theme-bg transition-colors duration-300">

    <div class="absolute inset-0 pointer-events-none"
         style="background: linear-gradient(to top, var(--hero-overlay-from), var(--hero-overlay-to));"></div>

    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1920 850" preserveAspectRatio="xMidYMid slice" style="opacity: 0.82;">
        <defs>
            <filter id="neon-glow" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stdDeviation="1.25" result="coloredBlur"/>
                <feMerge>
                    <feMergeNode in="coloredBlur"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
        </defs>

        <g transform="translate(0, 175)">
            <circle cx="720" cy="300" r="4" fill="var(--brand-accent)" filter="url(#neon-glow)" class="circuit-node central-node node-pulse-1"/>
            <circle cx="1200" cy="300" r="4" fill="var(--brand-accent)" filter="url(#neon-glow)" class="circuit-node central-node node-pulse-2"/>

            <g id="left-paths">
                <path d="M 0 100 H 180 V 200 H 350 V 260 H 550 V 300 H 720" stroke="var(--hero-line)" stroke-width="1.8" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-1" />
                <path d="M 0 350 L 120 350 L 120 250 L 320 250 L 320 330 L 520 330 L 520 300 L 720 300" stroke="var(--hero-line-soft)" stroke-width="1.3" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-2" />
                <path d="M 0 200 H 200 V 140 H 380 V 220 H 600 V 300 H 720" stroke="var(--hero-line)" stroke-width="1.8" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-3" />
                <path d="M 0 420 H 150 V 460 H 350 V 380 H 500 V 300 H 720" stroke="var(--hero-line-soft)" stroke-width="1.2" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-4" />
                <path d="M 0 60 H 100 V 160 H 250 V 220 H 450 V 290 H 720" stroke="var(--hero-line-soft)" stroke-width="1.0" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-5" />
                <path d="M 0 280 H 150 V 180 H 300 V 290 H 500 V 310 H 720" stroke="var(--hero-line)" stroke-width="1.4" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-6" />
                <path d="M 0 470 H 220 V 400 H 450 V 360 H 650 V 300 H 720" stroke="var(--hero-line-soft)" stroke-width="0.9" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-7" />
                <path d="M 0 150 H 50 V 250 H 300 V 190 H 550 V 300 H 720" stroke="var(--hero-line)" stroke-width="1.3" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-8" />
                <path d="M 0 30 H 250 V 90 H 400 V 180 H 620 V 300 H 720" stroke="var(--hero-line-soft)" stroke-width="0.8" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-9" />
                <path d="M 0 510 H 100 V 380 H 280 V 440 H 480 V 300 H 720" stroke="var(--hero-line-soft)" stroke-width="0.9" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-10" />
                <path d="M 0 250 H 180 V 330 H 420 V 240 H 680 V 300 H 720" stroke="var(--hero-line)" stroke-width="1.2" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-11" />
                <path d="M 0 490 H 50 V 420 H 150 V 350 H 350 V 300 H 720" stroke="var(--hero-line-soft)" stroke-width="0.7" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-12" />
            </g>

            <g id="right-paths">
                <path d="M 1920 100 H 1740 V 200 H 1570 V 260 H 1370 V 300 H 1200" stroke="var(--hero-line)" stroke-width="1.8" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-1" />
                <path d="M 1920 350 L 1800 350 L 1800 250 L 1600 250 L 1600 330 L 1400 330 L 1400 300 L 1200 300" stroke="var(--hero-line-soft)" stroke-width="1.3" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-2" />
                <path d="M 1920 200 H 1720 V 140 H 1540 V 220 H 1320 V 300 H 1200" stroke="var(--hero-line)" stroke-width="1.8" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-3" />
                <path d="M 1920 420 H 1770 V 460 H 1570 V 380 H 1420 V 300 H 1200" stroke="var(--hero-line-soft)" stroke-width="1.2" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-4" />
                <path d="M 1920 60 H 1820 V 160 H 1670 V 220 H 1470 V 290 H 1200" stroke="var(--hero-line-soft)" stroke-width="1.0" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-5" />
                <path d="M 1920 280 H 1770 V 180 H 1620 V 290 H 1420 V 310 H 1200" stroke="var(--hero-line)" stroke-width="1.4" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-6" />
                <path d="M 1920 470 H 1700 V 400 H 1470 V 360 H 1270 V 300 H 1200" stroke="var(--hero-line-soft)" stroke-width="0.9" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-7" />
                <path d="M 1920 150 H 1870 V 250 H 1620 V 190 H 1370 V 300 H 1200" stroke="var(--hero-line)" stroke-width="1.3" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-8" />
                <path d="M 1920 30 H 1670 V 90 H 1520 V 180 H 1300 V 300 H 1200" stroke="var(--hero-line-soft)" stroke-width="0.8" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-9" />
                <path d="M 1920 510 H 1820 V 380 H 1640 V 440 H 1440 V 300 H 1200" stroke="var(--hero-line-soft)" stroke-width="0.9" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-10" />
                <path d="M 1920 250 H 1740 V 330 H 1500 V 240 H 1240 V 300 H 1200" stroke="var(--hero-line)" stroke-width="1.2" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-11" />
                <path d="M 1920 490 H 1870 V 420 H 1770 V 350 H 1570 V 300 H 1200" stroke="var(--hero-line-soft)" stroke-width="0.7" fill="none" filter="url(#neon-glow)" class="circuit-path animate-flow-12" />
            </g>
        </g>
    </svg>

    <style>
        @keyframes draw-flow {
            0% { stroke-dashoffset: 1200; opacity: 0; }
            5% { opacity: 1; }
            40% { stroke-dashoffset: 0; opacity: 1; }
            70% { opacity: 0.45; }
            100% { stroke-dashoffset: 1200; opacity: 0; }
        }

        .circuit-path {
            stroke-dasharray: 1200;
            stroke-dashoffset: 1200;
            animation: draw-flow 16s linear infinite;
        }

        .central-node {
            opacity: 0.9;
            transform-origin: center;
            animation: pulse-neon 2s infinite;
        }

        .node-pulse-1 { animation-delay: 0s; }
        .node-pulse-2 { animation-delay: 1s; }
        .animate-flow-1 { animation-delay: 0s; }
        .animate-flow-2 { animation-delay: 0.5s; }
        .animate-flow-3 { animation-delay: 1s; }
        .animate-flow-4 { animation-delay: 1.5s; }
        .animate-flow-5 { animation-delay: 2s; }
        .animate-flow-6 { animation-delay: 2.5s; }
        .animate-flow-7 { animation-delay: 3s; }
        .animate-flow-8 { animation-delay: 3.5s; }
        .animate-flow-9 { animation-delay: 4s; }
        .animate-flow-10 { animation-delay: 4.5s; }
        .animate-flow-11 { animation-delay: 5s; }
        .animate-flow-12 { animation-delay: 5.5s; }
    </style>

    <div class="{{ $containerClass }} relative z-10 text-center pt-24 pb-16">
        <p class="text-md uppercase font-bold tracking-[0.2em] mb-4 text-brand-accent opacity-90">
            {{ __('frontend.home.hero_platform_label') }}
        </p>

        <h1 class="{{ $headingClass }} max-w-6xl mx-auto leading-tight text-theme-text">
            {{ __('frontend.home.hero_title_line1') }}

            <span class="block mt-6 text-7xl lg:text-8xl font-black uppercase tracking-wider text-brand-accent"
                  style="text-shadow: var(--hero-title-glow);">
                {{ __('frontend.home.hero_title_brand') }}
            </span>
        </h1>

        <p class="mt-8 text-xl lg:text-2xl text-theme-muted max-w-4xl mx-auto font-light">
            {{ __('frontend.home.hero_subtitle') }}
        </p>

        <div class="mt-14 max-w-4xl mx-auto">
            <div class="flex flex-col sm:flex-row items-stretch rounded-xl p-2 bg-theme-surface border border-theme-border shadow-brand-soft transition-colors duration-300">
                <input
                    type="search"
                    placeholder="{{ __('frontend.home.search_placeholder') }}"
                    class="flex-grow p-4 text-lg bg-transparent text-theme-text placeholder:text-theme-muted focus:outline-none focus:ring-0"
                >

                <a href="/projects"
                   class="mt-4 sm:mt-0 sm:ml-2 flex-shrink-0 inline-flex items-center justify-center rounded-lg px-8 py-4 text-lg font-semibold bg-brand-accent text-white hover-bg-brand-accent-strong transition duration-300 shadow-brand-soft">
                    <i class="fas fa-search mr-2"></i> {{ __('frontend.home.find_projects') }}
                </a>
            </div>

            <div class="mt-8">
                <p class="text-theme-muted text-sm font-semibold uppercase tracking-widest mb-4">
                    {{ __('frontend.home.creator_prompt') }}
                </p>

                <a href="/submit-project"
                   class="inline-flex items-center justify-center rounded-lg px-6 py-3 text-base font-semibold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300">
                    <i class="fas fa-rocket mr-2"></i> {{ __('frontend.home.submit_for_vetting') }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ---------------------------------------------------------------- --}}
{{-- END OF SECTION 1: HERO SECTION --}}
{{-- ---------------------------------------------------------------- --}}
{{-- ---------------------------------------------------------------- --}}
{{-- START OF SECTION 2: FEATURED PROJECTS --}}
{{-- ---------------------------------------------------------------- --}}
@php
    $design = config('design');
    $c = $design['classes'];

    $container = $c['container'];
    $sectionY = $c['section_y'];
@endphp

<section class="{{ $sectionY }} bg-theme-surface-2 border-y border-theme-border overflow-hidden transition-colors duration-300">
    <div class="{{ $container }}">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">

            <div class="lg:col-span-1 opacity-0 translate-y-8 section-anim">
                <h2 class="text-4xl lg:text-5xl font-extrabold text-theme-text leading-tight">
                    {{ __('frontend.home.projects_title_before') }}
                    <span class="text-brand-accent">{{ __('frontend.home.projects_title_highlight') }}</span>
                </h2>

                <p class="mt-4 text-theme-muted text-lg leading-relaxed">
                    {{ __('frontend.home.projects_subtitle') }}
                </p>

                <a href="{{ route('frontend.projects.index') }}"
                   class="mt-8 inline-flex items-center justify-center rounded-lg px-8 py-3 text-base font-semibold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300">
                    {{ __('frontend.home.explore_all_projects') }}
                </a>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-8">
                @forelse ($homeProjects as $project)
                    <div class="opacity-0 translate-y-8 section-anim">
                        <div class="group rounded-xl overflow-hidden theme-panel hover:shadow-brand-soft transition duration-300 h-full flex flex-col">
                            <div class="relative h-48 w-full bg-theme-surface-2 overflow-hidden">
                                @if(method_exists($project, 'hasMedia') && $project->hasMedia('images'))
                                    <img src="{{ $project->getFirstMediaUrl('images') }}"
                                         alt="{{ $project->name }}"
                                         class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-brand-accent-soft">
                                        <i class="fas fa-project-diagram text-brand-accent text-3xl"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 flex flex-col flex-1">
                                <p class="text-brand-accent text-sm font-semibold mb-2">
                                    {{ $project->projectCategory->display_name ?? $project->category ?? __('frontend.home.general') }}
                                </p>

                                <h3 class="text-xl font-bold text-theme-text leading-tight mb-3">
                                    {{ $project->name }}
                                </h3>

                                <p class="text-theme-muted text-sm mb-4 line-clamp-2 flex-1">
                                    {{ $project->description ?? '' }}
                                </p>

                                <div class="flex justify-between items-center mt-auto">
                                    <p class="text-theme-muted/80 text-xs">
                                        {{ __('frontend.home.by') }} {{ $project->student->name ?? __('frontend.home.student') }}
                                    </p>

                                    <a href="{{ route('frontend.projects.show', $project) }}"
                                       class="text-brand-accent text-sm font-bold hover:underline">
                                        {{ __('frontend.home.details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="sm:col-span-2 p-10 rounded-2xl border border-dashed border-theme-border text-center text-theme-muted bg-theme-surface">
                        {{ __('frontend.home.no_projects') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        gsap.utils.toArray(".section-anim").forEach((el) => {
            gsap.fromTo(el,
                { opacity: 0, y: 30 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    ease: "power3.out",
                    scrollTrigger: {
                        trigger: el,
                        start: "top 85%",
                        once: true
                    }
                }
            );
        });
    }
});
</script>

{{-- ---------------------------------------------------------------- --}}
{{-- END OF SECTION 2 --}}
{{-- ---------------------------------------------------------------- --}}


{{-- ---------------------------------------------------------------- --}}
{{-- START OF SECTION 3: EXPLORE BY CATEGORY --}}
{{-- ---------------------------------------------------------------- --}}

@php
    $categories = [
        ['name' => __('frontend.home.cat_ai'), 'icon' => 'fas fa-brain', 'query' => 'Artificial Intelligence'],
        ['name' => __('frontend.home.cat_fintech'), 'icon' => 'fas fa-money-bill-wave', 'query' => 'Fintech'],
        ['name' => __('frontend.home.cat_biotech'), 'icon' => 'fas fa-dna', 'query' => 'Healthcare'],
        ['name' => __('frontend.home.cat_energy'), 'icon' => 'fas fa-leaf', 'query' => 'Energy'],
        ['name' => __('frontend.home.cat_aerospace'), 'icon' => 'fas fa-rocket', 'query' => 'Aerospace'],
        ['name' => __('frontend.home.cat_quantum'), 'icon' => 'fas fa-cube', 'query' => 'Quantum'],
    ];
@endphp

<section class="{{ $sectionYClass }} bg-theme-bg relative border-b border-theme-border transition-colors duration-300">
    <div class="{{ $containerClass }} text-center">
        <x-section-title
            title="{{ __('frontend.home.categories_title') }}"
            subtitle="{{ __('frontend.home.categories_subtitle') }}"
        />

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mt-12">
            @foreach($categories as $category)
                <a href="{{ route('frontend.projects.index', ['category' => $category['query']]) }}"
                   class="block p-4 rounded-xl theme-panel hover:bg-theme-surface-2 transition duration-300 group">
                    <div class="mb-3">
                        <i class="{{ $category['icon'] }} text-4xl group-hover:text-brand-accent transition duration-300 text-brand-accent"
                           style="filter: drop-shadow(0 0 6px var(--brand-accent-glow));">
                        </i>
                    </div>

                    <p class="font-semibold text-theme-text text-sm mt-2 group-hover:text-brand-accent transition duration-300">
                        {{ $category['name'] }}
                    </p>
                </a>
            @endforeach
        </div>

        <div class="mt-12">
            <a href="{{ route('frontend.projects.index') }}" class="text-sm text-brand-accent hover:text-theme-text font-medium transition">
                <i class="fas fa-arrow-right mr-1"></i> {{ __('frontend.home.view_all_categories') }}
            </a>
        </div>
    </div>
</section>

{{-- ---------------------------------------------------------------- --}}
{{-- END OF SECTION 3 --}}
{{-- ---------------------------------------------------------------- --}}


{{-- ---------------------------------------------------------------- --}}
{{-- START OF SECTION 4: THE VERTEXGRAD ADVANTAGE --}}
{{-- ---------------------------------------------------------------- --}}

@php
    $valueProps = [
        [
            'title' => __('frontend.home.adv1_title'),
            'icon' => 'fas fa-graduation-cap',
            'description' => __('frontend.home.adv1_desc'),
        ],
        [
            'title' => __('frontend.home.adv2_title'),
            'icon' => 'fas fa-lock',
            'description' => __('frontend.home.adv2_desc'),
        ],
        [
            'title' => __('frontend.home.adv3_title'),
            'icon' => 'fas fa-globe',
            'description' => __('frontend.home.adv3_desc'),
        ],
    ];
@endphp

<section id="advantage" class="{{ $sectionYClass }} bg-theme-surface-2 relative border-b border-theme-border transition-colors duration-300">
    <div class="{{ $containerClass }} text-center">
        <x-section-title
            title="{{ __('frontend.home.advantage_title') }}"
            subtitle="{{ __('frontend.home.advantage_subtitle') }}"
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 text-left">
            @foreach($valueProps as $prop)
                <div class="p-6 lg:p-8 rounded-2xl theme-panel transition duration-300 hover:bg-theme-surface-2">
                    <div class="mb-4">
                        <i class="{{ $prop['icon'] }} text-5xl text-brand-accent"
                           style="filter: drop-shadow(0 0 6px var(--brand-accent-glow));">
                        </i>
                    </div>

                    <h3 class="text-2xl font-bold text-theme-text mb-3 tracking-wide">
                        {{ $prop['title'] }}
                    </h3>

                    <p class="text-theme-muted text-base leading-relaxed">
                        {{ $prop['description'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-16">
            @guest('web')
                <p class="text-theme-muted text-md">
                    {{ __('frontend.home.ready_prompt') }}
                    <a href="{{ route('register.investor') }}" class="text-brand-accent font-bold hover:text-theme-text transition">
                        {{ __('frontend.home.create_investor_account') }} <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </p>
            @else
                <p class="text-theme-muted text-md">
                    {{ __('frontend.home.welcome_back') }}
                    <a href="{{ $isInvestor ? route('dashboard.investor') : route('dashboard.academic') }}" class="text-brand-accent font-bold hover:text-theme-text transition">
                        {{ __('frontend.home.go_to_dashboard') }} <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </p>
            @endguest
        </div>
    </div>
</section>

{{-- ---------------------------------------------------------------- --}}
{{-- END OF SECTION 4 --}}
{{-- ---------------------------------------------------------------- --}}

@php
    $partnerLogos = [
        'stanford-logo.svg', 'mit-logo.svg', 'cambridge-logo.svg',
        'harvard-logo.svg', 'caltech-logo.svg', 'oxford-logo.svg',
    ];
@endphp

{{-- ---------------------------------------------------------------- --}}
{{-- START OF SECTION 5: TRUSTED PARTNERS & UNIVERSITIES --}}
{{-- ---------------------------------------------------------------- --}}


{{-- ---------------------------------------------------------------- --}}
{{-- END OF SECTION 5 --}}
{{-- ---------------------------------------------------------------- --}}

{{-- ---------------------------------------------------------------- --}}
{{-- START OF SECTION 6: ACCESS THE NETWORK --}}
{{-- ---------------------------------------------------------------- --}}

@guest('web')
<section class="{{ $sectionYClass }} relative overflow-hidden bg-theme-bg border-b border-theme-border transition-colors duration-300">
    <div class="{{ $containerClass }} text-center">

        <x-section-title
            title="{{ __('frontend.home.access_title') }}"
            subtitle="{{ __('frontend.home.access_subtitle') }}"
        />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-20 max-w-6xl mx-auto">

            <div class="p-10 lg:p-16 text-center rounded-2xl theme-panel transition duration-300 hover:bg-theme-surface-2">
                <i class="fas fa-hand-holding-usd text-7xl text-brand-accent mb-6"
                   style="filter: drop-shadow(0 0 10px var(--brand-accent-glow));">
                </i>

                <h3 class="text-4xl font-black text-theme-text mb-3 uppercase tracking-wide">
                    {{ __('frontend.home.investor_portal') }}
                </h3>

                <p class="text-theme-muted mb-10 max-w-sm mx-auto text-lg">
                    {{ __('frontend.home.investor_portal_text') }}
                </p>

                <a href="{{ route('register.investor') }}"
                   class="inline-flex items-center justify-center rounded-lg px-12 py-4 text-xl font-extrabold bg-brand-accent text-white hover-bg-brand-accent-strong transition duration-300 shadow-brand-soft">
                    <i class="fas fa-eye mr-2"></i> {{ __('frontend.home.review_projects_now') }}
                </a>
            </div>

            <div class="p-10 lg:p-16 text-center rounded-2xl theme-panel transition duration-300 hover:bg-theme-surface-2">
                <i class="fas fa-flask text-7xl text-brand-accent mb-6"
                   style="filter: drop-shadow(0 0 10px var(--brand-accent-glow));">
                </i>

                <h3 class="text-4xl font-black text-theme-text mb-3 uppercase tracking-wide">
                    {{ __('frontend.home.academic_submission') }}
                </h3>

                <p class="text-theme-muted mb-10 max-w-sm mx-auto text-lg">
                    {{ __('frontend.home.academic_submission_text') }}
                </p>

                <a href="{{ route('project.submit.step1') }}"
                   class="inline-flex items-center justify-center rounded-lg px-12 py-4 text-xl font-semibold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300">
                    <i class="fas fa-rocket mr-2"></i> {{ __('frontend.home.start_vetting_process') }}
                </a>
            </div>

        </div>

    </div>
</section>
@endguest

{{-- ---------------------------------------------------------------- --}}
{{-- END OF SECTION 6 --}}
{{-- ---------------------------------------------------------------- --}}
@endsection