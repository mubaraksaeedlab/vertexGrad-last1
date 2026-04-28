{{-- resources/views/components/tabs.blade.php --}}
@props(['activeTab' => null])

<div class="border-b border-theme-border flex space-x-6 overflow-x-auto mb-6">
    {{ $slot }}
</div>