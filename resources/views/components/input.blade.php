{{-- x-input.blade.php --}}
{{-- Usage: <x-input type="text" name="project_title" placeholder="Project Title" :value="$project->title" /> --}}
{{-- Usage: <x-input type="textarea" name="description" rows="4">Project description...</x-input> --}}

@props(['type' => 'text', 'name', 'rows' => 3, 'value' => null])

@php
    $baseClasses = 'w-full p-3 rounded-lg text-theme-text bg-theme-surface border border-theme-border placeholder:text-theme-muted outline-none shadow-inner transition duration-300 focus:border-brand-accent focus:ring-0';

    $tag = $type === 'textarea' ? 'textarea' : 'input';
@endphp

@if ($tag === 'textarea')
    <textarea
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge(['class' => $baseClasses]) }}
    >{{ $slot->isNotEmpty() ? $slot : old($name, $value) }}</textarea>
@else
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => $baseClasses]) }}
    />
@endif