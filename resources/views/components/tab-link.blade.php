{{-- resources/views/components/tab-link.blade.php --}}

@props(['target', 'label', 'active' => false])

@php
    $activeClasses = $active
        ? 'border-brand-accent text-brand-accent'
        : 'border-transparent text-theme-muted hover:text-brand-accent hover:border-brand-accent/50';
@endphp

<a
    href="#{{ $target }}"
    class="py-3 px-1 border-b-2 font-semibold text-lg whitespace-nowrap transition duration-300 {{ $activeClasses }}"
    data-tab-target="{{ $target }}"
    role="tab"
    aria-controls="{{ $target }}"
    aria-selected="{{ $active ? 'true' : 'false' }}"
>
    {{ $label }}
</a>