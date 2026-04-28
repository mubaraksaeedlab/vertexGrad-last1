<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>{{ setting('platform_name', 'VertexGrad') }} - @yield('title', __('backend.manager_layout.default_title'))</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <link rel="apple-touch-icon"
          href="{{ setting('platform_logo') ? asset('storage/' . setting('platform_logo')) : asset('vendors/images/apple-touch-icon.png') }}" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendors/styles/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @php
        $currentLocale = app()->getLocale();
        $isRtl = $currentLocale === 'ar';
    @endphp

    @stack('styles')

    <style>
        :root {
            --vg-primary: #1b00ff;
            --vg-primary-dark: #1400c8;

            --vg-bg: #f3f6fb;
            --vg-surface: #ffffff;
            --vg-surface-soft: #f8fafc;
            --vg-text: #18243a;
            --vg-text-muted: #6b7a90;
            --vg-border: #e2e8f0;

            --vg-shadow-sm: 0 10px 24px rgba(15, 23, 42, 0.06);
            --vg-shadow-md: 0 16px 36px rgba(15, 23, 42, 0.10);

            --sidebar-width: 290px;
            --header-height: 76px;

            --vg-header-bg: #ffffff;
            --vg-header-color: #18243a;
            --vg-header-btn-bg: #f8fafc;
            --vg-header-btn-border: #e2e8f0;

            --vg-sidebar-bg: #ffffff;
            --vg-sidebar-color: #18243a;
            --vg-sidebar-muted: #7b8aa0;
            --vg-sidebar-hover: #f2f6fc;
            --vg-sidebar-active-bg: rgba(27, 0, 255, 0.08);
            --vg-sidebar-active-color: #1b00ff;
            --vg-sidebar-border: #edf2f7;
            --vg-sidebar-bottom-bg: #fbfdff;
            --vg-sidebar-card-bg: #f8fafc;
            --vg-sidebar-card-border: #e2e8f0;

            --vg-header-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
            --vg-sidebar-shadow: 8px 0 26px rgba(15, 23, 42, 0.08);

            --vg-dropdown-bg: #ffffff;
            --vg-footer-bg: #ffffff;
        }

        body.dark-theme {
            --vg-bg: #08111f;
            --vg-surface: #0f172a;
            --vg-surface-soft: #111c31;
            --vg-text: #e5eefc;
            --vg-text-muted: #94a3b8;
            --vg-border: rgba(255,255,255,0.08);

            --vg-header-bg: linear-gradient(135deg, #081534 0%, #1200b8 100%);
            --vg-header-color: #ffffff;
            --vg-header-btn-bg: rgba(255,255,255,0.12);
            --vg-header-btn-border: rgba(255,255,255,0.10);

            --vg-sidebar-bg: linear-gradient(180deg, #061127 0%, #0b1d49 100%);
            --vg-sidebar-color: rgba(255,255,255,0.86);
            --vg-sidebar-muted: rgba(255,255,255,0.50);
            --vg-sidebar-hover: rgba(255,255,255,0.10);
            --vg-sidebar-active-bg: linear-gradient(90deg, rgba(27,0,255,0.36) 0%, rgba(79,70,229,0.18) 100%);
            --vg-sidebar-active-color: #ffffff;
            --vg-sidebar-border: rgba(255,255,255,0.07);
            --vg-sidebar-bottom-bg: rgba(255,255,255,0.03);
            --vg-sidebar-card-bg: rgba(255,255,255,0.05);
            --vg-sidebar-card-border: rgba(255,255,255,0.08);

            --vg-header-shadow: 0 10px 28px rgba(9, 16, 45, 0.18);
            --vg-sidebar-shadow: 8px 0 26px rgba(10, 16, 39, 0.18);

            --vg-dropdown-bg: #0f172a;
            --vg-footer-bg: #0f172a;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--vg-bg);
            color: var(--vg-text);
            overflow-x: hidden;
            transition: background 0.25s ease, color 0.25s ease;
        }

        body {
            position: relative;
        }

        #flash-messages {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            min-width: 280px;
            max-width: 360px;
        }

        .header,
        .left-side-bar,
        .main-container,
        .footer {
            box-sizing: border-box;
        }

        .header,
        .left-side-bar,
        .main-container {
            margin: 0 !important;
        }

        .header {
            position: fixed !important;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            width: auto !important;
            height: var(--header-height);
            min-height: var(--header-height);
            z-index: 1000;
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            background: var(--vg-header-bg) !important;
            border-bottom: 1px solid var(--vg-border) !important;
            box-shadow: var(--vg-header-shadow);
            transition: all 0.25s ease;
        }

        body.sidebar-closed .header {
            left: 0 !important;
            width: 100% !important;
        }

        body.sidebar-closed .main-container {
            margin-left: 0 !important;
            width: 100% !important;
        }

        body.sidebar-closed .left-side-bar {
            transform: translateX(-100%) !important;
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: none !important;
        }

        .left-side-bar {
            position: fixed !important;
            top: 0;
            left: 0;
            width: var(--sidebar-width) !important;
            min-width: var(--sidebar-width) !important;
            max-width: var(--sidebar-width) !important;
            height: 100vh !important;
            z-index: 1001;
            display: flex !important;
            flex-direction: column;
            background: var(--vg-sidebar-bg) !important;
            box-shadow: var(--vg-sidebar-shadow);
            overflow: hidden;
            transition: transform 0.25s ease, opacity 0.25s ease !important;
            border-right: 1px solid var(--vg-sidebar-border);
        }

        .main-container {
            margin-left: var(--sidebar-width) !important;
            margin-top: var(--header-height) !important;
            width: calc(100% - var(--sidebar-width)) !important;
            min-height: calc(100vh - var(--header-height));
            background: var(--vg-bg);
            padding: 28px 0 24px;
            transition: background 0.25s ease;
        }

        .app-content-wrap {
            padding: 0 22px;
        }

        .header-left,
        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-left .menu-icon,
        .header-left .search-toggle-icon,
        .header-right .header-circle-btn,
        .theme-switch-btn,
        .language-switch-btn {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            background: var(--vg-header-btn-bg);
            border: 1px solid var(--vg-header-btn-border);
            color: var(--vg-header-color);
            cursor: pointer;
            transition: all 0.18s ease;
            flex-shrink: 0;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
        }

        .header-left .menu-icon:hover,
        .header-left .search-toggle-icon:hover,
        .header-right .header-circle-btn:hover,
        .theme-switch-btn:hover,
        .language-switch-btn:hover {
            transform: translateY(-1px);
        }

        .language-switch-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .language-switch-btn.active {
            background: var(--vg-primary);
            border-color: var(--vg-primary);
            color: #fff;
        }

        .user-info-dropdown .dropdown-toggle {
            padding: 6px 10px 6px 8px;
            border-radius: 15px;
            border: 1px solid var(--vg-header-btn-border);
            background: var(--vg-header-btn-bg);
            color: var(--vg-header-color) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .user-info-dropdown .dropdown-toggle::after {
            margin-left: 10px;
            color: var(--vg-header-color);
        }

        .left-side-bar .brand-logo {
            min-height: 82px;
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 18px 16px;
            border-bottom: 1px solid var(--vg-sidebar-border);
            flex-shrink: 0;
        }

        .sidebar-logo-link {
            display: flex !important;
            align-items: center;
            text-decoration: none;
            max-width: calc(100% - 44px);
        }

        .sidebar-logo-img {
            max-height: 42px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            display: block;
        }

        .left-side-bar .close-sidebar {
            width: 34px;
            height: 34px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: var(--vg-sidebar-color);
            cursor: pointer;
            transition: all 0.18s ease;
            flex-shrink: 0;
        }

        .left-side-bar .close-sidebar:hover {
            background: var(--vg-sidebar-hover);
        }

        .menu-block {
            flex: 1;
            min-height: 0;
            padding-top: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .sidebar-section-label {
            padding: 0 18px;
            margin: 8px 0 12px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--vg-sidebar-muted);
        }

        #accordion-menu {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 12px 18px !important;
        }

        #accordion-menu li {
            list-style: none !important;
            margin: 0 0 8px 0 !important;
            padding: 0 !important;
        }

        #accordion-menu li a,
        #accordion-menu li a.dropdown-toggle,
        #accordion-menu li a.no-arrow {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 12px !important;
            width: 100% !important;
            min-height: 52px !important;
            padding: 14px 14px !important;
            border-radius: 14px !important;
            color: var(--vg-sidebar-color) !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            line-height: 1.2 !important;
            transition: all 0.18s ease !important;
            overflow: hidden !important;
        }

        #accordion-menu li a.dropdown-toggle::before,
        #accordion-menu li a.dropdown-toggle::after,
        #accordion-menu li a.no-arrow::before,
        #accordion-menu li a.no-arrow::after {
            display: none !important;
            content: none !important;
        }

        #accordion-menu li a:hover {
            background: var(--vg-sidebar-hover) !important;
            transform: translateX(2px);
        }

        #accordion-menu li a .micon {
            position: static !important;
            transform: none !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 20px !important;
            min-width: 20px !important;
            height: 20px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 17px !important;
            color: inherit !important;
            flex-shrink: 0 !important;
        }

        #accordion-menu li a .mtext {
            position: static !important;
            transform: none !important;
            margin: 0 !important;
            padding: 0 !important;
            flex: 1 !important;
            min-width: 0 !important;
            color: inherit !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        #accordion-menu li a.active,
        #accordion-menu li a.current,
        #accordion-menu li a[aria-expanded="true"] {
            background: var(--vg-sidebar-active-bg) !important;
            color: var(--vg-sidebar-active-color) !important;
            box-shadow: inset 0 0 0 1px rgba(27,0,255,0.05);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 14px 12px 16px;
            border-top: 1px solid var(--vg-sidebar-border);
            background: var(--vg-sidebar-bottom-bg);
            flex-shrink: 0;
        }

        .sidebar-bottom-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--vg-sidebar-muted);
            margin-bottom: 10px;
            padding: 0 6px;
        }

        .sidebar-account-card {
            background: var(--vg-sidebar-card-bg);
            border: 1px solid var(--vg-sidebar-card-border);
            border-radius: 16px;
            padding: 12px;
        }

        .sidebar-account-top {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .sidebar-account-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(148,163,184,0.18);
            flex-shrink: 0;
        }

        .sidebar-account-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--vg-sidebar-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-account-role {
            margin-top: 2px;
            font-size: 11px;
            color: var(--vg-sidebar-muted);
        }

        .sidebar-account-links {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-account-links a {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 42px;
            padding: 10px 12px;
            border-radius: 12px;
            color: var(--vg-sidebar-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.18s ease;
        }

        .sidebar-account-links a:hover {
            background: var(--vg-sidebar-hover);
        }

        .dropdown-menu {
            border: 1px solid var(--vg-border);
            box-shadow: var(--vg-shadow-md);
            padding: 10px;
            border-radius: 16px;
            background: var(--vg-dropdown-bg);
        }

        .dropdown-item {
            min-height: 42px;
            display: flex;
            align-items: center;
            border-radius: 10px;
            font-weight: 500;
            color: var(--vg-text);
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: var(--vg-surface-soft);
            color: var(--vg-text);
        }

        .btn,
        .form-control,
        .form-select,
        .dropdown-menu {
            border-radius: 12px;
        }

        .btn {
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--vg-primary);
            border-color: var(--vg-primary);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--vg-primary-dark) !important;
            border-color: var(--vg-primary-dark) !important;
        }

        .app-footer {
            margin: 18px 22px 0;
            padding: 14px 18px;
            background: var(--vg-footer-bg);
            border: 1px solid var(--vg-border);
            border-radius: 16px;
            box-shadow: var(--vg-shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: var(--vg-text-muted);
            font-size: 13px;
            transition: background 0.25s ease, color 0.25s ease, border-color 0.25s ease;
        }

        .app-footer strong {
            color: var(--vg-text);
        }

        html[dir="rtl"] body {
            text-align: right;
        }

        html[dir="rtl"] #flash-messages {
            right: auto;
            left: 20px;
        }

        html[dir="rtl"] .header {
            left: 0;
            right: var(--sidebar-width);
        }

        html[dir="rtl"] body.sidebar-closed .header {
            right: 0 !important;
            left: 0 !important;
        }

        html[dir="rtl"] .left-side-bar {
            left: auto;
            right: 0;
            border-right: 0;
            border-left: 1px solid var(--vg-sidebar-border);
        }

        html[dir="rtl"] body.sidebar-closed .left-side-bar {
            transform: translateX(100%) !important;
        }

        html[dir="rtl"] .main-container {
            margin-left: 0 !important;
            margin-right: var(--sidebar-width) !important;
        }

        html[dir="rtl"] body.sidebar-closed .main-container {
            margin-right: 0 !important;
            width: 100% !important;
        }

        html[dir="rtl"] .user-info-dropdown .dropdown-toggle::after {
            margin-left: 0;
            margin-right: 10px;
        }

        html[dir="rtl"] .dropdown-menu.dropdown-menu-end {
            right: auto !important;
            left: 0 !important;
        }

        @media (max-width: 991.98px) {
            .header {
                left: 0 !important;
                width: 100% !important;
            }

            .left-side-bar {
                transform: translateX(-100%);
                opacity: 0;
            }

            .main-container {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .app-content-wrap {
                padding: 0 14px;
            }

            .app-footer {
                margin: 18px 14px 0;
                padding: 12px 14px;
                flex-direction: column;
                align-items: flex-start;
            }

            html[dir="rtl"] .left-side-bar {
                transform: translateX(100%);
            }

            html[dir="rtl"] .main-container {
                margin-right: 0 !important;
                width: 100% !important;
            }

            html[dir="rtl"] .header {
                right: 0 !important;
                left: 0 !important;
            }
        }
    </style>

    <script>
        function showToast(message, type = 'success') {
            const flashDiv = document.getElementById('flash-messages');
            if (!flashDiv) return;

            const toast = document.createElement('div');
            toast.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
            toast.style.marginBottom = '10px';
            toast.style.borderRadius = '14px';
            toast.innerHTML = message + `<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('backend.common.close') }}"></button>`;
            flashDiv.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 200);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                showToast(@json(session('success')), 'success');
            @endif

            @if(session('error'))
                showToast(@json(session('error')), 'danger');
            @endif
        });
    </script>
