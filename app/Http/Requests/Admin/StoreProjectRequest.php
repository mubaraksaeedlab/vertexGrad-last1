<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        // You already protect admin routes with auth:admin
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'status' => 'required|in:Pending,Active,Completed',

            'student_id' => ['required', Rule::exists('users', 'id')->where('role', 'Student')],
            'supervisor_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'Supervisor')],
            'manager_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'Manager')],
            'investor_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'Investor')],

            'budget' => 'nullable|numeric|min:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|in:Low,Medium,High',
            'progress' => 'nullable|integer|min:0|max:100',

            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|array',

            // Spatie media (same as student)
            'project_photos' => 'nullable|array',
            'project_photos.*' => 'image|max:5120', // 5MB each
            'project_video' => 'nullable|mimetypes:video/mp4,video/quicktime,video/ogg|max:51200', // 50MB
        ];
    }

    protected function prepareForValidation(): void
    {
        // Ensure checkbox comes as boolean
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}