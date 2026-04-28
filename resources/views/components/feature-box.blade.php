{{-- resources/views/components/feature-box.blade.php --}}
{{-- Usage:
    <x-feature-box
        title="Showcase in Minutes"
        description="Upload your project assets and academic endorsements swiftly with our guided process."
        icon="fas fa-upload"
    />
--}}

@props(['title', 'description', 'icon' => 'fas fa-cogs'])

<div {{ $attributes->merge(['class' => 'theme-panel rounded-xl p-8 transition duration-300 hover:shadow-neon_md hover:scale-[1.02]']) }}>
    <div class="mb-4">
        <i class="{{ $icon }} text-4xl text-brand-accent" style="text-shadow: 0 0 8px var(--brand-accent-glow);"></i>
    </div>

    <h3 class="text-xl font-bold text-theme-text mb-3">
        {{ $title }}
    </h3>

    <p class="text-theme-muted text-base leading-relaxed">
        {{ $description }}
    </p>

    <div class="mt-4">
        <a href="#" class="text-sm font-semibold text-brand-accent hover:underline transition duration-300">
            Learn More <i class="fas fa-arrow-right ml-1 text-xs"></i>
        </a>
    </div>
</div>