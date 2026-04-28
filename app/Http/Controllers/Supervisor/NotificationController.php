<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth('admin')->user();

        $notifications = $user
            ? $user->notifications()->latest()->paginate(15)
            : collect();

        return view('supervisor.notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
        $user = auth('admin')->user();

        return response()->json([
            'count' => $user ? $user->unreadNotifications()->count() : 0,
        ]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $user = auth('admin')->user();

        $notification = $user?->notifications()->where('id', $id)->firstOrFail();

        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $redirect = $request->input('redirect');

        if ($redirect) {
            return redirect($redirect);
        }

        return redirect()->route('supervisor.notifications.index');
    }

    public function markAllRead()
    {
        $user = auth('admin')->user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return back()->with('success', __('backend.notifications.marked_all_read'));
    }
}