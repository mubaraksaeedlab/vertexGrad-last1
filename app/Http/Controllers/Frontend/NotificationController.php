<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth('web')->user();

        $notifications = $user->notifications()->latest()->paginate(15);

        return view('frontend.notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => auth('web')->user()->unreadNotifications()->count()
        ]);
    }
public function latest()
{
    $user = auth('web')->user();

    $notifications = $user->notifications()
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Notification',
                'message' => $notification->data['message'] ?? '',
                'url' => $notification->data['url'] ?? null,
                'icon' => $notification->data['icon'] ?? 'fas fa-bell',
                'type' => $notification->data['type'] ?? 'info',
                'read_at' => $notification->read_at,
                'created_at_human' => $notification->created_at?->diffForHumans(),
            ];
        });

    return response()->json([
        'notifications' => $notifications,
        'unread_count' => $user->unreadNotifications()->count(),
    ]);
}
    public function markAsRead(Request $request, $id)
    {
        $notification = auth('web')->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        $redirect = $request->input('redirect');
        if ($redirect) {
            return redirect($redirect);
        }

        return back();
    }

    public function markAllRead()
    {
        auth('web')->user()->unreadNotifications->markAsRead();

        return back();
    }
}