</head>
<body>

<div id="flash-messages"></div>

@php
    $adminUser = auth('admin')->user();
    $unreadCount = $adminUser ? $adminUser->unreadNotifications()->count() : 0;
    $latestNotifications = $adminUser ? $adminUser->notifications()->latest()->take(5)->get() : collect();
@endphp

<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list" data-toggle="left-sidebar-toggle" title="{{ __('backend.manager_layout.toggle_sidebar') }}"></div>
        <div class="search-toggle-icon bi bi-search" title="{{ __('backend.manager_layout.search') }}"></div>
    </div>

    <div class="header-right">
        <div class="language-switch-group">
            <a href="{{ route('admin.language.switch', 'en') }}"
               class="language-switch-btn {{ $currentLocale === 'en' ? 'active' : '' }}"
               title="{{ __('backend.manager_layout.switch_to_english') }}">
                EN
            </a>
            <a href="{{ route('admin.language.switch', 'ar') }}"
               class="language-switch-btn {{ $currentLocale === 'ar' ? 'active' : '' }}"
               title="{{ __('backend.manager_layout.switch_to_arabic') }}">
                AR
            </a>
        </div>

        <button type="button" class="theme-switch-btn" id="themeSwitchBtn" title="{{ __('backend.manager_layout.change_theme') }}">
            <i class="bi bi-moon-stars-fill" id="themeSwitchIcon"></i>
        </button>

        <div
            class="dropdown"
            id="admin-notification-bell"
            data-count-url="{{ route('admin.notifications.count') }}"
            data-latest-url="{{ route('admin.notifications.latest') }}"
        >
            <a class="dropdown-toggle position-relative d-inline-flex align-items-center justify-content-center text-decoration-none header-circle-btn"
               href="#"
               role="button"
               data-bs-toggle="dropdown"
               aria-expanded="false">
                <i class="icon-copy dw dw-notification" style="font-size: 18px;"></i>
                <span id="adminUnreadBadge"
                      class="badge bg-danger rounded-circle d-flex align-items-center justify-content-center {{ $unreadCount > 0 ? '' : 'd-none' }}"
                      style="position:absolute; top:-5px; right:-7px; min-width:18px; height:18px; font-size:10px; padding:0;">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-end p-0 border-0"
                 style="width: 360px; max-width: 360px; border-radius: 18px; overflow: hidden; background: var(--vg-dropdown-bg);">
                <div class="d-flex justify-content-between align-items-center px-3 py-3"
                     style="border-bottom: 1px solid var(--vg-border); background: var(--vg-dropdown-bg);">
                    <div>
                        <h6 class="mb-0 fw-bold" style="color: var(--vg-text);">{{ __('backend.manager_layout.notifications') }}</h6>
                        <small style="color: var(--vg-text-muted);">
                            <span id="adminUnreadText">{{ $unreadCount }}</span> {{ __('backend.manager_layout.unread') }}
                        </small>
                    </div>
                </div>

                <div id="adminNotificationList" style="max-height: 340px; overflow-y: auto; background: var(--vg-dropdown-bg);">
                    @forelse($latestNotifications as $notification)
                        @php
                      $key = $notification->data['key'] ?? null;

