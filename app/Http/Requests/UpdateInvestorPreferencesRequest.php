<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvestorPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pref_in_app_notifications' => 'nullable|boolean',
            'pref_email_notifications'  => 'nullable|boolean',
            'pref_meeting_reminders'    => 'nullable|boolean',
            'pref_announcements'        => 'nullable|boolean',
        ];
    }
}