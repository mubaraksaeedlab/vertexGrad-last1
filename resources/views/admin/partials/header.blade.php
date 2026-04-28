@php
    $adminUser = auth('admin')->user();
@endphp

<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list" data-toggle="left-sidebar-toggle" title="{{ __('backend.layout_header.toggle_sidebar') }}"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search" title="{{ __('backend.layout_header.search') }}"></div>
    </div>

    <div class="header-right">
        <a href="javascript:;"
           class="dashboard-setting"
           data-toggle="right-sidebar"
           title="{{ __('backend.layout_header.layout_settings') }}">
            <i class="dw dw-settings2"></i>
        </a>

        @include('admin.partials.notifications')

        <div class="dropdown user-info-dropdown">
            <a class="dropdown-toggle d-flex align-items-center gap-2 text-decoration-none"
               href="#"
               role="button"
               data-bs-toggle="dropdown"
               aria-expanded="false"
               style="padding: 6px 10px 6px 8px; border-radius: 14px; border: 1px solid var(--vg-border); background: #fff;">
                <img src="{{ $adminUser && $adminUser->avatar ? asset('storage/' . $adminUser->avatar) : asset('vendors/images/photo1.jpg') }}"
                     class="rounded-circle"
                     width="34"
                     height="34"
                     alt="{{ __('backend.layout_header.user_avatar') }}"
                     style="object-fit: cover; border: 2px solid #f1f5f9; flex-shrink: 0;">

                <div class="d-none d-md-block text-start" style="min-width: 0;">
                    <div class="fw-bold"
                         style="font-size: 13px; color: var(--vg-text); line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 135px;">
                        {{ $adminUser ? $adminUser->name : __('backend.layout_header.guest') }}
                    </div>
                    <div style="font-size: 11px; color: var(--vg-text-muted); line-height: 1.2;">
                        {{ __('backend.layout_header.administrator') }}
                    </div>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end border-0" style="min-width: 230px;">
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.profile') }}">
                        <i class="bi bi-person"></i>
                        <span>{{ __('backend.layout_header.profile') }}</span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                        <i class="bi bi-gear"></i>
                        <span>{{ __('backend.layout_header.settings') }}</span>
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider my-2">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 text-danger"
                       href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{ __('backend.layout_header.log_out') }}</span>
                    </a>
                </li>
            </ul>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>