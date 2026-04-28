<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.utility.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'in:academic,investor,support,other'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $user = auth('web')->user();

        $senderType = 'guest';
        $senderUserId = null;

        if ($user) {
            $senderUserId = $user->id;

            if (isset($user->role)) {
                $role = strtolower(trim($user->role));

                if ($role === 'student') {
                    $senderType = 'student';
                } elseif ($role === 'investor') {
                    $senderType = 'investor';
                }
            }
        }

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'new',
            'sender_type' => $senderType,
            'sender_user_id' => $senderUserId,
            'assigned_admin_id' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('utility.contact')
            ->with('success', 'Your message has been sent successfully.');
    }
}