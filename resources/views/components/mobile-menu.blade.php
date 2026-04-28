@php
    $navLinks = [
        ['href' => '/projects', 'label' => 'Projects'],
        ['href' => '/students', 'label' => 'For Students'],
        ['href' => '/investors', 'label' => 'For Investors'],
        ['href' => '/about', 'label' => 'About'],
    ];
@endphp

<div id="mobileSidebar"
     class="fixed inset-y-0 right-0 w-80 transform translate-x-full z-50 transition duration-300 bg-theme-surface shadow-xl text-theme-text border-l border-theme-border">

    <div class="flex items-center justify-between p-4 border-b border-theme-border">
        <a href="/" class="flex items-center gap-2">
            <img src="{{ config('design.brand.logo') }}" alt="{{ config('design.brand.name') }}" class="w-8 h-8">
            <span class="font-extrabold text-xl tracking-wider text-brand-accent">{{ config('design.brand.name') }}</span>
        </a>

        <button id="mobileSidebarClose"
                class="p-2 rounded-md text-brand-accent hover:bg-brand-accent-soft focus:ring-2 focus:ring-brand-accent"
                aria-label="Close mobile menu">
            ✕
        </button>
    </div>

    <nav class="p-6">
        <ul class="space-y-4">
            @foreach($navLinks as $link)
                <li>
                    <a href="{{ $link['href'] }}" class="block py-2 text-lg hover:text-brand-accent transition duration-300">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-8 space-y-3">
            <a href="/login" class="block text-center w-full inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold bg-brand-accent text-white hover-bg-brand-accent-strong transition duration-300">
                Login
            </a>

            <a href="/projects/submit" class="block text-center w-full inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300">
                Submit Project
            </a>
        </div>
    </nav>
</div>

<div id="mobileSidebarOverlay" class="fixed inset-0 bg-black/60 opacity-0 pointer-events-none z-40 transition duration-300"></div>