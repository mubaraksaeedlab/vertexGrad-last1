<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestorReminderRequest;
use App\Http\Requests\UpdateInvestorReminderRequest;
use App\Models\Investor;
use App\Models\InvestorReminder;
use App\Models\InvestorActivity;

class InvestorReminderController extends Controller
{
    public function index(Investor $investor)
    {
        $investor->load('user');

        $reminders = $investor->reminders()
            ->with('creator')
            ->latest('remind_at')
            ->paginate(10);

        return view('admin.investors.reminders.index', compact('investor', 'reminders'));
    }

    public function create(Investor $investor)
    {
        $investor->load('user');

        return view('admin.investors.reminders.create', compact('investor'));
    }

    public function store(StoreInvestorReminderRequest $request, Investor $investor)
    {
        $reminder = $investor->reminders()->create([
            'created_by'  => auth('admin')->id(),
            'title'       => $request->title,
            'message'     => $request->message,
            'type'        => $request->type,
            'status'      => $request->status,
            'remind_at'   => $request->remind_at,
            'send_in_app' => $request->boolean('send_in_app'),
            'send_email'  => $request->boolean('send_email'),
        ]);

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'reminder_created',
            'meta'        => [
                'reminder_id' => $reminder->id,
                'title'       => $reminder->title,
                'type'        => $reminder->type,
                'remind_at'   => optional($reminder->remind_at)->format('Y-m-d H:i:s'),
            ],
        ]);

        return redirect()
            ->route('admin.investors.reminders.index', $investor->id)
            ->with('success', 'Reminder created successfully.');
    }

    public function edit(Investor $investor, InvestorReminder $reminder)
    {
        return view('admin.investors.reminders.edit', compact('investor', 'reminder'));
    }

    public function update(UpdateInvestorReminderRequest $request, Investor $investor, InvestorReminder $reminder)
    {
        $reminder->update([
            'title'       => $request->title,
            'message'     => $request->message,
            'type'        => $request->type,
            'status'      => $request->status,
            'remind_at'   => $request->remind_at,
            'send_in_app' => $request->boolean('send_in_app'),
            'send_email'  => $request->boolean('send_email'),
        ]);

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'reminder_updated',
            'meta'        => [
                'reminder_id' => $reminder->id,
                'title'       => $reminder->title,
                'status'      => $reminder->status,
            ],
        ]);

        return redirect()
            ->route('admin.investors.reminders.index', $investor->id)
            ->with('success', 'Reminder updated successfully.');
    }

    public function destroy(Investor $investor, InvestorReminder $reminder)
    {
        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'reminder_deleted',
            'meta'        => [
                'reminder_id' => $reminder->id,
                'title'       => $reminder->title,
            ],
        ]);

        $reminder->delete();

        return redirect()
            ->route('admin.investors.reminders.index', $investor->id)
            ->with('success', 'Reminder deleted successfully.');
    }
}