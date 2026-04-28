{{-- x-stat-card.blade.php --}}
{{-- Usage: <x-stat-card title="Total Investment" value="$12.5M" icon="fas fa-chart-line" /> --}}

@props(['title', 'value', 'icon' => null, 'difference' => null])

<div {{ $attributes->merge(['class' => 'theme-panel rounded-xl p-6 space-y-3 transition duration-300 hover:shadow-neon_md']) }}>
    <div class="flex items-center justify-between">
        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-brand-accent-soft border border-brand-accent text-brand-accent text-lg">
            @if ($icon)
                <i class="{{ $icon }}"></i>
            @else
                <i class="fas fa-star"></i>
            @endif
        </div>

        @if ($difference)
            @php
                $isPositive = str_contains($difference, '+');
                $diffClass = $isPositive ? 'text-green-500' : 'text-red-500';
            @endphp
            <span class="text-xs font-semibold {{ $diffClass }}">{{ $difference }}</span>
        @endif
    </div>

    <p class="text-4xl font-extrabold tracking-tight text-theme-text">
        <span class="text-brand-accent">{{ $value }}</span>
    </p>

    <h4 class="text-sm font-medium text-theme-muted uppercase tracking-wider">{{ $title }}</h4>
</div>