$title = $key
    ? __("backend.notifications.types.{$key}.title", $notification->data)
    : ($notification->data['title'] ?? __('backend.notifications.notification'));

$message = $key
    ? __("backend.notifications.types.{$key}.message", $notification->data)
    : ($notification->data['message'] ?? '');
                            $url = $notification->data['url'] ?? route('admin.notifications.index');
                            $icon = $notification->data['icon'] ?? 'fas fa-bell';
                            $isRead = !is_null($notification->read_at);
                        @endphp

                        <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}" class="m-0">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ $url }}">
                            <button type="submit"
                                    class="dropdown-item px-3 py-3 border-0 border-bottom text-wrap w-100 text-start"
                                    style="background: {{ $isRead ? 'var(--vg-dropdown-bg)' : 'var(--vg-surface-soft)' }}; border-radius: 0; border-color: var(--vg-border) !important;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width: 36px; height: 36px; border-radius: 12px; background: rgba(27,0,255,0.08); color: var(--vg-primary);">
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
                        <div class="px-3 py-4 text-center small" style="color: var(--vg-text-muted); background: var(--vg-dropdown-bg);">
                            {{ __('backend.manager_layout.no_notifications_yet') }}
                        </div>
                    @endforelse
                </div>

                <div class="d-grid" style="grid-template-columns: 1fr 1fr; background: var(--vg-dropdown-bg);">
                    <a href="{{ route('admin.notifications.index') }}"
                       class="btn btn-light rounded-0 border-0 py-2"
                       style="border-top: 1px solid var(--vg-border); border-right: 1px solid var(--vg-border);">
                        {{ __('backend.manager_layout.history') }}
                    </a>
                    <form method="POST" action="{{ route('admin.notifications.markAllRead') }}" class="m-0">
                        @csrf
                        <button type="submit"
                                class="btn btn-light rounded-0 border-0 py-2"
                                style="border-top: 1px solid var(--vg-border);">
                            {{ __('backend.manager_layout.mark_all_read') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="dropdown user-info-dropdown">
            <a class="dropdown-toggle d-flex align-items-center gap-2"
               href="#"
               role="button"
               data-bs-toggle="dropdown"
               aria-expanded="false">
                <img
                    src="{{ !empty($adminUser?->profile_image) ? asset('storage/' . $adminUser->profile_image) : asset('vendors/images/photo1.jpg') }}"
                    alt="{{ __('backend.manager_layout.user_avatar') }}"
                    class="sidebar-account-avatar"
                >
                <div class="d-none d-md-block text-start">
                    <div style="font-size: 13px; font-weight: 700; color: var(--vg-header-color); line-height: 1.2;">
                        {{ $adminUser ? $adminUser->name : __('backend.manager_layout.guest') }}
                    </div>
                    <div style="font-size: 11px; color: var(--vg-text-muted); line-height: 1.2;">
                        {{ __('backend.manager_layout.administrator') }}
                    </div>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end border-0" style="min-width: 230px;">
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.profile') }}"><i class="bi bi-person"></i><span>{{ __('backend.manager_layout.profile') }}</span></a></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear"></i><span>{{ __('backend.manager_layout.settings') }}</span></a></li>
                <li><hr class="dropdown-divider my-2"></li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 text-danger"
                       href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{ __('backend.manager_layout.log_out') }}</span>
                    </a>
                </li>
            </ul>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>

<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('manager.dashboard') }}" class="sidebar-logo-link">
            <img src="{{ setting('admin_logo') ? asset('storage/' . setting('admin_logo')) : (setting('platform_logo') ? asset('storage/' . setting('platform_logo')) : asset('images/logo.png')) }}"
                 alt="{{ setting('platform_name', 'VertexGrad') }}"
                 class="sidebar-logo-img" />
        </a>

        <div class="close-sidebar" data-toggle="left-sidebar-close" title="{{ __('backend.manager_layout.close_sidebar') }}">
            <i class="ion-close-round"></i>
        </div>
    </div>

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <div class="sidebar-section-label">{{ __('backend.manager_layout.main_navigation') }}</div>

            <ul id="accordion-menu">
                <li>
                    <a href="{{ route('manager.dashboard') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                        <span class="micon bi bi-house-door-fill"></span>
                        <span class="mtext">{{ __('backend.manager_layout.dashboard') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('manager.pending.users') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('manager.pending.users') ? 'active' : '' }}">
                        <span class="micon bi bi-people-fill"></span>
                        <span class="mtext">{{ __('backend.manager_layout.user_management') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('manager.index') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('manager.index') ? 'active' : '' }}">
                        <span class="micon bi bi-person-workspace"></span>
                        <span class="mtext">{{ __('backend.manager_layout.managers') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.students.index') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        <span class="micon bi bi-mortarboard-fill"></span>
                        <span class="mtext">{{ __('backend.manager_layout.students') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.investors.index') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('admin.investors.*') ? 'active' : '' }}">
                        <span class="micon bi bi-wallet2"></span>
                        <span class="mtext">{{ __('backend.manager_layout.investors') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.investment-requests.index') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('admin.investment-requests.*') ? 'active' : '' }}">
                        <span class="micon bi bi-cash-coin"></span>
                        <span class="mtext">{{ __('backend.manager_layout.investment_requests') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.projects.index') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        <span class="micon bi bi-briefcase-fill"></span>
                        <span class="mtext">{{ __('backend.manager_layout.projects') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.contact-messages.index') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                        <span class="micon bi bi-envelope-paper-fill"></span>
                        <span class="mtext">{{ __('backend.manager_layout.contact_messages') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('manager.calendar.index') }}"
                       class="dropdown-toggle no-arrow {{ request()->routeIs('manager.calendar.*') ? 'active' : '' }}">
                        <span class="micon bi bi-calendar-check-fill"></span>
                        <span class="mtext">{{ __('backend.manager_layout.calendar') }}</span>
                    </a>
                </li>

                @if(Route::has('admin.projects.final-decisions.index'))
                    <li>
                        <a href="{{ route('admin.projects.final-decisions.index') }}"
                           class="dropdown-toggle no-arrow {{ request()->routeIs('admin.projects.final-decisions.*') ? 'active' : '' }}">
                            <span class="micon bi bi-check2-square"></span>
                            <span class="mtext">{{ __('backend.manager_layout.final_decisions') }}</span>
                        </a>
                    </li>
                @endif

                @if(Route::has('admin.reports.index'))
                    <li>
                        <a href="{{ route('admin.reports.index') }}"
                           class="dropdown-toggle no-arrow {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <span class="micon bi bi-bar-chart-line-fill"></span>
                            <span class="mtext">{{ __('backend.manager_layout.reports') }}</span>
                        </a>
                    </li>
                @endif

                @if(Route::has('admin.permissions.index'))
                    <li>
                        <a href="{{ route('admin.permissions.index') }}"
                           class="dropdown-toggle no-arrow {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                            <span class="micon bi bi-shield-lock-fill"></span>
                            <span class="mtext">{{ __('backend.manager_layout.permissions') }}</span>
                        </a>
                    </li>
 @endif
@if(Route::has('admin.auth-role-policies.index'))
    <li>
        <a href="{{ route('admin.auth-role-policies.index') }}"
           class="dropdown-toggle no-arrow {{ request()->routeIs('admin.auth-role-policies.*') ? 'active' : '' }}">
            <span class="micon fa fa-shield-alt"></span>
            <span class="mtext">{{ __('backend.manager_layout.role_auth_policies') }}</span>
        </a>
    </li>
@endif

@if(Route::has('admin.auth-policies.index'))
    <li>
        <a href="{{ route('admin.auth-policies.index') }}"
           class="dropdown-toggle no-arrow {{ request()->routeIs('admin.auth-policies.*') ? 'active' : '' }}">
            <span class="micon fa fa-lock"></span>
            <span class="mtext">{{ __('backend.manager_layout.user_auth_policies') }}</span>
        </a>
    </li>
@endif
                @if(Route::has('admin.announcements.index'))
                    <li>
                        <a href="{{ route('admin.announcements.index') }}"
                           class="dropdown-toggle no-arrow {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                            <span class="micon bi bi-megaphone-fill"></span>
                            <span class="mtext">{{ __('backend.manager_layout.announcements') }}</span>
                        </a>
                    </li>
                @endif

                @if(Route::has('admin.audit-logs.index'))
                    <li>
                        <a href="{{ route('admin.audit-logs.index') }}"
                           class="dropdown-toggle no-arrow {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                            <span class="micon bi bi-clipboard-data-fill"></span>
                            <span class="mtext">{{ __('backend.manager_layout.audit_center') }}</span>
                        </a>
                    </li>
                @endif

                @if(Route::has('settings.index'))
                    <li>
                        <a href="{{ route('admin.settings.index') }}"
                           class="dropdown-toggle no-arrow {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <span class="micon bi bi-gear-fill"></span>
                            <span class="mtext">{{ __('backend.manager_layout.settings') }}</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-bottom">
                <div class="sidebar-bottom-title">{{ __('backend.manager_layout.account') }}</div>

                <div class="sidebar-account-card">
                    <div class="sidebar-account-top">
                        <img
                            src="{{ !empty($adminUser?->profile_image) ? asset('storage/' . $adminUser->profile_image) : asset('vendors/images/photo1.jpg') }}"
                            alt="{{ __('backend.manager_layout.user_avatar') }}"
                            class="sidebar-account-avatar"
                        >
                        <div>
                            <div class="sidebar-account-name">{{ $adminUser ? $adminUser->name : __('backend.manager_layout.guest') }}</div>
                            <div class="sidebar-account-role">{{ __('backend.manager_layout.administrator') }}</div>
                        </div>
                    </div>

                    <div class="sidebar-account-links">
                        <a href="{{ route('admin.profile') }}">
                            <span class="micon bi bi-person-circle"></span>
                            <span class="mtext">{{ __('backend.manager_layout.profile') }}</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}">
                            <span class="micon bi bi-gear"></span>
                            <span class="mtext">{{ __('backend.manager_layout.settings') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-container">
    <div class="app-content-wrap">
        @yield('content')
    </div>

    <div class="app-footer">
        <div><strong>{{ setting('platform_name', 'VertexGrad') }}</strong> {{ __('backend.manager_layout.manager_panel') }}</div>
        <div>{{ setting('platform_tagline', __('backend.manager_layout.footer_tagline')) }}</div>
    </div>
</div>

<script src="{{ asset('vendors/scripts/core.js') }}"></script>
<script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
<script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
<script src="{{ asset('vendors/scripts/process.js') }}" defer></script>
<script src="{{ asset('src/plugins/apexcharts/apexcharts.min.js') }}" defer></script>
<script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}" defer></script>
<script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}" defer></script>
<script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}" defer></script>
<script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const sidebar = document.querySelector('.left-side-bar');
    const themeSwitchBtn = document.getElementById('themeSwitchBtn');
    const themeSwitchIcon = document.getElementById('themeSwitchIcon');

    const savedTheme = localStorage.getItem('vertex-theme');

    function syncThemeIcon() {
        if (!themeSwitchIcon) return;
        themeSwitchIcon.className = body.classList.contains('dark-theme')
            ? 'bi bi-sun-fill'
            : 'bi bi-moon-stars-fill';
    }

    if (savedTheme === 'dark') {
        body.classList.add('dark-theme');
    }
    syncThemeIcon();

    function openLeftSidebar() {
        if (!sidebar) return;
        body.classList.remove('sidebar-closed');
        sidebar.classList.remove('closed');
    }

    function closeLeftSidebar() {
        if (!sidebar) return;
        body.classList.add('sidebar-closed');
        sidebar.classList.add('closed');
    }

    function toggleLeftSidebar() {
        if (!sidebar) return;

        if (body.classList.contains('sidebar-closed')) {
            openLeftSidebar();
        } else {
            closeLeftSidebar();
        }
    }

    function toggleTheme() {
        body.classList.toggle('dark-theme');
        localStorage.setItem('vertex-theme', body.classList.contains('dark-theme') ? 'dark' : 'light');
        syncThemeIcon();
    }

    document.querySelectorAll('[data-toggle="left-sidebar-toggle"]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            toggleLeftSidebar();
        });
    });

    document.querySelectorAll('[data-toggle="left-sidebar-close"]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            closeLeftSidebar();
        });
    });

    if (themeSwitchBtn) {
        themeSwitchBtn.addEventListener('click', function () {
            toggleTheme();
        });
    }

    if (window.innerWidth <= 991) {
        closeLeftSidebar();
    } else {
        openLeftSidebar();
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth <= 991) {
            closeLeftSidebar();
        }
    });
});
</script>

@stack('scripts')
</body>
</html>