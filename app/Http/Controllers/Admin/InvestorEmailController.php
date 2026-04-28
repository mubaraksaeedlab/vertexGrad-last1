<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ManualInvestorEmail;
use App\Models\Investor;
use App\Models\InvestorActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvestorEmailController extends Controller
{
    public function create(Investor $investor)
    {
        $investor->load('user');

        return view('investors.email', compact('investor'));
    }

    public function store(Request $request, Investor $investor)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:10000',
        ]);

        if (! $investor->user || ! $investor->user->email) {
            return back()->with('error', 'Investor email not found.');
        }

        if (! $investor->pref_email_notifications) {
            return back()->with('error', 'This investor has email notifications disabled.');
        }

        Mail::to($investor->user->email)->send(
            new ManualInvestorEmail($data['subject'], $data['message'])
        );

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'manual_email_sent',
            'meta'        => [
                'subject' => $data['subject'],
                'email'   => $investor->user->email,
            ],
        ]);

        return redirect()
            ->route('admin.investors.show', $investor->user_id)
            ->with('success', 'Email sent to investor successfully.');
    }
}