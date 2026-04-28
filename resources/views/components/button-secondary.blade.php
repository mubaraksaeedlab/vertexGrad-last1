{{-- Secondary Button Component (Outline / Ghost Style) --}}

@props(['href' => null, 'type' => 'button', 'disabled' => false])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg text-lg px-6 py-3 whitespace-nowrap transition duration-300 ease-in-out border';
    $enabledClasses = 'border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white';
    $disabledClasses = 'pointer-events-none opacity-50 cursor-not-allowed bg-theme-surface-2 border-theme-border text-theme-muted';

    $finalClasses = $baseClasses . ' ' . ($disabled ? $disabledClasses : $enabledClasses);
@endphp

@if($href)
    <a href="{{ $disabled ? 'javascript:void(0)' : $href }}"
       @if($disabled) aria-disabled="true" @endif
       {{ $attributes->merge(['class' => $finalClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
            @if($disabled) disabled @endif
            {{ $attributes->merge(['class' => $finalClasses]) }}>
        {{ $slot }}
    </button>
@endif