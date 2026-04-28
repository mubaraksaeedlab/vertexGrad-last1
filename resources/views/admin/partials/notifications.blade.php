@php
    $adminUser = auth('admin')->user();
    $unreadCount = $adminUser ? $adminUser->unreadNotifications()->count() : 0;
    $latestNotifications = $adminUser
        ? $adminUser->notifications()->latest()->take(5)->get()
        : collect();
@endphp

<div class="dropdown"
     id="admin-notification-bell"
     data-count-url="{{ route('admin.notifications.count') }}">
    <a class="dropdown-toggle position-relative d-inline-flex align-items-center justify-content-center text-decoration-none"
       href="#"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false"
       style="width: 40px; height: 40px; border-radius: 12px; color: var(--vg-text); background: #fff; border: 1px solid var(--vg-border);">
        <i class="icon-copy dw dw-notification" style="font-size: 18px;"></i>

        <span id="adminUnreadBadge"
              class="badge bg-danger rounded-circle notification-active d-flex align-items-center justify-content-center {{ $unreadCount > 0 ? '' : 'd-none' }}"
              style="position:absolute; top:-5px; right:-7px; min-width:18px; height:18px; font-size:10px; padding:0;">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    </a>

    <div class="dropdown-menu dropdown-menu-end p-0 border-0"
         style="width: 360px; max-width: 360px; border-radius: 18px; overflow: hidden;">
        <div class="d-flex justify-content-between align-items-center px-3 py-3"
             style="border-bottom: 1px solid var(--vg-border); background: #fff;">
            <div>
                <h6 class="mb-0 fw-bold" style="color: var(--vg-text);">{{ __('backend.layout_notifications.notifications') }}</h6>
                <small style="color: var(--vg-text-muted);">
                    <span id="adminUnreadText">{{ $unreadCount }}</span> {{ __('backend.layout_notifications.unread') }}
                </small>
            </div>
        </div>

        <div style="max-height: 340px; overflow-y: auto; background: #fff;">
            @forelse($latestNotifications as $notification)
                @php
                    $title = $notification->data['title'] ?? __('backend.layout_notifications.notification');
                    $message = $notification->data['message'] ?? '';
                    $url = $notification->data['url'] ?? route('admin.notifications.index');
                    $icon = $notification->data['icon'] ?? 'fas fa-bell';
                    $isRead = !is_null($notification->read_at);
                @endphp

                <form method="POST"
                      action="{{ route('admin.notifications.read', $notification->id) }}"
                      class="m-0">
                    @csrf
                    <input type="hidden" name="redirect" value="{{ $url }}">

                    <button type="submit"
                            class="dropdown-item px-3 py-3 border-0 border-bottom text-wrap w-100 text-start"
                            style="background: {{ $isRead ? '#ffffff' : '#f8fbff' }}; border-radius: 0; border-color: var(--vg-border) !important;">
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width: 36px; height: 36px; border-radius: 12px; background: rgba(63,127,178,0.10); color: var(--vg-primary);">
                                <i class="{{ $icon }}"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold small" style="color: var(--vg-text);">{{ $title }}</div>
                                <div class="small mt-1" style="color: var(--vg-text-muted); line-height: 1.45;">{{ $message }}</div>
                                <div class="small mt-2" style="color: #94a3b8;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </button>
                </form>
            @empty
                <div class="px-3 py-4 text-center small" style="color: var(--vg-text-muted); background: #fff;">
                    {{ __('backend.layout_notifications.no_notifications_yet') }}
                </div>
            @endforelse
        </div>

        <div class="d-grid" style="grid-template-columns: 1fr 1fr; background: #fff;">
            <a href="{{ route('admin.notifications.index') }}"
               class="btn btn-light rounded-0 border-0 py-2"
               style="border-top: 1px solid var(--vg-border); border-right: 1px solid var(--vg-border);">
                {{ __('backend.layout_notifications.history') }}
            </a>

            <form method="POST" action="{{ route('admin.notifications.markAllRead') }}" class="m-0">
                @csrf
                <button type="submit"
                        class="btn btn-light rounded-0 border-0 py-2"
                        style="border-top: 1px solid var(--vg-border);">
                    {{ __('backend.layout_notifications.mark_all_read') }}
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bell = document.getElementById('admin-notification-bell');
    if (!bell) return;

    const countUrl = bell.dataset.countUrl;
    const badge = document.getElementById('adminUnreadBadge');
    const unreadText = document.getElementById('adminUnreadText');

    function refreshUnreadCount() {
        fetch(countUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const count = data.count ?? 0;

            if (unreadText) unreadText.textContent = count;

            if (badge) {
                if (count > 0) {
                    badge.classList.remove('d-none');
                    badge.textContent = count > 9 ? '9+' : count;
                } else {
                    badge.classList.add('d-none');
                }
            }
        })
        .catch(() => {});
    }

    setInterval(refreshUnreadCount, 30000);
});
</script>
@endpush