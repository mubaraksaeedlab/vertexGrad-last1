{{-- x-alert.blade.php --}}
{{-- Usage: <x-alert type="success" message="Project successfully submitted for review." /> --}}
{{-- Usage: <x-alert type="error">Could not process investment request.</x-alert> --}}

@props(['type' => 'info', 'message' => null])

@php
    $schemes = [
        'success' => [
            'wrapper' => 'bg-green-500/10 border-green-400/40',
            'icon' => 'fas fa-check-circle text-green-500',
            'text' => 'text-theme-text',
        ],
        'error' => [
            'wrapper' => 'bg-red-500/10 border-red-400/40',
            'icon' => 'fas fa-times-circle text-red-500',
            'text' => 'text-theme-text',
        ],
        'info' => [
            'wrapper' => 'bg-brand-accent-soft border-brand-accent',
            'icon' => 'fas fa-info-circle text-brand-accent',
            'text' => 'text-theme-text',
        ],
        'warning' => [
            'wrapper' => 'bg-yellow-500/10 border-yellow-400/40',
            'icon' => 'fas fa-exclamation-triangle text-yellow-500',
            'text' => 'text-theme-text',
        ],
    ];

    $scheme = $schemes[$type] ?? $schemes['info'];
@endphp

<div
    {{ $attributes->merge(['class' => "p-4 rounded-lg border-l-4 shadow-brand-soft transition duration-300 {$scheme['wrapper']}"]) }}
    role="alert"
>
    <div class="flex items-start">
        <div class="flex-shrink-0 mr-3 text-xl">
            <i class="{{ $scheme['icon'] }}"></i>
        </div>

        <div class="{{ $scheme['text'] }} text-sm font-medium">
            {{ $message ?? $slot }}
        </div>
    </div>
</div>