<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>@yield('title', __('backend.supervisor_layout.default_title'))</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendors/styles/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @php
        use App\Models\Announcement;

        $adminUser = auth('admin')->user();
        $currentLocale = app()->getLocale();
        $isRtl = $currentLocale === 'ar';

        $layoutAnnouncements = Announcement::published()
            ->where(function ($query) {
                $query->where('audience', 'all')
                    ->orWhere('audience', 'supervisors');
            })
            ->ordered()
            ->take(3)
            ->get();

        $unreadCount = $adminUser ? $adminUser->unreadNotifications()->count() : 0;
        $latestNotifications = $adminUser
            ? $adminUser->notifications()->latest()->take(5)->get()
            : collect();

        $user = auth('admin')->user();

        $canReviewProjects = $user && $user->hasPermission('review_projects');
        $canViewMeetings = $user && $user->hasPermission('view_meetings');
        $canManageMeetings = $user && $user->hasPermission('manage_meetings');
        $canViewRequests = $user && $user->hasPermission('view_requests');
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
            --vg-shadow-lg: 8px 0 26px rgba(15, 23, 42, 0.08);

            --sidebar-width: 300px;
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

            --vg-right-sidebar-bg: #ffffff;
            --vg-dropdown-bg: #ffffff;
            --vg-panel-bg: #ffffff;

            --vg-scrollbar-thumb: rgba(148, 163, 184, 0.45);
            --vg-scrollbar-track: transparent;
        }

        body.dark-theme {
            --vg-bg: #07111f;
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
            --vg-sidebar-color: rgba(255,255,255,0.90);
            --vg-sidebar-muted: rgba(255,255,255,0.52);
            --vg-sidebar-hover: rgba(255,255,255,0.08);
            --vg-sidebar-active-bg: linear-gradient(90deg, rgba(27,0,255,0.36) 0%, rgba(79,70,229,0.18) 100%);
            --vg-sidebar-active-color: #ffffff;
            --vg-sidebar-border: rgba(255,255,255,0.07);
            --vg-sidebar-bottom-bg: rgba(255,255,255,0.03);
            --vg-sidebar-card-bg: rgba(255,255,255,0.05);
            --vg-sidebar-card-border: rgba(255,255,255,0.08);

            --vg-right-sidebar-bg: #0b1220;
            --vg-dropdown-bg: #0f172a;
            --vg-panel-bg: #0f172a;

            --vg-shadow-sm: 0 10px 24px rgba(0, 0, 0, 0.20);
            --vg-shadow-md: 0 16px 36px rgba(0, 0, 0, 0.28);
            --vg-shadow-lg: 8px 0 26px rgba(0, 0, 0, 0.22);

            --vg-scrollbar-thumb: rgba(255,255,255,0.30);
            --vg-scrollbar-track: transparent;
        }

        * {
            box-sizing: border-box;
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

        a {
            text-decoration: none;
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
        .right-sidebar {
            transition: all 0.25s ease;
        }

        .header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            background: var(--vg-header-bg);
            border-bottom: 1px solid var(--vg-border);
            box-shadow: var(--vg-shadow-sm);
        }

        .left-side-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            height: 100vh;
            z-index: 1001;
            display: flex;
            flex-direction: column;
            background: var(--vg-sidebar-bg);
            border-right: 1px solid var(--vg-sidebar-border);
            box-shadow: var(--vg-shadow-lg);
            overflow: hidden;
        }

        .main-container {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            width: calc(100% - var(--sidebar-width));
            min-height: calc(100vh - var(--header-height));
            background: var(--vg-bg);
            padding: 28px 0 24px;
        }

        .app-content-wrap {
            padding: 0 22px;
        }

        body.sidebar-closed .header {
            left: 0;
        }

        body.sidebar-closed .main-container {
            margin-left: 0;
            width: 100%;
        }

        body.sidebar-closed .left-side-bar {
            transform: translateX(-100%);
            pointer-events: none;
        }

        .header-left,
        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-icon,
        .search-toggle-icon,
        .theme-switch-btn,
        .header-circle-btn,
        .header-setting-btn,
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
            font-weight: 700;
            font-size: 12px;
        }

        .menu-icon:hover,
        .search-toggle-icon:hover,
        .theme-switch-btn:hover,
        .header-circle-btn:hover,
        .header-setting-btn:hover,
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
            display: flex;
            align-items: center;
        }

        .user-info-dropdown .dropdown-toggle::after {
            margin-left: 10px;
            color: var(--vg-header-color);
        }

        .brand-logo {
            min-height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 18px 16px;
            border-bottom: 1px solid var(--vg-sidebar-border);
            flex-shrink: 0;
        }

        .sidebar-logo-link {
            display: flex;
            align-items: center;
            max-width: calc(100% - 44px);
        }

        .sidebar-logo-img {
            max-height: 42px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            display: block;
        }

        .close-sidebar {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: var(--vg-sidebar-color);
            cursor: pointer;
            transition: all 0.18s ease;
            flex-shrink: 0;
        }

        .close-sidebar:hover {
            background: var(--vg-sidebar-hover);
        }

        .menu-block {
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        .sidebar-section-label {
            padding: 22px 18px 14px;
            margin: 0;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--vg-sidebar-muted);
            flex-shrink: 0;
        }

        .sidebar-menu-scroll {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0 0 16px 0;
        }

        .sidebar-menu-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-menu-scroll::-webkit-scrollbar-track {
            background: var(--vg-scrollbar-track);
        }

        .sidebar-menu-scroll::-webkit-scrollbar-thumb {
            background: var(--vg-scrollbar-thumb);
            border-radius: 999px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .sidebar-menu-scroll {
            scrollbar-width: thin;
            scrollbar-color: var(--vg-scrollbar-thumb) var(--vg-scrollbar-track);
        }

        #accordion-menu {
            list-style: none;
            margin: 0;
            padding: 0 12px 0 12px;
        }

        #accordion-menu li {
            list-style: none;
            margin: 0 0 10px 0;
            padding: 0;
        }

        #accordion-menu li > a,
        #accordion-menu li > .menu-dropdown-trigger {
            position: relative;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 14px !important;
            width: 100%;
            min-height: 54px;
            padding: 15px 15px;
            border-radius: 15px;
            color: var(--vg-sidebar-color) !important;
            font-weight: 600;
            font-size: 14px;
            line-height: 1.2;
            transition: all 0.18s ease;
            overflow: hidden;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
        }

        #accordion-menu li > a:hover,
        #accordion-menu li > .menu-dropdown-trigger:hover {
            background: var(--vg-sidebar-hover);
            transform: translateX(2px);
        }

        #accordion-menu li > a .micon,
        #accordion-menu li > .menu-dropdown-trigger .micon {
            width: 22px !important;
            min-width: 22px !important;
            max-width: 22px !important;
            height: 22px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 18px !important;
            color: inherit !important;
            flex: 0 0 22px !important;
            margin: 0 !important;
            padding: 0 !important;
            position: static !important;
            transform: none !important;
        }

        #accordion-menu li > a .mtext,
        #accordion-menu li > .menu-dropdown-trigger .mtext {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            position: static !important;
            transform: none !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            color: inherit !important;
        }

        #accordion-menu li > a.active,
        #accordion-menu li.active > a,
        #accordion-menu li.open > .menu-dropdown-trigger {
            background: var(--vg-sidebar-active-bg);
            color: var(--vg-sidebar-active-color) !important;
            box-shadow: inset 0 0 0 1px rgba(27,0,255,0.05);
        }

        .menu-arrow {
            flex: 0 0 auto;
            margin-left: auto;
            font-size: 12px;
            opacity: 0.85;
            transition: transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .dropdown-parent.open > .menu-dropdown-trigger .menu-arrow {
            transform: rotate(180deg);
        }

        .submenu {
            display: none;
            margin: 8px 0 14px 12px;
            padding: 14px;
            border-radius: 18px;
            background: rgba(148, 163, 184, 0.07);
            border: 1px solid var(--vg-sidebar-card-border);
        }

        .dropdown-parent.open > .submenu {
            display: block;
        }

        .submenu li {
            margin-bottom: 9px;
        }

        .submenu li:last-child {
            margin-bottom: 0;
        }

        .submenu li a {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 48px;
            padding: 13px 14px 13px 50px !important;
            border-radius: 13px;
            color: var(--vg-sidebar-color) !important;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.18s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .submenu li a::before {
            content: "";
            position: absolute;
            left: 20px;
            top: 50%;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transform: translateY(-50%);
            background: currentColor;
            opacity: 0.45;
        }

        .submenu li a:hover,
        .submenu li a.active {
            background: var(--vg-sidebar-hover);
            color: var(--vg-sidebar-active-color) !important;
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

        .notification-panel {
            width: 360px;
            max-width: 360px;
            padding: 0;
            overflow: hidden;
            border-radius: 18px;
            border: 1px solid var(--vg-border);
            background: var(--vg-panel-bg);
        }

        .notification-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            border-bottom: 1px solid var(--vg-border);
            background: var(--vg-panel-bg);
        }

        .notification-panel-title {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
            color: var(--vg-text);
        }

        .notification-panel-count {
            font-size: 12px;
            color: var(--vg-text-muted);
            font-weight: 600;
        }

        .notification-panel-body {
            max-height: 330px;
            overflow-y: auto;
            background: var(--vg-panel-bg);
        }

        .notification-item-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 14px 16px;
            border: 0;
            background: var(--vg-panel-bg);
            border-bottom: 1px solid var(--vg-border);
            transition: .2s ease;
            color: inherit;
        }

        .notification-item-btn:hover {
            background: var(--vg-surface-soft);
        }

        .notification-item-btn.unread {
            background: var(--vg-surface-soft);
        }

        .notification-item-icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: rgba(27,0,255,0.08);
            color: var(--vg-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .notification-item-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--vg-text);
            margin-bottom: 3px;
        }

        .notification-item-message {
            font-size: 12px;
            color: var(--vg-text-muted);
            line-height: 1.5;
        }

        .notification-item-time {
            font-size: 11px;
            color: var(--vg-text-muted);
            margin-top: 6px;
        }

        .notification-panel-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-top: 1px solid var(--vg-border);
            background: var(--vg-panel-bg);
        }

        .notification-footer-btn {
            border: 0;
            background: var(--vg-panel-bg);
            padding: 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--vg-text);
            text-decoration: none;
            text-align: center;
            transition: .2s ease;
        }

        .notification-footer-btn:hover {
            background: var(--vg-surface-soft);
            color: var(--vg-primary);
        }

        .notification-footer-btn + .notification-footer-btn {
            border-left: 1px solid var(--vg-border);
        }

        .right-sidebar {
            position: fixed;
            top: 0;
            right: -340px;
            width: 320px;
            height: 100vh;
            z-index: 1400;
            background: var(--vg-right-sidebar-bg);
            border-left: 1px solid var(--vg-border);
            box-shadow: -16px 0 45px rgba(15,23,42,.08);
            transition: right 0.25s ease;
            overflow-y: auto;
        }

        .right-sidebar.open {
            right: 0;
        }

        .right-sidebar .sidebar-title {
            padding: 24px 20px;
            border-bottom: 1px solid var(--vg-border);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .right-sidebar .sidebar-title h3 {
            color: var(--vg-text);
            margin: 0;
        }

        .right-sidebar .sidebar-title span {
            color: var(--vg-text-muted);
        }

        .right-sidebar .close-sidebar {
            background: var(--vg-surface-soft);
            color: var(--vg-text);
            border: 1px solid var(--vg-border);
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .theme-setting-card {
            border: 1px solid var(--vg-border);
            background: var(--vg-surface-soft);
            border-radius: 22px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .theme-setting-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--vg-text);
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .theme-btn-group {
            display: flex;
            gap: 10px;
        }

        .theme-choice-btn {
            flex: 1;
            border: 1px solid var(--vg-border);
            background: var(--vg-surface);
            color: var(--vg-text);
            padding: 12px 14px;
            border-radius: 16px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .theme-choice-btn.active {
            background: linear-gradient(135deg, var(--vg-primary), #4338ca);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 14px 30px rgba(27, 0, 255, .18);
        }

        .announcement-card {
            border: 1px solid var(--vg-border);
            background: var(--vg-surface);
            border-radius: 18px;
            padding: 16px;
            box-shadow: var(--vg-shadow-sm);
        }

        .announcement-card-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--vg-text);
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .announcement-card-text {
            font-size: 13px;
            line-height: 1.7;
            color: var(--vg-text-muted);
            margin-bottom: 0;
        }

        .announcement-meta {
            margin-top: 10px;
            font-size: 12px;
            font-weight: 700;
            color: var(--vg-text-muted);
        }

        .announcement-badge.pinned {
            background: rgba(245, 158, 11, 0.10);
            border: 1px solid rgba(245, 158, 11, 0.22);
            color: #f59e0b;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 10px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .app-footer {
            margin: 18px 22px 0;
            padding: 14px 18px;
            background: var(--vg-surface);
            border: 1px solid var(--vg-border);
            border-radius: 16px;
            box-shadow: var(--vg-shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: var(--vg-text-muted);
            font-size: 13px;
        }

        .app-footer strong {
            color: var(--vg-text);
        }

        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.45);
            backdrop-filter: blur(2px);
            opacity: 0;
            visibility: hidden;
            transition: .25s ease;
            z-index: 999;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
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
            right: 0;
            left: 0;
        }

        html[dir="rtl"] .left-side-bar {
            left: auto;
            right: 0;
            border-right: 0;
            border-left: 1px solid var(--vg-sidebar-border);
        }

        html[dir="rtl"] body.sidebar-closed .left-side-bar {
            transform: translateX(100%);
        }

        html[dir="rtl"] .main-container {
            margin-left: 0;
            margin-right: var(--sidebar-width);
        }

        html[dir="rtl"] body.sidebar-closed .main-container {
            margin-right: 0;
            width: 100%;
        }

        html[dir="rtl"] .user-info-dropdown .dropdown-toggle::after {
            margin-left: 0;
            margin-right: 10px;
        }

        html[dir="rtl"] .menu-arrow {
            margin-left: 0;
            margin-right: auto;
        }

        html[dir="rtl"] .submenu {
            margin: 8px 12px 14px 0;
        }

        html[dir="rtl"] .submenu li a {
            padding: 13px 50px 13px 14px !important;
        }

        html[dir="rtl"] .submenu li a::before {
            left: auto;
            right: 20px;
        }

        html[dir="rtl"] .right-sidebar {
            right: auto;
            left: -340px;
            border-left: 0;
            border-right: 1px solid var(--vg-border);
            box-shadow: 16px 0 45px rgba(15,23,42,.08);
        }

        html[dir="rtl"] .right-sidebar.open {
            left: 0;
        }

        html[dir="rtl"] .notification-footer-btn + .notification-footer-btn {
            border-left: 0;
            border-right: 1px solid var(--vg-border);
        }

        html[dir="rtl"] .dropdown-menu.dropdown-menu-end {
            right: auto !important;
            left: 0 !important;
        }

        @media (max-width: 991.98px) {
            .header {
                left: 0;
                width: 100%;
                padding: 0 16px;
            }

            .left-side-bar {
                transform: translateX(-100%);
                opacity: 0;
            }

            .left-side-bar.mobile-open {
                transform: translateX(0) !important;
                opacity: 1 !important;
            }

            .main-container {
                margin-left: 0;
                width: 100%;
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

            html[dir="rtl"] .header {
                right: 0;
                left: 0;
            }

            html[dir="rtl"] .left-side-bar {
                transform: translateX(100%);
            }

            html[dir="rtl"] .left-side-bar.mobile-open {
                transform: translateX(0) !important;
            }

            html[dir="rtl"] .main-container {
                margin-right: 0;
                width: 100%;
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
    </script>
</head>
<body>

<div id="flash-messages"></div>

<div class="left-side-bar" id="supervisorSidebar">
    <div class="brand-logo">
        <a href="{{ route('supervisor.dashboard') }}" class="sidebar-logo-link">
            <img
                src="{{ !empty($adminUser?->profile_image) ? asset('storage/' . $adminUser->profile_image) : asset('images/logo.png') }}"
                alt="{{ __('backend.supervisor_layout.user_avatar') }}"
                class="sidebar-account-avatar"
            >
        </a>

        <div class="close-sidebar" id="sidebarCloseBtn" title="{{ __('backend.supervisor_layout.close_sidebar') }}">
            <i class="ion-close-round"></i>
        </div>
    </div>

    <div class="menu-block">
        <div class="sidebar-menu">
            <div class="sidebar-section-label">{{ __('backend.supervisor_layout.supervisor_panel') }}</div>

            <div class="sidebar-menu-scroll">
                <ul id="accordion-menu">
                    <li>
                        <a href="{{ route('supervisor.dashboard') }}"
                           class="{{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
                            <span class="micon bi bi-house-door-fill"></span>
                            <span class="mtext">{{ __('backend.supervisor_layout.dashboard') }}</span>
                        </a>
                    </li>

                    @if($canReviewProjects)
                        @php $projectsOpen = request()->routeIs('supervisor.projects.*'); @endphp
                        <li class="dropdown-parent {{ $projectsOpen ? 'open active' : '' }}">
                            <div class="menu-dropdown-trigger">
                                <span class="micon bi bi-folder-fill"></span>
                                <span class="mtext">{{ __('backend.supervisor_layout.projects') }}</span>
                                <span class="menu-arrow"><i class="fa fa-chevron-down"></i></span>
                            </div>
                            <ul class="submenu">
                                <li><a href="{{ route('supervisor.projects.index') }}" class="{{ request()->routeIs('supervisor.projects.index') ? 'active' : '' }}">{{ __('backend.supervisor_layout.all_projects') }}</a></li>
                                <li><a href="{{ route('supervisor.projects.pending') }}" class="{{ request()->routeIs('supervisor.projects.pending') ? 'active' : '' }}">{{ __('backend.supervisor_layout.pending_reviews') }}</a></li>
                                <li><a href="{{ route('supervisor.projects.approved') }}" class="{{ request()->routeIs('supervisor.projects.approved') ? 'active' : '' }}">{{ __('backend.supervisor_layout.approved_projects') }}</a></li>
                                <li><a href="{{ route('supervisor.projects.revisions') }}" class="{{ request()->routeIs('supervisor.projects.revisions') ? 'active' : '' }}">{{ __('backend.supervisor_layout.revision_requests') }}</a></li>
                            </ul>
                        </li>
                    @endif

                    @if($canViewMeetings)
                        @php $meetingsOpen = request()->routeIs('supervisor.meetings.*'); @endphp
                        <li class="dropdown-parent {{ $meetingsOpen ? 'open active' : '' }}">
                            <div class="menu-dropdown-trigger">
                                <span class="micon bi bi-calendar-event"></span>
                                <span class="mtext">{{ __('backend.supervisor_layout.meetings') }}</span>
                                <span class="menu-arrow"><i class="fa fa-chevron-down"></i></span>
                            </div>
                            <ul class="submenu">
                                <li><a href="{{ route('supervisor.meetings.index') }}" class="{{ request()->routeIs('supervisor.meetings.index') ? 'active' : '' }}">{{ __('backend.supervisor_layout.all_meetings') }}</a></li>
                                <li><a href="{{ route('supervisor.meetings.upcoming') }}" class="{{ request()->routeIs('supervisor.meetings.upcoming') ? 'active' : '' }}">{{ __('backend.supervisor_layout.upcoming_meetings') }}</a></li>
                                <li><a href="{{ route('supervisor.meetings.completed') }}" class="{{ request()->routeIs('supervisor.meetings.completed') ? 'active' : '' }}">{{ __('backend.supervisor_layout.completed_meetings') }}</a></li>
                                @if($canManageMeetings)
                                    <li><a href="{{ route('supervisor.meetings.create') }}" class="{{ request()->routeIs('supervisor.meetings.create') ? 'active' : '' }}">{{ __('backend.supervisor_layout.create_meeting') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if($canViewRequests)
                        @php $requestsOpen = request()->routeIs('supervisor.requests.*'); @endphp
                        <li class="dropdown-parent {{ $requestsOpen ? 'open active' : '' }}">
                            <div class="menu-dropdown-trigger">
                                <span class="micon fa fa-send"></span>
                                <span class="mtext">{{ __('backend.supervisor_layout.requests') }}</span>
                                <span class="menu-arrow"><i class="fa fa-chevron-down"></i></span>
                            </div>
                            <ul class="submenu">
                                <li><a href="{{ route('supervisor.requests.index') }}" class="{{ request()->routeIs('supervisor.requests.index') ? 'active' : '' }}">{{ __('backend.supervisor_layout.all_requests') }}</a></li>
                                <li><a href="{{ route('supervisor.requests.pending') }}" class="{{ request()->routeIs('supervisor.requests.pending') ? 'active' : '' }}">{{ __('backend.supervisor_layout.pending_requests') }}</a></li>
                                <li><a href="{{ route('supervisor.requests.completed') }}" class="{{ request()->routeIs('supervisor.requests.completed') ? 'active' : '' }}">{{ __('backend.supervisor_layout.completed_requests') }}</a></li>
                            </ul>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('view_contact_messages'))
                        <li>
                            <a href="{{ route('supervisor.contact-messages.index') }}"
                               class="{{ request()->routeIs('supervisor.contact-messages.*') ? 'active' : '' }}">
                                <span class="micon bi bi-envelope-paper-fill"></span>
                                <span class="mtext">{{ __('backend.supervisor_layout.contact_messages') }}</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ route('supervisor.profile.index') }}"
                           class="{{ request()->routeIs('supervisor.profile.*') ? 'active' : '' }}">
                            <span class="micon bi bi-person-lines-fill"></span>
                            <span class="mtext">{{ __('backend.supervisor_layout.profile_settings') }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-bottom">
                <div class="sidebar-bottom-title">{{ __('backend.supervisor_layout.account') }}</div>

                <div class="sidebar-account-card">
                    <div class="sidebar-account-top">
                        <img
                            src="{{ !empty($adminUser?->profile_image) ? asset('storage/' . $adminUser->profile_image) : asset('vendors/images/photo1.jpg') }}"
                            alt="{{ __('backend.supervisor_layout.user_avatar') }}"
                            class="sidebar-account-avatar"
                        >
                        <div>
                            <div class="sidebar-account-name">{{ $adminUser ? $adminUser->name : __('backend.supervisor_layout.guest') }}</div>
                            <div class="sidebar-account-role">{{ __('backend.supervisor_layout.supervisor') }}</div>
                        </div>
                    </div>

                    <div class="sidebar-account-links">
                        <a href="{{ route('supervisor.profile.index') }}">
                            <span class="micon bi bi-person-circle"></span>
                            <span class="mtext">{{ __('backend.supervisor_layout.profile') }}</span>
                        </a>
                        <a href="javascript:void(0);" id="rightSidebarToggleBtn">
                            <span class="micon bi bi-gear"></span>
                            <span class="mtext">{{ __('backend.supervisor_layout.layout_settings') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list" id="sidebarToggleBtn" title="{{ __('backend.supervisor_layout.toggle_sidebar') }}"></div>
        <div class="search-toggle-icon bi bi-grid" title="{{ __('backend.supervisor_layout.supervisor_panel') }}"></div>
    </div>

    <div class="header-right">
        <div class="language-switch-group">
            <a href="{{ route('admin.language.switch', 'en') }}"
               class="language-switch-btn {{ $currentLocale === 'en' ? 'active' : '' }}"
               title="{{ __('backend.supervisor_layout.switch_to_english') }}">
                EN
            </a>
            <a href="{{ route('admin.language.switch', 'ar') }}"
               class="language-switch-btn {{ $currentLocale === 'ar' ? 'active' : '' }}"
               title="{{ __('backend.supervisor_layout.switch_to_arabic') }}">
                AR
            </a>
        </div>

        <button type="button" class="theme-switch-btn" id="themeSwitchBtn" title="{{ __('backend.supervisor_layout.change_theme') }}">
            <i class="bi bi-moon-stars-fill" id="themeSwitchIcon"></i>
        </button>

        <a href="javascript:void(0);" class="header-setting-btn" id="headerRightSidebarToggleBtn" title="{{ __('backend.supervisor_layout.layout_settings') }}">
            <i class="dw dw-settings2"></i>
        </a>

        <div class="dropdown" id="admin-notification-bell" data-count-url="{{ route('admin.notifications.count') }}">
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

            <div class="dropdown-menu dropdown-menu-end notification-panel">
                <div class="notification-panel-header">
                    <div>
                        <h6 class="notification-panel-title">{{ __('backend.supervisor_layout.notifications') }}</h6>
                        <small class="notification-panel-count">
                            <span id="adminUnreadText">{{ $unreadCount }}</span> {{ __('backend.supervisor_layout.unread') }}
                        </small>
                    </div>
                </div>

                <div class="notification-panel-body">
                    @forelse($latestNotifications as $notification)
                        @php
                          $key = $notification->data['key'] ?? null;

$title = $key
    ? __("backend.notifications.types.{$key}.title", $notification->data)
    : ($notification->data['title'] ?? __('backend.notifications.notification'));

$message = $key
    ? __("backend.notifications.types.{$key}.message", $notification->data)
    : ($notification->data['message'] ?? '');
                            $url = $notification->data['url'] ?? route('supervisor.notifications.index');
                            $icon = $notification->data['icon'] ?? 'fas fa-bell';
                            $isRead = !is_null($notification->read_at);
                        @endphp

                        <form method="POST" action="{{ route('supervisor.notifications.read', $notification->id) }}" class="m-0">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ $url }}">

                            <button type="submit" class="notification-item-btn {{ $isRead ? '' : 'unread' }}">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="notification-item-icon">
                                        <i class="{{ $icon }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="notification-item-title">{{ $title }}</div>
                                        <div class="notification-item-message">{{ $message }}</div>
                                        <div class="notification-item-time">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </button>
                        </form>
                    @empty
                        <div class="px-3 py-4 text-center small" style="color: var(--vg-text-muted);">
                            {{ __('backend.supervisor_layout.no_notifications_yet') }}
                        </div>
                    @endforelse
                </div>

                <div class="notification-panel-footer">
                    <a href="{{ route('supervisor.notifications.index') }}" class="notification-footer-btn">
                        {{ __('backend.supervisor_layout.history') }}
                    </a>

                    <form method="POST" action="{{ route('supervisor.notifications.markAllRead') }}" class="m-0">
                        @csrf
                        <button type="submit" class="notification-footer-btn w-100">
                            {{ __('backend.supervisor_layout.mark_all_read') }}
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
                    src="{{ !empty($adminUser?->avatar) ? asset('storage/' . $adminUser->avatar) : asset('vendors/images/photo1.jpg') }}"
                    alt="{{ __('backend.supervisor_layout.user_avatar') }}"
                    class="sidebar-account-avatar"
                >
                <div class="d-none d-md-block text-start">
                    <div style="font-size: 13px; font-weight: 700; color: var(--vg-header-color); line-height: 1.2;">
                        {{ $adminUser ? $adminUser->name : __('backend.supervisor_layout.guest') }}
                    </div>
                    <div style="font-size: 11px; color: var(--vg-text-muted); line-height: 1.2;">
                        {{ __('backend.supervisor_layout.supervisor') }}
                    </div>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end border-0" style="min-width: 230px;">
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('supervisor.profile.index') }}">
                        <i class="bi bi-person"></i>
                        <span>{{ __('backend.supervisor_layout.profile') }}</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);" id="dropdownSettingsBtn">
                        <i class="bi bi-gear"></i>
                        <span>{{ __('backend.supervisor_layout.layout_settings') }}</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider my-2"></li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 text-danger"
                       href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{ __('backend.supervisor_layout.log_out') }}</span>
                    </a>
                </li>
            </ul>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>

<div class="right-sidebar" id="rightSidebarPanel">
    <div class="sidebar-title">
        <h3 class="weight-600 font-16">
            {{ __('backend.supervisor_layout.layout_settings') }}
            <span class="btn-block font-weight-400 font-12">{{ __('backend.supervisor_layout.supervisor_interface_preferences') }}</span>
        </h3>
        <div class="close-sidebar" id="rightSidebarCloseBtn">
            <i class="icon-copy ion-close-round"></i>
        </div>
    </div>

    <div class="right-sidebar-body customscroll">
        <div class="right-sidebar-body-content p-3">
            <div class="theme-setting-card">
                <div class="theme-setting-title">{{ __('backend.supervisor_layout.theme_mode') }}</div>
                <div class="theme-btn-group">
                    <button type="button" class="theme-choice-btn" id="themeLightBtn">{{ __('backend.supervisor_layout.light') }}</button>
                    <button type="button" class="theme-choice-btn" id="themeDarkBtn">{{ __('backend.supervisor_layout.dark') }}</button>
                </div>
            </div>

            <div class="theme-setting-card">
                <div class="theme-setting-title">{{ __('backend.supervisor_layout.announcements') }}</div>

                @if($layoutAnnouncements->count())
                    <div class="d-flex flex-column gap-3">
                        @foreach($layoutAnnouncements as $announcement)
                            <div class="announcement-card">
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                    <div class="announcement-card-title">
                                        {{ $announcement->title }}
                                    </div>

                                    @if($announcement->is_pinned)
                                        <span class="announcement-badge pinned">
                                            <i class="fas fa-thumbtack"></i>
                                            {{ __('backend.supervisor_layout.pinned') }}
                                        </span>
                                    @endif
                                </div>

                                <p class="announcement-card-text">
                                    {{ \Illuminate\Support\Str::limit($announcement->body, 120) }}
                                </p>

                                @if($announcement->expires_at)
                                    <div class="announcement-meta">
                                        {{ __('backend.supervisor_layout.until') }} {{ $announcement->expires_at->format('M d, Y • h:i A') }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="announcement-card">
                        <div class="announcement-card-title">{{ __('backend.supervisor_layout.no_announcements') }}</div>
                        <p class="announcement-card-text mb-0">
                            {{ __('backend.supervisor_layout.no_active_announcements') }}
                        </p>
                    </div>
                @endif
            </div>

            <div class="reset-options pt-2 text-center">
                <button class="btn btn-danger" id="reset-settings" type="button">{{ __('backend.supervisor_layout.reset_theme') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="main-container">
    <div class="app-content-wrap">
        @yield('content')
    </div>

    <div class="app-footer">
        <div><strong>{{ setting('platform_name', 'VertexGrad') }}</strong> {{ __('backend.supervisor_layout.supervisor_panel') }}</div>
        <div>{{ setting('platform_tagline', __('backend.supervisor_layout.footer_tagline')) }}</div>
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
    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif

    @if(session('error'))
        showToast(@json(session('error')), 'danger');
    @endif

    const body = document.body;
    const sidebar = document.getElementById('supervisorSidebar');
    const mobileOverlay = document.getElementById('mobileMenuOverlay');

    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

    const rightSidebar = document.getElementById('rightSidebarPanel');
    const rightSidebarToggleBtn = document.getElementById('rightSidebarToggleBtn');
    const headerRightSidebarToggleBtn = document.getElementById('headerRightSidebarToggleBtn');
    const dropdownSettingsBtn = document.getElementById('dropdownSettingsBtn');
    const rightSidebarCloseBtn = document.getElementById('rightSidebarCloseBtn');

    const themeSwitchBtn = document.getElementById('themeSwitchBtn');
    const themeSwitchIcon = document.getElementById('themeSwitchIcon');
    const themeLightBtn = document.getElementById('themeLightBtn');
    const themeDarkBtn = document.getElementById('themeDarkBtn');
    const resetBtn = document.getElementById('reset-settings');

    const bell = document.getElementById('admin-notification-bell');
    const badge = document.getElementById('adminUnreadBadge');
    const unreadText = document.getElementById('adminUnreadText');

    function syncThemeButtons() {
        const isDark = body.classList.contains('dark-theme');

        if (themeSwitchIcon) {
            themeSwitchIcon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }

        if (themeLightBtn) themeLightBtn.classList.toggle('active', !isDark);
        if (themeDarkBtn) themeDarkBtn.classList.toggle('active', isDark);
    }

    function applyTheme(theme) {
        if (theme === 'dark') {
            body.classList.add('dark-theme');
            localStorage.setItem('supervisor-theme', 'dark');
        } else {
            body.classList.remove('dark-theme');
            localStorage.setItem('supervisor-theme', 'light');
        }
        syncThemeButtons();
    }

    function openLeftSidebar() {
        if (window.innerWidth <= 991.98) {
            sidebar?.classList.add('mobile-open');
            mobileOverlay?.classList.add('active');
        } else {
            body.classList.remove('sidebar-closed');
        }
    }

    function closeLeftSidebar() {
        if (window.innerWidth <= 991.98) {
            sidebar?.classList.remove('mobile-open');
            mobileOverlay?.classList.remove('active');
        } else {
            body.classList.add('sidebar-closed');
        }
    }

    function toggleLeftSidebar() {
        if (window.innerWidth <= 991.98) {
            if (sidebar?.classList.contains('mobile-open')) {
                closeLeftSidebar();
            } else {
                openLeftSidebar();
            }
        } else {
            body.classList.toggle('sidebar-closed');
        }
    }

    function toggleRightSidebar() {
        rightSidebar?.classList.toggle('open');
    }

    sidebarToggleBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        toggleLeftSidebar();
    });

    sidebarCloseBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        closeLeftSidebar();
    });

    mobileOverlay?.addEventListener('click', function () {
        closeLeftSidebar();
        rightSidebar?.classList.remove('open');
    });

    [rightSidebarToggleBtn, headerRightSidebarToggleBtn, dropdownSettingsBtn].forEach(function (btn) {
        btn?.addEventListener('click', function (e) {
            e.preventDefault();
            toggleRightSidebar();
        });
    });

    rightSidebarCloseBtn?.addEventListener('click', function () {
        rightSidebar?.classList.remove('open');
    });

    themeSwitchBtn?.addEventListener('click', function () {
        applyTheme(body.classList.contains('dark-theme') ? 'light' : 'dark');
    });

    themeLightBtn?.addEventListener('click', function () {
        applyTheme('light');
    });

    themeDarkBtn?.addEventListener('click', function () {
        applyTheme('dark');
    });

    resetBtn?.addEventListener('click', function () {
        applyTheme('light');
    });

    document.querySelectorAll('.dropdown-parent > .menu-dropdown-trigger').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const parent = this.closest('.dropdown-parent');
            if (!parent) return;

            const isOpen = parent.classList.contains('open');

            document.querySelectorAll('.dropdown-parent').forEach(function (item) {
                if (item !== parent) {
                    item.classList.remove('open');
                }
            });

            if (isOpen) {
                parent.classList.remove('open');
            } else {
                parent.classList.add('open');
            }
        });
    });

    if (bell) {
        const countUrl = bell.dataset.countUrl;

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
    }

    const savedTheme = localStorage.getItem('supervisor-theme');
    applyTheme(savedTheme === 'dark' ? 'dark' : 'light');

    if (window.innerWidth <= 991.98) {
        closeLeftSidebar();
    } else {
        openLeftSidebar();
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth <= 991.98) {
            closeLeftSidebar();
        } else {
            sidebar?.classList.remove('mobile-open');
            mobileOverlay?.classList.remove('active');
        }
    });
});
</script>

@stack('scripts')
</body>
</html>