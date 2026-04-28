<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvestorReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:150',
            'message'     => 'nullable|string',
            'type'        => 'required|in:meeting,follow_up,contract,custom',
            'status'      => 'required|in:pending,sent,completed,cancelled',
            'remind_at'   => 'required|date',
            'send_in_app' => 'nullable|boolean',
            'send_email'  => 'nullable|boolean',
        ];
    }
}