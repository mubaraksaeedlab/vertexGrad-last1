@php
    $navLinks = [
        ['href' => '/projects', 'label' => 'Explore Projects'],
        ['href' => '/how-it-works', 'label' => 'How It Works'],
        ['href' => '/partners', 'label' => 'Partnerships'],
        ['href' => '/faq', 'label' => 'FAQs / Support'],
    ];
@endphp

<div id="mobileSidebar"
     class="fixed inset-y-0 right-0 w-80 transform translate-x-full z-[60] transition duration-300 bg-theme-surface shadow-xl text-theme-text border-l border-theme-border">

    <div class="flex items-center justify-between p-4 h-20 border-b border-theme-border">
        <a href="/" class="flex items-center gap-2">
            <img src="{{ config('design.brand.logo') }}" alt="{{ config('design.brand.name') }}" class="w-10 h-10">
            <span class="font-extrabold text-xl tracking-wider text-brand-accent">{{ config('design.brand.name') }}</span>
        </a>

        <button id="mobileSidebarClose"
                class="p-2 rounded-lg text-brand-accent hover:bg-brand-accent-soft transition duration-300 focus:ring-2 focus:ring-brand-accent"
                aria-label="Close mobile menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="p-6">
        <ul class="space-y-2">
            @foreach($navLinks as $link)
                <li>
                    <a href="{{ $link['href'] }}"
                       class="block py-3 text-lg font-medium text-theme-text hover:text-brand-accent border-b border-theme-border transition duration-300">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-8 space-y-4 pt-4 border-t border-theme-border">
            @guest
                <a href="/submit-project"
                   class="block text-center w-full inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300">
                    Submit Project
                </a>

                <a href="/login"
                   class="block text-center w-full inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold bg-brand-accent text-white hover-bg-brand-accent-strong transition duration-300">
                    Login / Register
                </a>
            @endguest

            @auth
                <a href="/dashboard"
                   class="block text-center w-full py-3 font-semibold rounded-lg bg-brand-accent-soft text-brand-accent hover:bg-brand-accent hover:text-white transition duration-300">
                    Dashboard
                </a>
            @endauth
        </div>
    </nav>
</div>

<div id="mobileSidebarOverlay" class="fixed inset-0 bg-black/60 opacity-0 pointer-events-none z-50 transition duration-300"></div>