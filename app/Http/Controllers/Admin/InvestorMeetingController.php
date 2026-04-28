<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestorMeetingRequest;
use App\Http\Requests\UpdateInvestorMeetingRequest;
use App\Models\Investor;
use App\Models\InvestorMeeting;
use App\Models\InvestorActivity;

class InvestorMeetingController extends Controller
{
    public function index(Investor $investor)
    {
        $investor->load(['user', 'meetings.creator']);

        $meetings = $investor->meetings()
            ->with('creator')
            ->latest('meeting_at')
            ->paginate(10);

        return view('investors.meetings.index', compact('investor', 'meetings'));
    }

    public function create(Investor $investor)
    {
        $investor->load('user');

        return view('investors.meetings.create', compact('investor'));
    }

    public function store(StoreInvestorMeetingRequest $request, Investor $investor)
    {
        $meeting = $investor->meetings()->create([
            'created_by'   => auth('admin')->id(),
            'title'        => $request->title,
            'type'         => $request->type,
            'status'       => $request->status,
            'meeting_at'   => $request->meeting_at,
            'meeting_link' => $request->meeting_link,
            'location'     => $request->location,
            'notes'        => $request->notes,
        ]);

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'meeting_created',
            'meta'        => [
                'meeting_id' => $meeting->id,
                'title'      => $meeting->title,
                'meeting_at' => $meeting->meeting_at?->format('Y-m-d H:i:s'),
            ],
        ]);

        return redirect()
            ->route('investors.meetings.index', $investor->id)
            ->with('success', 'Meeting created successfully.');
    }

    public function edit(Investor $investor, InvestorMeeting $meeting)
    {
        return view('investors.meetings.edit', compact('investor', 'meeting'));
    }

    public function update(UpdateInvestorMeetingRequest $request, Investor $investor, InvestorMeeting $meeting)
    {
        $meeting->update($request->validated());

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'meeting_updated',
            'meta'        => [
                'meeting_id' => $meeting->id,
                'title'      => $meeting->title,
                'status'     => $meeting->status,
            ],
        ]);

        return redirect()
            ->route('investors.meetings.index', $investor->id)
            ->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Investor $investor, InvestorMeeting $meeting)
    {
        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'meeting_deleted',
            'meta'        => [
                'meeting_id' => $meeting->id,
                'title'      => $meeting->title,
            ],
        ]);

        $meeting->delete();

        return redirect()
            ->route('investors.meetings.index', $investor->id)
            ->with('success', 'Meeting deleted successfully.');
    }
}