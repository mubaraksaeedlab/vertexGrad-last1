<ul class="list-unstyled mb-0">
    @forelse($permissions as $perm)
        <li>
            {{ $perm->permission_name }}
            @if($perm->status == 'inactive')
                <span class="badge bg-secondary">{{ __('backend.permissions_common.inactive') }}</span>
            @endif
        </li>
    @empty
        <li>{{ __('backend.permissions_common.empty_value') }}</li>
    @endforelse
</ul>