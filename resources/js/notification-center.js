const NotificationCenter = (() => {
    let pollTimer = null;
    let activeToasts = new Map();
    let seenIds = new Set();
    let playedSoundIds = new Set();
    let isInitialLoad = true;

    const MAX_VISIBLE_TOASTS = 3;
    const POLL_INTERVAL = 15000;
    const STORAGE_SOUND_KEY = 'vertexgrad_notifications_sound_enabled';

    function soundEnabled() {
        const stored = localStorage.getItem(STORAGE_SOUND_KEY);
        return stored === null ? true : stored === 'true';
    }

    function setSoundEnabled(value) {
        localStorage.setItem(STORAGE_SOUND_KEY, value ? 'true' : 'false');
    }

    function getAudio() {
        let audio = document.getElementById('vertexgrad-notification-audio');
        if (!audio) {
            audio = document.createElement('audio');
            audio.id = 'vertexgrad-notification-audio';
            audio.preload = 'auto';
            audio.src = '/sounds/notification-soft.mp3';
            document.body.appendChild(audio);
        }
        return audio;
    }

    function playSoundOnce(notificationId) {
        if (!soundEnabled()) return;
        if (playedSoundIds.has(notificationId)) return;

        const audio = getAudio();
        audio.currentTime = 0;
        audio.play().catch(() => {});
        playedSoundIds.add(notificationId);
    }

    function ensureToastContainer() {
        let container = document.getElementById('notification-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-toast-container';
            container.style.position = 'fixed';
            container.style.top = '90px';
            container.style.right = '20px';
            container.style.zIndex = '99999';
            container.style.width = '340px';
            container.style.display = 'flex';
            container.style.flexDirection = 'column';
            container.style.gap = '12px';
            document.body.appendChild(container);
        }
        return container;
    }

    function removeToast(id) {
        const toast = activeToasts.get(id);
        if (!toast) return;

        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-8px)';
        setTimeout(() => {
            toast.remove();
            activeToasts.delete(id);
        }, 220);
    }

    function showToast(notification) {
        if (activeToasts.has(notification.id)) return;

        const container = ensureToastContainer();

        if (activeToasts.size >= MAX_VISIBLE_TOASTS) {
            const firstKey = activeToasts.keys().next().value;
            removeToast(firstKey);
        }

        const toast = document.createElement('div');
        toast.className = 'vertexgrad-live-toast';
        toast.style.background = 'white';
        toast.style.border = '1px solid rgba(0,0,0,0.08)';
        toast.style.borderRadius = '16px';
        toast.style.padding = '14px 16px';
        toast.style.boxShadow = '0 14px 30px rgba(0,0,0,0.10)';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        toast.style.transition = 'all 0.22s ease';
        toast.style.cursor = 'pointer';

        const safeTitle = notification.title ?? 'Notification';
        const safeMessage = notification.message ?? '';
        const safeTime = notification.created_at_human ?? 'Just now';
        const safeIcon = notification.icon ?? 'fas fa-bell';

        toast.innerHTML = `
            <div style="display:flex; gap:12px; align-items:flex-start;">
                <div style="width:38px;height:38px;border-radius:12px;background:rgba(27,0,255,0.08);display:flex;align-items:center;justify-content:center;color:#1b00ff;flex-shrink:0;">
                    <i class="${safeIcon}"></i>
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:700; font-size:13px; color:#18243a;">${escapeHtml(safeTitle)}</div>
                    <div style="font-size:12px; color:#6b7a90; margin-top:4px; line-height:1.5;">${escapeHtml(safeMessage)}</div>
                    <div style="font-size:11px; color:#94a3b8; margin-top:8px;">${escapeHtml(safeTime)}</div>
                </div>
                <button type="button" style="border:0;background:transparent;font-size:16px;line-height:1;cursor:pointer;color:#94a3b8;" aria-label="Close">×</button>
            </div>
        `;

        const closeBtn = toast.querySelector('button');
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            removeToast(notification.id);
        });

        toast.addEventListener('click', () => {
            if (notification.url) {
                window.location.href = notification.url;
            } else {
                removeToast(notification.id);
            }
        });

        container.appendChild(toast);
        activeToasts.set(notification.id, toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });

        setTimeout(() => removeToast(notification.id), 5000);
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function updateBellBadge(unreadCount, badgeSelector, textSelector, hiddenClass = 'hidden') {
        const badge = document.querySelector(badgeSelector);
        const unreadText = document.querySelector(textSelector);

        if (unreadText) {
            unreadText.textContent = unreadCount;
        }

        if (badge) {
            if (unreadCount > 0) {
                badge.classList.remove(hiddenClass, 'd-none');
                badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
            } else {
                if (hiddenClass) {
                    badge.classList.add(hiddenClass);
                } else {
                    badge.classList.add('d-none');
                }
            }
        }
    }

    function syncDropdownList(notifications, listSelector, readRouteBuilder) {
        const list = document.querySelector(listSelector);
        if (!list) return;

        list.innerHTML = '';

        if (!notifications.length) {
            list.innerHTML = `<div class="px-3 py-4 text-center small" style="color:#94a3b8;">No notifications yet</div>`;
            return;
        }

        notifications.forEach((notification) => {
            const item = document.createElement('form');
            item.method = 'POST';
            item.action = readRouteBuilder(notification.id);
            item.className = 'm-0';

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

            item.innerHTML = `
                <input type="hidden" name="_token" value="${csrf}">
                <input type="hidden" name="redirect" value="${notification.url ?? ''}">
                <button type="submit"
                        class="dropdown-item px-3 py-3 border-0 border-bottom text-wrap w-100 text-start"
                        style="background:${notification.read_at ? 'var(--vg-dropdown-bg, #fff)' : 'var(--vg-surface-soft, #f8fafc)'}; border-radius:0;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:36px;height:36px;border-radius:12px;background:rgba(27,0,255,0.08);color:#1b00ff;">
                            <i class="${notification.icon ?? 'fas fa-bell'}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">${escapeHtml(notification.title ?? 'Notification')}</div>
                            <div class="small mt-1" style="line-height:1.45;">${escapeHtml(notification.message ?? '')}</div>
                            <div class="small mt-2" style="color:#94a3b8;">${escapeHtml(notification.created_at_human ?? 'Just now')}</div>
                        </div>
                    </div>
                </button>
            `;

            list.appendChild(item);
        });
    }

    async function fetchLatest(config) {
        const response = await fetch(config.latestUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) return;

        const data = await response.json();
        const notifications = Array.isArray(data.notifications) ? data.notifications : [];
        const unreadCount = Number(data.unread_count ?? 0);

        updateBellBadge(
            unreadCount,
            config.badgeSelector,
            config.unreadTextSelector,
            config.hiddenClass ?? 'hidden'
        );

        syncDropdownList(notifications, config.listSelector, config.readRouteBuilder);

        notifications.forEach((notification) => {
            const isUnread = !notification.read_at;

            if (!seenIds.has(notification.id)) {
                seenIds.add(notification.id);

                if (!isInitialLoad && isUnread) {
                    showToast(notification);
                    playSoundOnce(notification.id);
                }
            }
        });

        isInitialLoad = false;
    }

    function start(config) {
        if (!config || !config.latestUrl) return;

        fetchLatest(config).catch(() => {});

        if (pollTimer) {
            clearInterval(pollTimer);
        }

        pollTimer = setInterval(() => {
            fetchLatest(config).catch(() => {});
        }, POLL_INTERVAL);
    }

    return {
        start,
        soundEnabled,
        setSoundEnabled,
    };
})();

export default NotificationCenter;