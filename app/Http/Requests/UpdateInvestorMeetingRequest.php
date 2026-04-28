<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvestorMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:150',
            'type'         => 'required|in:online,in_person,call',
            'status'       => 'required|in:scheduled,completed,cancelled',
            'meeting_at'   => 'required|date',
            'meeting_link' => 'nullable|string|max:500',
            'location'     => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ];
    }
}