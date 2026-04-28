<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use Illuminate\Http\Request;
use App\Notifications\ManualInvestorMessageNotification;
use App\Models\InvestorActivity;

class InvestorCommunicationController extends Controller
{
    public function create(Investor $investor)
    {
        $investor->load('user');

        return view('investors.notify', compact('investor'));
    }

    public function store(Request $request, Investor $investor)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:150',
            'message' => 'required|string|max:5000',
            'url'     => 'nullable|string|max:500',
        ]);

        if (! $investor->user) {
            return back()->with('error', 'Investor user account not found.');
        }

        $investor->user->notify(
            new ManualInvestorMessageNotification(
                $data['title'],
                $data['message'],
                $data['url'] ?? null
            )
        );

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'manual_notification_sent',
            'meta'        => [
                'title' => $data['title'],
                'url'   => $data['url'] ?? null,
            ],
        ]);

        return redirect()
            ->route('investors.show', $investor->user_id)
            ->with('success', 'Notification sent to investor successfully.');
    }
}