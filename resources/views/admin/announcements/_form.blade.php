<style>
    .announcement-settings-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.04);
    }

    .announcement-option {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        background: #fff;
        cursor: pointer;
        transition: all .2s ease;
        min-height: 84px;
        margin: 0;
    }

    .announcement-option:hover {
        border-color: #4f46e5;
        background: #f8fafc;
    }

    .announcement-option-input {
        width: 18px;
        height: 18px;
        min-width: 18px;
        margin: 0;
        accent-color: #4f46e5;
        cursor: pointer;
        flex-shrink: 0;
        position: relative;
        top: 0;
    }

    .announcement-option-content {
        flex: 1;
        min-width: 0;
        line-height: 1.4;
    }

    .announcement-option-title {
        display: block;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }

    .announcement-option-text {
        display: block;
        font-size: 13px;
        color: #6b7280;
    }

    .announcement-header-gradient {
        height: 6px;
        background: linear-gradient(90deg, #1b00ff, #4f46e5, #06b6d4);
    }
</style>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 px-4 py-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="mb-1 fw-bold text-dark">{{ __('backend.announcements_form.announcement_details') }}</h4>
                <p class="mb-0 text-muted small">{{ __('backend.announcements_form.announcement_details_subtitle') }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill text-bg-light border px-3 py-2">{{ __('backend.announcements_form.admin_panel') }}</span>
            </div>
        </div>
    </div>

    <div class="card-body p-4 p-lg-5 bg-light bg-opacity-25">
        <div class="row g-4">

            {{-- Title --}}
            <div class="col-12">
                <div class="bg-white border rounded-4 p-4 shadow-sm h-100">
                    <label class="form-label fw-semibold text-dark mb-2">{{ __('backend.announcements_form.title') }}</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control form-control-lg rounded-3 border-2"
                        placeholder="{{ __('backend.announcements_form.enter_announcement_title') }}"
                        value="{{ old('title', $announcement->title ?? '') }}"
                        required
                    >
                </div>
            </div>

            {{-- Body --}}
            <div class="col-12">
                <div class="bg-white border rounded-4 p-4 shadow-sm h-100">
                    <label class="form-label fw-semibold text-dark mb-2">{{ __('backend.announcements_form.body') }}</label>
                    <textarea
                        name="body"
                        rows="6"
                        class="form-control rounded-3 border-2"
                        placeholder="{{ __('backend.announcements_form.write_full_message') }}"
                        required>{{ old('body', $announcement->body ?? '') }}</textarea>
                </div>
            </div>

            {{-- Audience --}}
            <div class="col-12">
                <div class="bg-white border rounded-4 p-4 shadow-sm h-100">
                    <label class="form-label fw-semibold text-dark mb-2">{{ __('backend.announcements_form.audience') }}</label>
                    <select name="audience" class="form-select form-select-lg rounded-3 border-2" required>
                        @php
                            $selectedAudience = old('audience', $announcement->audience ?? 'all');
                        @endphp
                        <option value="all" {{ $selectedAudience == 'all' ? 'selected' : '' }}>{{ __('backend.announcements_form.audiences.all') }}</option>
                        <option value="students" {{ $selectedAudience == 'students' ? 'selected' : '' }}>{{ __('backend.announcements_form.audiences.students') }}</option>
                        <option value="investors" {{ $selectedAudience == 'investors' ? 'selected' : '' }}>{{ __('backend.announcements_form.audiences.investors') }}</option>
                        <option value="supervisors" {{ $selectedAudience == 'supervisors' ? 'selected' : '' }}>{{ __('backend.announcements_form.audiences.supervisors') }}</option>
                    </select>
                </div>
            </div>

            {{-- Publish / Expire --}}
            <div class="col-md-6">
                <div class="bg-white border rounded-4 p-4 shadow-sm h-100">
                    <label class="form-label fw-semibold text-dark mb-2">{{ __('backend.announcements_form.publish_at') }}</label>
                    <input
                        type="datetime-local"
                        name="publish_at"
                        class="form-control form-control-lg rounded-3 border-2"
                        value="{{ old('publish_at', isset($announcement) && $announcement->publish_at ? $announcement->publish_at->format('Y-m-d\TH:i') : '') }}"
                    >
                </div>
            </div>

            <div class="col-md-6">
                <div class="bg-white border rounded-4 p-4 shadow-sm h-100">
                    <label class="form-label fw-semibold text-dark mb-2">{{ __('backend.announcements_form.expires_at') }}</label>
                    <input
                        type="datetime-local"
                        name="expires_at"
                        class="form-control form-control-lg rounded-3 border-2"
                        value="{{ old('expires_at', isset($announcement) && $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}"
                    >
                </div>
            </div>

            {{-- Settings --}}
            <div class="col-12">
                <div class="announcement-settings-card">
                    <div class="d-flex flex-column flex-lg-row gap-3">

                        <label class="announcement-option flex-fill">
                            <input
                                type="checkbox"
                                name="is_pinned"
                                value="1"
                                class="announcement-option-input"
                                {{ old('is_pinned', $announcement->is_pinned ?? false) ? 'checked' : '' }}
                            >
                            <span class="announcement-option-content">
                                <span class="announcement-option-title">{{ __('backend.announcements_form.pinned_announcement') }}</span>
                                <span class="announcement-option-text">{{ __('backend.announcements_form.pinned_announcement_text') }}</span>
                            </span>
                        </label>

                        <label class="announcement-option flex-fill">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                class="announcement-option-input"
                                {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}
                            >
                            <span class="announcement-option-content">
                                <span class="announcement-option-title">{{ __('backend.announcements_form.active_status') }}</span>
                                <span class="announcement-option-text">{{ __('backend.announcements_form.active_status_text') }}</span>
                            </span>
                        </label>

                    </div>
                </div>
            </div>

        </div>

        {{-- Actions --}}
        <div class="d-flex flex-column flex-sm-row justify-content-end gap-3 mt-4 pt-3 border-top">
            <a href="{{ route('admin.announcements.index') }}"
               class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">
                {{ __('backend.announcements_form.cancel') }}
            </a>

            <button type="submit"
                    class="btn btn-primary rounded-3 px-5 py-2 fw-semibold shadow-sm">
                <i class="bi bi-check2-circle me-2"></i> {{ __('backend.announcements_form.save_announcement') }}
            </button>
        </div>
    </div>
</div>