@extends('layouts.app')

@section('title', __('backend.notifications.title'))

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">{{ __('backend.notifications.page_title') }}</h2>

        <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">{{ __('backend.notifications.mark_all_as_read') }}</button>
        </form>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            @forelse($notifications as $n)
                @php
                    $title = $n->data['title'] ?? __('backend.notifications.notification');
                    $message = $n->data['message'] ?? '';
                    $url = $n->data['url'] ?? null;
                    $icon = $n->data['icon'] ?? 'fas fa-bell';
                    $isRead = !is_null($n->read_at);
                @endphp

                <div class="p-3 border-bottom {{ $isRead ? 'bg-white text-muted' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary pt-1">
                                <i class="{{ $icon }}"></i>
                            </div>

                            <div>
                                <div class="fw-bold">{{ $title }}</div>
                                <div class="small">{{ $message }}</div>
                                <div class="text-muted small mt-1">
                                    {{ $n->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-shrink-0">
                            @if($url)
                                <form method="POST" action="{{ route('admin.notifications.read', $n->id) }}">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ $url }}">
                                    <button type="submit" class="btn btn-sm btn-secondary">
                                        {{ __('backend.notifications.open') }}
                                    </button>
                                </form>
                            @endif

                            @if(!$isRead)
                                <form method="POST" action="{{ route('admin.notifications.read', $n->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        {{ __('backend.notifications.mark_read') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-muted">{{ __('backend.notifications.no_notifications') }}</div>
            @endforelse
        </div>
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection