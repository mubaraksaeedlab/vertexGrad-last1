@extends('layouts.app')

@section('title', __('backend.announcements_edit.page_title'))

@section('content')
<div class="container-fluid py-4 px-lg-5">

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark">{{ __('backend.announcements_edit.heading') }}</h3>
            <p class="text-muted mb-0 small">
                {{ __('backend.announcements_edit.subtitle') }}
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-light border rounded-3 px-3 py-2 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> {{ __('backend.announcements_edit.back') }}
            </a>
        </div>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 px-4 py-3 mb-4">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                <div>
                    <strong class="d-block mb-1">{{ __('backend.announcements_edit.there_are_some_errors') }}</strong>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

        {{-- Top Gradient --}}
        <div class="announcement-header-gradient"></div>

        <div class="card-body p-4 p-lg-5 bg-white">

            <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.announcements._form', ['announcement' => $announcement])

            </form>

        </div>
    </div>

</div>

<style>
    .announcement-header-gradient {
        height: 6px;
        background: linear-gradient(90deg, #1b00ff, #4f46e5, #06b6d4);
    }

    .card.shadow-lg {
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.08) !important;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fef2f2);
        color: #991b1b;
    }

    .btn-light {
        background: #fff;
    }

    .btn-light:hover {
        background: #f8fafc;
    }

    .container-fluid {
        max-width: 1200px;
    }
</style>
@endsection