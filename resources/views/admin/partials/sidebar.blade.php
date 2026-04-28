@php
    $adminUser = auth('admin')->user();
@endphp

<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('manager.dashboard') }}" class="sidebar-logo-link">
            <img
                src="{{ asset('vendors/images/VertexGrad_logod.png') }}"
                alt="{{ __('backend.layout_left_sidebar.vertexgrad_logo') }}"
                class="sidebar-logo-img"
            />
        </a>

        <div class="close-sidebar" data-toggle="left-sidebar-close" title="{{ __('backend.layout_left_sidebar.close_sidebar') }}">
            <i class="ion-close-round"></i>
        </div>
    </div>

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <div class="sidebar-section-label">{{ __('backend.layout_left_sidebar.main_navigation') }}</div>

            <ul id="accordion-menu">
                <li>
                    <a href="{{ route('manager.dashboard') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-house-door-fill"></span>
                        <span class="mtext">{{ __('backend.layout_left_sidebar.dashboard') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('manager.pending.users') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-people-fill"></span>
                        <span class="mtext">{{ __('backend.layout_left_sidebar.user_management') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('manager.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-person-workspace"></span>
                        <span class="mtext">{{ __('backend.layout_left_sidebar.managers') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.students.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-mortarboard-fill"></span>
                        <span class="mtext">{{ __('backend.layout_left_sidebar.students') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.investors.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-wallet2"></span>
                        <span class="mtext">{{ __('backend.layout_left_sidebar.investors') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.projects.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-briefcase-fill"></span>
                        <span class="mtext">{{ __('backend.layout_left_sidebar.projects') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('manager.calendar.index') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-calendar-check-fill"></span>
                        <span class="mtext">{{ __('backend.layout_left_sidebar.calendar') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.reports.platform') }}" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-bar-chart-line-fill"></span>
                        <span class="mtext">{{ __('backend.layout_left_sidebar.platform_reports') }}</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-bottom">
                <div class="sidebar-bottom-title">{{ __('backend.layout_left_sidebar.account') }}</div>

                <div class="sidebar-account-card">
                    <div class="sidebar-account-top">
                        <img
                            src="{{ $adminUser && $adminUser->avatar ? asset('storage/' . $adminUser->avatar) : asset('vendors/images/photo1.jpg') }}"
                            alt="{{ __('backend.layout_left_sidebar.user_avatar') }}"
                            class="sidebar-account-avatar"
                        >

                        <div class="sidebar-account-info">
                            <div class="sidebar-account-name">
                                {{ $adminUser ? $adminUser->name : __('backend.layout_left_sidebar.guest') }}
                            </div>
                            <div class="sidebar-account-role">
                                {{ __('backend.layout_left_sidebar.administrator') }}
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-account-links">
                        <a href="{{ route('admin.profile') }}">
                            <span class="micon bi bi-person-circle"></span>
                            <span class="mtext">{{ __('backend.layout_left_sidebar.profile') }}</span>
                        </a>

                        <a href="#">
                            <span class="micon bi bi-gear"></span>
                            <span class="mtext">{{ __('backend.layout_left_sidebar.settings') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>