{{-- Project Card Component --}}
{{-- Usage: <x-card-project title="My Project" creator="John Doe" category="Tech" image="/path/to/image.jpg" /> --}}

@props(['title', 'creator', 'category' => null, 'image' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl overflow-hidden theme-panel transition duration-300 hover:shadow-neon_md']) }}>
    <div class="h-48 w-full overflow-hidden bg-theme-surface-2">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-theme-muted text-sm">
                Project Visual Coming Soon
            </div>
        @endif
    </div>

    <div class="p-5 space-y-3">
        <h3 class="text-xl font-semibold truncate text-theme-text">{{ $title }}</h3>

        <p class="text-sm text-theme-muted">
            By <span class="font-medium text-theme-text">{{ $creator }}</span>
        </p>

        @if($category)
            <span class="inline-block text-xs font-semibold px-3 py-1.5 rounded-full bg-brand-accent-soft text-brand-accent border border-brand-accent uppercase tracking-wide">
                {{ $category }}
            </span>
        @endif

        <div class="pt-4">
            <a href="#" class="inline-flex items-center justify-center w-full rounded-lg px-4 py-2 text-sm font-semibold bg-brand-accent text-white hover-bg-brand-accent-strong transition duration-300 shadow-brand-soft">
                View Project
            </a>
        </div>
    </div>
</div>