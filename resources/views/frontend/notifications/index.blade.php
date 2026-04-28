@extends('frontend.layouts.app')

@section('title', __('frontend.notifications_page.title'))

@section('content')
<div class="min-h-screen bg-theme-bg text-theme-text pt-28 pb-10 transition-colors duration-300">
    <div class="{{ config('design.classes.container') }}">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-theme-text">
                    <span class="text-brand-accent">{{ __('frontend.notifications_page.heading') }}</span>
                </h1>
                <p class="text-theme-muted text-sm mt-1">
                    {{ __('frontend.notifications_page.subtitle') }}
                </p>
            </div>

            <form method="POST" action="{{ route('frontend.notifications.markAllRead') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg px-5 py-3 font-semibold border border-brand-accent text-theme-text hover:bg-brand-accent hover:text-white transition duration-300">
                    {{ __('frontend.notifications_page.mark_all_read') }}
                </button>
            </form>
        </div>

        <div class="theme-panel rounded-3xl overflow-hidden shadow-brand-soft">
            @forelse($notifications as $n)
                @php
                    $title = $n->data['title'] ?? __('frontend.notifications_page.notification_fallback');
                    $message = $n->data['message'] ?? '';
                    $url = $n->data['url'] ?? null;
                    $icon = $n->data['icon'] ?? 'fas fa-bell';
                    $isRead = !is_null($n->read_at);
                @endphp

                <div class="p-5 border-b border-theme-border last:border-b-0 {{ $isRead ? 'opacity-70' : 'bg-brand-accent-soft/40' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="text-brand-accent mt-1">
                                <i class="{{ $icon }}"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="font-bold text-theme-text">{{ $title }}</p>
                                <p class="text-sm text-theme-muted mt-1">{{ $message }}</p>
                                <p class="text-xs text-theme-muted/80 mt-2">
                                    {{ $n->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if($url)
                                <form method="POST" action="{{ route('frontend.notifications.read', $n->id) }}">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ $url }}">
                                    <button type="submit"
                                            class="px-3 py-2 rounded-lg bg-brand-accent text-white text-xs font-bold hover:bg-brand-accent-strong transition">
                                        {{ __('frontend.notifications_page.open') }}
                                    </button>
                                </form>
                            @endif

                            @if(!$isRead)
                                <form method="POST" action="{{ route('frontend.notifications.read', $n->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-2 rounded-lg bg-theme-surface-2 text-theme-text text-xs font-bold hover:bg-brand-accent-soft transition border border-theme-border">
                                        {{ __('frontend.notifications_page.mark_read') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-theme-muted">
                    {{ __('frontend.notifications_page.empty') }}
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!document.getElementById('vg-notifications-style')) {
        const style = document.createElement('style');
        style.id = 'vg-notifications-style';
        style.innerHTML = `
            @keyframes vgSpin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .vg-progress-line {
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                width: 0%;
                z-index: 9999;
                pointer-events: none;
                background: linear-gradient(90deg, rgba(99,102,241,0.98), rgba(34,197,94,0.98));
                box-shadow: 0 0 18px rgba(99,102,241,0.28);
                transition: width 0.08s linear;
            }
        `;
        document.head.appendChild(style);
    }

    // Reading progress
    const progress = document.createElement('div');
    progress.className = 'vg-progress-line';
    document.body.appendChild(progress);

    function updateProgress() {
        const scrollTop = window.scrollY || window.pageYOffset;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const percent = docHeight > 0 ? Math.min((scrollTop / docHeight) * 100, 100) : 0;
        progress.style.width = percent + '%';
    }

    updateProgress();
    window.addEventListener('scroll', updateProgress, { passive: true });
    window.addEventListener('resize', updateProgress);

    const topBar = document.querySelector('.flex.flex-col.sm\\:flex-row.sm\\:items-center.sm\\:justify-between.gap-4.mb-6');
    const panel = document.querySelector('.theme-panel');
    const items = Array.from(document.querySelectorAll('.theme-panel > div.p-5'));
    const emptyState = document.querySelector('.p-10.text-center.text-theme-muted');
    const markAllForm = document.querySelector('form[action*="markAllRead"]');

    if (!prefersReducedMotion) {
        if (topBar) {
            topBar.style.opacity = '0';
            topBar.style.transform = 'translateY(34px)';
            topBar.style.transition = 'opacity 1.05s ease, transform 1.05s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                topBar.style.opacity = '1';
                topBar.style.transform = 'translateY(0)';
            }, 120);
        }

        if (panel) {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(36px)';
            panel.style.transition = 'opacity 1.08s ease, transform 1.08s cubic-bezier(0.22, 1, 0.36, 1)';
            setTimeout(() => {
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0)';
            }, 420);
        }

        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.88s ease, transform 0.88s ease';
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 760 + (index * 110));
        });

        if (emptyState) {
            emptyState.style.opacity = '0';
            emptyState.style.transform = 'translateY(22px)';
            emptyState.style.transition = 'opacity 0.95s ease, transform 0.95s ease';
            setTimeout(() => {
                emptyState.style.opacity = '1';
                emptyState.style.transform = 'translateY(0)';
            }, 760);
        }
    }

    // Item hover polish
    items.forEach(item => {
        item.style.transition = 'transform 0.28s ease, background-color 0.28s ease, box-shadow 0.28s ease';

        item.addEventListener('mouseenter', function () {
            if (prefersReducedMotion) return;
            item.style.transform = 'translateX(4px)';
            item.style.boxShadow = '0 14px 32px rgba(0,0,0,0.06)';
        });

        item.addEventListener('mouseleave', function () {
            item.style.transform = '';
            item.style.boxShadow = '';
        });
    });

    // Unread pulse feel
    if (!prefersReducedMotion) {
        const unreadItems = Array.from(document.querySelectorAll('.bg-brand-accent-soft\\/40'));
        unreadItems.forEach((item, index) => {
            setTimeout(() => {
                item.animate(
                    [
                        { boxShadow: '0 0 0 rgba(99,102,241,0)' },
                        { boxShadow: '0 0 0 10px rgba(99,102,241,0.06)' },
                        { boxShadow: '0 0 0 rgba(99,102,241,0)' }
                    ],
                    {
                        duration: 1400,
                        easing: 'ease-out',
                        iterations: 1
                    }
                );
            }, 1100 + (index * 120));
        });
    }

    // Button submit loading feedback
    const allForms = document.querySelectorAll('form');
    allForms.forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        if (!submitBtn.dataset.originalHtml) {
            submitBtn.dataset.originalHtml = submitBtn.innerHTML;
        }

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.style.pointerEvents = 'none';
            submitBtn.style.opacity = '0.92';

            const isMarkAll = form.action.includes('markAllRead');
            const label = isMarkAll ? 'Processing...' : 'Opening...';

            submitBtn.innerHTML = `
                <span style="display:inline-flex;align-items:center;gap:10px;">
                    <span style="
                        width:16px;
                        height:16px;
                        border:2px solid rgba(255,255,255,0.45);
                        border-top-color:${isMarkAll ? 'currentColor' : '#ffffff'};
                        border-radius:50%;
                        display:inline-block;
                        animation: vgSpin .7s linear infinite;
                    "></span>
                    ${label}
                </span>
            `;
        });
    });

    window.addEventListener('pageshow', function () {
        document.querySelectorAll('form button[type="submit"]').forEach(btn => {
            if (btn.dataset.originalHtml) {
                btn.disabled = false;
                btn.style.pointerEvents = '';
                btn.style.opacity = '';
                btn.innerHTML = btn.dataset.originalHtml;
            }
        });
    });

    // Accessibility
    const interactive = document.querySelectorAll('a, button');
    interactive.forEach(el => {
        el.addEventListener('focus', function () {
            el.style.outline = 'none';
            el.style.boxShadow = '0 0 0 3px rgba(99,102,241,0.18)';
        });

        el.addEventListener('blur', function () {
            el.style.boxShadow = '';
        });
    });
});
</script>
@endsection