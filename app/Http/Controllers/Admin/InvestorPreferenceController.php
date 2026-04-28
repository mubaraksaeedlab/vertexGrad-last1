<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInvestorPreferencesRequest;
use App\Models\Investor;
use App\Models\InvestorActivity;

class InvestorPreferenceController extends Controller
{
    public function edit(Investor $investor)
    {
        $investor->load('user');

        return view('investors.preferences', compact('investor'));
    }

    public function update(UpdateInvestorPreferencesRequest $request, Investor $investor)
    {
        $investor->update([
            'pref_in_app_notifications' => $request->boolean('pref_in_app_notifications'),
            'pref_email_notifications'  => $request->boolean('pref_email_notifications'),
            'pref_meeting_reminders'    => $request->boolean('pref_meeting_reminders'),
            'pref_announcements'        => $request->boolean('pref_announcements'),
        ]);

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'preferences_updated',
            'meta'        => [
                'in_app'   => $investor->pref_in_app_notifications,
                'email'    => $investor->pref_email_notifications,
                'meetings' => $investor->pref_meeting_reminders,
                'announce' => $investor->pref_announcements,
            ],
        ]);

        return redirect()
            ->route('admin.investors.show', $investor->user_id)
            ->with('success', 'Investor notification preferences updated successfully.');
    }
}