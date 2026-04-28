<div class="right-sidebar">
    <div class="sidebar-title px-4 py-3" style="border-bottom: 1px solid var(--vg-border);">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <h3 class="mb-1 fw-bold" style="font-size: 16px; color: var(--vg-primary);">
                    {{ __('backend.layout_right_sidebar.layout_settings') }}
                </h3>
                <div class="small" style="color: var(--vg-text-muted);">
                    {{ __('backend.layout_right_sidebar.user_interface_preferences') }}
                </div>
            </div>

            <div class="close-sidebar" data-toggle="right-sidebar-close" style="cursor: pointer;">
                <i class="icon-copy ion-close-round"></i>
            </div>
        </div>
    </div>

    <div class="right-sidebar-body customscroll">
        <div class="right-sidebar-body-content p-4">
            <h4 class="fw-bold mb-3" style="font-size: 15px; color: var(--vg-text);">{{ __('backend.layout_right_sidebar.header_background') }}</h4>
            <div class="sidebar-btn-group d-flex gap-2 pb-4 mb-2">
                <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">{{ __('backend.layout_right_sidebar.white') }}</a>
                <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">{{ __('backend.layout_right_sidebar.dark') }}</a>
            </div>

            <h4 class="fw-bold mb-3" style="font-size: 15px; color: var(--vg-text);">{{ __('backend.layout_right_sidebar.sidebar_background') }}</h4>
            <div class="sidebar-btn-group d-flex gap-2 pb-4 mb-2">
                <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">{{ __('backend.layout_right_sidebar.white') }}</a>
                <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">{{ __('backend.layout_right_sidebar.dark') }}</a>
            </div>

            <h4 class="fw-bold mb-3" style="font-size: 15px; color: var(--vg-text);">{{ __('backend.layout_right_sidebar.menu_dropdown_icon') }}</h4>
            <div class="sidebar-radio-group pb-3 mb-2">
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="form-check-input" value="icon-style-1" checked />
                    <label class="form-check-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="form-check-input" value="icon-style-2" />
                    <label class="form-check-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="form-check-input" value="icon-style-3" />
                    <label class="form-check-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
                </div>
            </div>

            <h4 class="fw-bold mb-3" style="font-size: 15px; color: var(--vg-text);">{{ __('backend.layout_right_sidebar.menu_list_icon') }}</h4>
            <div class="sidebar-radio-group pb-4 mb-2">
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="form-check-input" value="icon-list-style-1" checked />
                    <label class="form-check-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="form-check-input" value="icon-list-style-2" />
                    <label class="form-check-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="form-check-input" value="icon-list-style-3" />
                    <label class="form-check-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="form-check-input" value="icon-list-style-4" checked />
                    <label class="form-check-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="form-check-input" value="icon-list-style-5" />
                    <label class="form-check-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="form-check-input" value="icon-list-style-6" />
                    <label class="form-check-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
                </div>
            </div>

            <div class="pt-3 text-center">
                <button class="btn btn-danger px-4" id="reset-settings">{{ __('backend.layout_right_sidebar.reset_settings') }}</button>
            </div>
        </div>
    </div>
</div>