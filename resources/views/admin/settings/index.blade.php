@extends('layouts.app')

@section('title', __('backend.settings.page_title'))

@section('content')
@php
    $filteredSettings = collect($settings)->reject(function ($groupSettings, $group) {
        return $group === 'appearance';
    });
@endphp

<div class="container-fluid py-4 px-lg-4 px-xl-5 settings-page">

    {{-- Hero --}}
    <div class="settings-hero-card mb-4">
        <div class="settings-hero-content">
            <div>
                <div class="settings-eyebrow">{{ __('backend.settings.system_control_center') }}</div>
                <h2 class="settings-page-title mb-2">{{ __('backend.settings.heading') }}</h2>
                <p class="settings-page-subtitle mb-0">
                    {{ __('backend.settings.subtitle') }}
                </p>
            </div>

            <div class="settings-hero-badge">
                <i class="bi bi-sliders2-vertical"></i>
                <span>{{ __('backend.settings.dynamic_settings_engine') }}</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
            <div class="fw-bold mb-2">{{ __('backend.settings.please_review_issues') }}</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li class="mb-1">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            {{-- Sidebar Tabs --}}
            <div class="col-xl-3">
                <div class="settings-sidebar-card">
                    <div class="settings-sidebar-title">
                        <h6 class="mb-1">{{ __('backend.settings.configuration_sections') }}</h6>
                        <p class="mb-0">{{ __('backend.settings.configuration_sections_text') }}</p>
                    </div>

                    <div class="nav flex-column nav-pills settings-tabs" id="settings-tab" role="tablist">
                        @foreach($filteredSettings as $group => $groupSettings)
                            <button
                                class="nav-link {{ $loop->first ? 'active' : '' }}"
                                id="tab-{{ $group }}"
                                data-bs-toggle="pill"
                                data-bs-target="#pane-{{ $group }}"
                                type="button"
                                role="tab"
                            >
                                <span>{{ $groupLabels[$group] ?? ucfirst($group) }}</span>
                                <small>{{ count($groupSettings) }} {{ __('backend.settings.items') }}</small>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-xl-9">
                <div class="tab-content">
                    @foreach($filteredSettings as $group => $groupSettings)
                        <div
                            class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                            id="pane-{{ $group }}"
                            role="tabpanel"
                        >
                            <div class="settings-group-card">
                                <div class="settings-group-header">
                                    <div>
                                        <h4>{{ $groupLabels[$group] ?? ucfirst($group) }}</h4>
                                        <p class="mb-0">
                                            {{ __('backend.settings.manage_group_related_settings', ['group' => strtolower($groupLabels[$group] ?? ucfirst($group))]) }}
                                        </p>
                                    </div>

                                    <div class="settings-group-count">
                                        {{ count($groupSettings) }} {{ __('backend.settings.fields') }}
                                    </div>
                                </div>

                                <div class="row g-4">
                                    @foreach($groupSettings as $setting)
                                        <div class="col-md-6">
                                            <div class="setting-item-card">
                                                <label class="setting-label" for="{{ $setting->key }}">
                                                    {{ $setting->label }}
                                                </label>

                                                @if($setting->type === 'text' || $setting->type === 'number')
                                                    <input
                                                        type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                                        name="{{ $setting->key }}"
                                                        id="{{ $setting->key }}"
                                                        value="{{ old($setting->key, $setting->value) }}"
                                                        class="form-control setting-control"
                                                    >

                                                @elseif($setting->type === 'textarea')
                                                    <textarea
                                                        name="{{ $setting->key }}"
                                                        id="{{ $setting->key }}"
                                                        rows="4"
                                                        class="form-control setting-control"
                                                    >{{ old($setting->key, $setting->value) }}</textarea>

                                                @elseif($setting->type === 'boolean')
                                                    <div class="form-check form-switch setting-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="{{ $setting->key }}"
                                                            id="{{ $setting->key }}"
                                                            value="1"
                                                            {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="{{ $setting->key }}">
                                                            {{ __('backend.settings.enable_this_option') }}
                                                        </label>
                                                    </div>

                                                @elseif($setting->type === 'select')
                                                    <select
                                                        name="{{ $setting->key }}"
                                                        id="{{ $setting->key }}"
                                                        class="form-select setting-control"
                                                    >
                                                        @foreach(($setting->options ?? []) as $option)
                                                            <option value="{{ $option }}" {{ old($setting->key, $setting->value) == $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                @elseif($setting->type === 'image')
                                                    <div class="setting-image-upload-wrap">
                                                        <div class="setting-file-input-wrap">
                                                            <input
                                                                type="file"
                                                                name="{{ $setting->key }}"
                                                                id="{{ $setting->key }}"
                                                                class="form-control setting-control image-input"
                                                                accept="image/*"
                                                                data-preview-target="preview-{{ $setting->key }}"
                                                                data-name-target="filename-{{ $setting->key }}"
                                                                data-empty-target="empty-{{ $setting->key }}"
                                                                data-box-target="box-{{ $setting->key }}"
                                                            >
                                                        </div>

                                                        <div class="setting-image-preview mt-3">
                                                            <div class="setting-image-preview-label">
                                                                {{ __('backend.settings.preview') }}
                                                            </div>

                                                            <div
                                                                class="setting-image-box {{ $setting->value ? '' : 'd-none' }}"
                                                                id="box-{{ $setting->key }}"
                                                            >
                                                                <img
                                                                    id="preview-{{ $setting->key }}"
                                                                    src="{{ $setting->value ? asset('storage/' . $setting->value) : '' }}"
                                                                    alt="{{ $setting->label }}"
                                                                >
                                                            </div>

                                                            <div
                                                                class="setting-image-empty mt-0 {{ $setting->value ? 'd-none' : '' }}"
                                                                id="empty-{{ $setting->key }}"
                                                            >
                                                                <i class="bi bi-image"></i>
                                                                <span>{{ __('backend.settings.no_image_selected') }}</span>
                                                            </div>

                                                            <div
                                                                class="setting-image-file-name {{ $setting->value ? '' : 'is-empty' }}"
                                                                id="filename-{{ $setting->key }}"
                                                            >
                                                                {{ $setting->value ? basename($setting->value) : __('backend.settings.no_file_chosen') }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                @else
                                                    <input
                                                        type="text"
                                                        name="{{ $setting->key }}"
                                                        id="{{ $setting->key }}"
                                                        value="{{ old($setting->key, $setting->value) }}"
                                                        class="form-control setting-control"
                                                    >
                                                @endif

                                                @if($setting->description)
                                                    <div class="setting-help">
                                                        {{ $setting->description }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Actions --}}
                <div class="settings-actions-card mt-4">
                    <div class="settings-actions-left">
                        <div class="settings-actions-note">
                            {{ __('backend.settings.actions_note') }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <a href="{{ url()->current() }}" class="btn settings-btn-light">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            {{ __('backend.settings.reset_view') }}
                        </a>

                        <button type="submit" class="btn settings-btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>
                            {{ __('backend.settings.save_settings') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.settings-page {
    background: #f8fafc;
    min-height: calc(100vh - 90px);
}

.settings-hero-card {
    background: linear-gradient(135deg, #0f172a 0%, #16213e 45%, #1d4ed8 100%);
    border-radius: 26px;
    padding: 30px;
    color: #fff;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.18);
    position: relative;
    overflow: hidden;
}

.settings-hero-card::before {
    content: "";
    position: absolute;
    right: -50px;
    top: -40px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
}

.settings-hero-card::after {
    content: "";
    position: absolute;
    left: -50px;
    bottom: -70px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}

.settings-hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    flex-wrap: wrap;
}

.settings-eyebrow {
    color: rgba(255,255,255,0.72);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.3px;
    margin-bottom: 10px;
}

.settings-page-title {
    font-size: 30px;
    font-weight: 800;
}

.settings-page-subtitle {
    max-width: 840px;
    color: rgba(255,255,255,0.84);
    line-height: 1.8;
    font-size: 14px;
}

.settings-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 16px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    font-weight: 700;
    backdrop-filter: blur(10px);
}

.settings-alert-success {
    border: 0;
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(34,197,94,.10), rgba(34,197,94,.05));
    color: #166534;
    box-shadow: 0 8px 24px rgba(34,197,94,.08);
}

.settings-sidebar-card,
.settings-group-card,
.settings-actions-card {
    background: #fff;
    border: 1px solid #e9eef5;
    border-radius: 22px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
}

.settings-sidebar-card {
    padding: 18px;
    position: sticky;
    top: 20px;
}

.settings-sidebar-title {
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #eef2f7;
}

.settings-sidebar-title h6 {
    font-weight: 800;
    color: #0f172a;
}

.settings-sidebar-title p {
    font-size: 12px;
    color: #64748b;
}

.settings-tabs .nav-link {
    border-radius: 16px;
    color: #334155;
    font-weight: 700;
    padding: 13px 14px;
    margin-bottom: 8px;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    border: 1px solid transparent;
}

.settings-tabs .nav-link small {
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
}

.settings-tabs .nav-link.active {
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    color: #fff;
    box-shadow: 0 10px 18px rgba(37, 99, 235, 0.18);
}

.settings-tabs .nav-link.active small {
    color: rgba(255,255,255,0.82);
}

.settings-group-card {
    padding: 24px;
}

.settings-group-header {
    margin-bottom: 22px;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: flex-start;
    flex-wrap: wrap;
}

.settings-group-header h4 {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 6px;
}

.settings-group-header p {
    color: #64748b;
    font-size: 13px;
}

.settings-group-count {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #dbeafe;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.setting-item-card {
    background: #fbfdff;
    border: 1px solid #e7eef7;
    border-radius: 18px;
    padding: 16px;
    height: 100%;
    transition: all 0.2s ease;
}

.setting-item-card:hover {
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
    transform: translateY(-2px);
}

.setting-label {
    display: block;
    color: #0f172a;
    font-size: 14px;
    font-weight: 800;
    margin-bottom: 10px;
}

.setting-control {
    min-height: 48px;
    border-radius: 14px;
    border: 1px solid #dbe4ee;
    box-shadow: none !important;
}

.setting-control:focus {
    border-color: #3b82f6;
}

.setting-help {
    color: #64748b;
    font-size: 12px;
    margin-top: 9px;
    line-height: 1.6;
}

.setting-switch {
    padding-top: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.setting-switch .form-check-input {
    width: 48px;
    height: 24px;
    margin-top: 0;
}

.setting-image-upload-wrap {
    width: 100%;
}

.setting-file-input-wrap {
    position: relative;
}

.setting-image-preview-label {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
}

.setting-image-box {
    border: 1px dashed #cbd5e1;
    border-radius: 16px;
    padding: 12px;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 110px;
    min-height: 80px;
    width: 100%;
    max-width: 100%;
    margin-bottom: 10px;
}

.setting-image-box img {
    max-height: 120px;
    max-width: 100%;
    object-fit: contain;
    display: block;
}

.setting-image-file-name {
    margin-top: 4px;
    font-size: 12px;
    color: #334155;
    font-weight: 700;
    word-break: break-word;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 12px;
}

.setting-image-file-name.is-empty {
    color: #94a3b8;
    font-weight: 600;
}

.setting-image-empty {
    min-height: 80px;
    border: 1px dashed #dbe4ee;
    border-radius: 16px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 700;
    padding: 16px;
}

.settings-actions-card {
    padding: 18px 22px;
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
}

.settings-actions-note {
    color: #64748b;
    font-size: 13px;
    max-width: 520px;
    line-height: 1.7;
}

.settings-btn-primary {
    min-height: 48px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    color: #fff;
    font-weight: 700;
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.20);
}

.settings-btn-primary:hover {
    color: #fff;
    opacity: 0.96;
}

.settings-btn-light {
    min-height: 48px;
    border-radius: 14px;
    border: 1px solid #dbe4ee;
    background: #fff;
    color: #334155;
    font-weight: 700;
}

.settings-btn-light:hover {
    background: #f8fafc;
    color: #0f172a;
}

@media (max-width: 1199.98px) {
    .settings-sidebar-card {
        position: static;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInputs = document.querySelectorAll('.image-input');

    imageInputs.forEach(function (input) {
        input.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0];

            const previewId = input.dataset.previewTarget;
            const nameId = input.dataset.nameTarget;
            const emptyId = input.dataset.emptyTarget;
            const boxId = input.dataset.boxTarget;

            const previewImage = document.getElementById(previewId);
            const fileNameBox = document.getElementById(nameId);
            const emptyBox = document.getElementById(emptyId);
            const imageBox = document.getElementById(boxId);

            if (!file) {
                if (fileNameBox && !previewImage?.getAttribute('src')) {
                    fileNameBox.textContent = '{{ __('backend.settings.no_file_chosen') }}';
                    fileNameBox.classList.add('is-empty');
                }
                return;
            }

            if (fileNameBox) {
                fileNameBox.textContent = file.name;
                fileNameBox.classList.remove('is-empty');
            }

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (previewImage) {
                        previewImage.src = e.target.result;
                    }

                    if (imageBox) {
                        imageBox.classList.remove('d-none');
                    }

                    if (emptyBox) {
                        emptyBox.classList.add('d-none');
                    }
                };

                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endsection