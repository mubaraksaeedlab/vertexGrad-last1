@extends('layouts.app')

@section('title', __('backend.permissions_index.title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

        <div class="card-box mb-30" style="border-radius: 18px; overflow: hidden;">
            <div class="pd-20 d-flex justify-content-between align-items-center flex-wrap" style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: white;">
                <div>
                    <h4 class="mb-1 text-white">{{ __('backend.permissions_index.page_title') }}</h4>
                    <p class="mb-0" style="opacity: .9;">
                        {{ __('backend.permissions_index.page_subtitle') }}
                    </p>
                </div>
            </div>

            <div class="pb-20">
                <div class="table-responsive">
                    <table class="table table-striped hover nowrap mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th>#</th>
                                <th>{{ __('backend.permissions_index.name') }}</th>
                                <th>{{ __('backend.permissions_index.username') }}</th>
                                <th>{{ __('backend.permissions_index.email') }}</th>
                                <th>{{ __('backend.permissions_index.role') }}</th>
                                <th>{{ __('backend.permissions_index.status') }}</th>
                                <th width="180">{{ __('backend.permissions_index.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-primary" style="padding: 8px 12px; border-radius: 999px;">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->status === 'active')
                                            <span class="badge badge-success" style="padding: 8px 12px; border-radius: 999px;">
                                                {{ __('backend.permissions_index.status_active') }}
                                            </span>
                                        @elseif($user->status === 'pending')
                                            <span class="badge badge-warning" style="padding: 8px 12px; border-radius: 999px;">
                                                {{ __('backend.permissions_index.status_pending') }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary" style="padding: 8px 12px; border-radius: 999px;">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.permissions.show', $user->id) }}"
                                           class="btn btn-primary btn-sm"
                                           style="border-radius: 10px; font-weight: 600;">
                                            {{ __('backend.permissions_index.manage_permissions') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">{{ __('backend.permissions_index.no_users_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection