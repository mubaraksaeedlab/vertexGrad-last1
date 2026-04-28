<div class="section-card h-100 investor-notes-card">
    <div class="section-header">
        <div class="title-wrap">
            <i class="fa fa-sticky-note"></i>
            <span>{{ __('backend.investors_show.notes') }}</span>
        </div>
    </div>

    <div class="section-body">
        <form action="{{ route('admin.investors.notes.store', $investor->user_id) }}"
              method="POST"
              class="mb-4 investor-note-form"
              data-submit-text="{{ __('backend.investors_show.add_note') }}"
              data-loading-text="{{ __('backend.investors_show.saving') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">{{ __('backend.investors_show.add_note') }}</label>
                <textarea name="note"
                          rows="4"
                          class="form-control auto-resize"
                          placeholder="{{ __('backend.investors_show.write_note_placeholder') }}">{{ old('note') }}</textarea>

                @error('note')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary-custom submit-btn">
                <i class="fa fa-plus mr-1"></i> {{ __('backend.investors_show.add_note') }}
            </button>
        </form>

        @if($investor->notes->count() > 0)
            <ul class="list-clean">
                @foreach($investor->notes as $note)
                    <li class="record-item">
                        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 12px;">
                            <div class="flex-grow-1">
                                <div class="record-title">{{ $note->user?->name ?? __('backend.investors_show.unknown_user') }}</div>
                                <div class="record-meta">{{ $note->created_at?->format('Y-m-d h:i A') }}</div>
                                <div class="record-text">{{ $note->note }}</div>
                            </div>

                            <form action="{{ route('admin.investors.notes.delete', [$investor->user_id, $note->id]) }}"
                                  method="POST"
                                  onsubmit="return confirm('{{ __('backend.investors_show.confirm_delete_note') }}')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="icon-delete-btn" title="{{ __('backend.investors_show.delete_note') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-state">
                <i class="fa fa-sticky-note"></i>
                <div>{{ __('backend.investors_show.no_notes_available') }}</div>
            </div>
        @endif
    </div>
</div>