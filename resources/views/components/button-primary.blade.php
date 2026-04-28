{{-- Primary Button Component --}}
{{-- Usage: <x-button-primary href="/signup" class="text-xl">Sign Up</x-button-primary> --}}
{{-- Usage: <x-button-primary type="submit" disabled>Submitting...</x-button-primary> --}}

@props(['href' => null, 'type' => 'button', 'disabled' => false])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg text-lg px-6 py-3 whitespace-nowrap transition duration-300 ease-in-out';
    $enabledClasses = 'bg-brand-accent text-white hover-bg-brand-accent-strong shadow-brand-soft';
    $disabledClasses = 'pointer-events-none opacity-50 cursor-not-allowed bg-theme-surface-2 text-theme-muted border border-theme-border shadow-none';

    $combinedClasses = $baseClasses . ' ' . ($disabled ? $disabledClasses : $enabledClasses);
@endphp

@if($href)
    <a href="{{ $disabled ? 'javascript:void(0)' : $href }}"
       @if($disabled) aria-disabled="true" @endif
       {{ $attributes->merge(['class' => $combinedClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
            @if($disabled) disabled @endif
            {{ $attributes->merge(['class' => $combinedClasses]) }}>
        {{ $slot }}
    </button>
@endif