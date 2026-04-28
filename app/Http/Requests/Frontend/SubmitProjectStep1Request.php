<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class SubmitProjectStep1Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_title' => 'required|string|max:150',

            'abstract' => 'required|string|max:2000',

            'discipline' => 'required|string|max:100',

            'project_type' => 'required|string|max:50',

            'problem_statement' => 'required|string|max:2000',

            'target_beneficiaries' => 'required|string|max:500',

            'project_nature' => 'required|string|max:50',
        ];
    }
}
