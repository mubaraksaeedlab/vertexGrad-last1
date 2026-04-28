{{-- Section Title Component --}}
{{-- Usage: <x-section-title title="Featured Projects" subtitle="Discover the latest student innovations" alignment="left" /> --}}

@props([
    'title',
    'subtitle' => null,
    'alignment' => 'center',
])

@php
    $alignmentClass =
        $alignment === 'center'
            ? 'text-center'
            : ($alignment === 'left' ? 'text-left' : 'text-right');

    $subtitleWidthClass = $alignment === 'center' ? 'mx-auto' : '';
@endphp

<div class="mb-12 {{ $alignmentClass }}">
    <h2 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-theme-text">
        <span class="text-brand-accent">{{ $title }}</span>
    </h2>

    @if($subtitle)
        <p class="mt-4 text-theme-muted text-lg md:text-xl font-light max-w-4xl {{ $subtitleWidthClass }}">
            {{ $subtitle }}
        </p>
    @endif
</div>