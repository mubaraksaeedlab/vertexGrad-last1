<div class="main-panel h-100">
    <div class="panel-head">
        <h2 class="panel-title">
            <i class="fa fa-sticky-note mr-2"></i> {{ __('backend.investors_show.notes') }}
        </h2>
        <div class="panel-subtitle">{{ __('backend.investors_show.notes_subtitle') }}</div>
    </div>

    <div class="table-wrap">
        <div class="students-table-card">
            <div class="p-3 p-md-4">
                <form action="{{ route('admin.investors.notes.store', $investor->user_id) }}" method="POST" class="mb-4">
                    @csrf

                    <div class="mb-3">
                        <label class="filter-label">{{ __('backend.investors_show.add_note') }}</label>
                        <textarea
                            name="note"
                            rows="4"
                            class="form-control filter-input"
                            placeholder="{{ __('backend.investors_show.write_note_placeholder') }}"
                            style="min-height: 120px;">{{ old('note') }}</textarea>

                        @error('note')
                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary search-btn">
                        <i class="fa fa-plus mr-1"></i> {{ __('backend.investors_show.add_note') }}
                    </button>
                </form>

                @if($investor->notes->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($investor->notes as $note)
                            <li class="py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: #eef2f7 !important;">
                                <div class="d-flex justify-content-between align-items-start" style="gap: 12px;">
                                    <div class="flex-grow-1">
                                        <div class="student-name-cell">{{ $note->user?->name ?? __('backend.investors_show.unknown_user') }}</div>
                                        <div class="student-muted-cell mb-2">{{ $note->created_at?->format('Y-m-d h:i A') }}</div>
                                        <div style="color: var(--text-main); line-height: 1.7;">
                                            {{ $note->note }}
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.investors.notes.delete', [$investor->user_id, $note->id]) }}"
                                          method="POST"
                                          onsubmit="return confirm('{{ __('backend.investors_show.confirm_delete_note') }}')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="action-btn btn-delete"
                                                title="{{ __('backend.investors_show.delete_note') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="fa fa-sticky-note mb-2"></i>
                        <div>{{ __('backend.investors_show.no_notes_available') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>