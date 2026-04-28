<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvestorRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
{
    $investor = $this->route('investor');
    $userId = $investor?->user_id;

    return [
        'name' => 'required|string|max:150',
        'email' => [
            'nullable',
            'email',
            Rule::unique('users', 'email')->ignore($userId),
        ],
        'phone' => 'nullable|string|max:50',
        'company' => 'nullable|string|max:150',
        'position' => 'nullable|string|max:150',
        'investment_type' => 'nullable|string|max:100',
        'budget' => 'nullable|numeric',
        'source' => 'nullable|string|max:100',
        'notes' => 'nullable|string',
    ];
}

}
