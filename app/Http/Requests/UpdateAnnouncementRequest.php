<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'body'        => ['required', 'string'],
            'audience'    => ['required', 'in:all,students,investors,supervisors'],
            'is_pinned'   => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],
            'publish_at'  => ['nullable', 'date'],
            'expires_at'  => ['nullable', 'date', 'after_or_equal:publish_at'],
        ];
    }
}