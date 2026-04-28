<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvestorContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => 'required|string|max:150',
            'type'       => 'nullable|string|max:100',
            'status'     => 'required|in:draft,active,expired,cancelled',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'file'       => 'nullable|file|max:10240',
            'notes'      => 'nullable|string',
        ];
    }
}