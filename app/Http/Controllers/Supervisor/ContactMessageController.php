<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReplyMail;
use App\Models\ContactMessage;
use App\Models\ContactMessageReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Services\AuditLogService;

class ContactMessageController extends Controller
{
    protected function requirePermission(string $permissionSlug): void
    {
        $user = auth('admin')->user();

        if (!$user || !$user->hasPermission($permissionSlug)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }

    public function index(Request $request)
    {
        $this->requirePermission('view_contact_messages');

        $query = ContactMessage::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('sender_type')) {
            $query->where('sender_type', $request->sender_type);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        $messages = $query->paginate(12)->withQueryString();

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed contact messages list',
            category: 'contact_messages',
            properties: [
                'filters' => $request->only(['status', 'subject', 'sender_type', 'search']),
                'results_count' => $messages->count(),
            ]
        );

        return view('supervisor.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $this->requirePermission('view_contact_messages');

        $contactMessage->load('replies.admin');

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed contact message #' . $contactMessage->id,
            category: 'contact_messages',
            subject: $contactMessage,
            properties: [
                'contact_message_id' => $contactMessage->id,
                'sender_email' => $contactMessage->email,
                'subject' => $contactMessage->subject,
                'status' => $contactMessage->status,
            ]
        );

        return view('supervisor.contact-messages.show', compact('contactMessage'));
    }

    public function updateStatus(Request $request, ContactMessage $contactMessage)
    {
        $this->requirePermission('update_contact_message_status');

        $validated = $request->validate([
            'status' => ['required', 'in:new,in_progress,replied,closed'],
        ]);

        $contactMessage->update([
            'status' => $validated['status'],
        ]);

        AuditLogService::log(
            event: 'status_updated',
            description: 'Updated status for contact message #' . $contactMessage->id,
            category: 'contact_messages',
            subject: $contactMessage,
            properties: [
                'contact_message_id' => $contactMessage->id,
                'new_status' => $validated['status'],
            ]
        );

        return redirect()
            ->route('supervisor.contact-messages.show', $contactMessage)
            ->with('success', 'Contact message status updated successfully.');
    }

    public function sendReply(Request $request, ContactMessage $contactMessage)
    {
        $this->requirePermission('reply_contact_messages');

        $validated = $request->validate([
            'reply_message' => ['required', 'string', 'max:5000'],
        ]);

        $reply = ContactMessageReply::create([
            'contact_message_id' => $contactMessage->id,
            'admin_id' => auth('admin')->id(),
            'reply_message' => $validated['reply_message'],
            'channel' => 'email',
            'is_sent' => false,
            'sent_at' => null,
        ]);

        Mail::to($contactMessage->email)->send(
            new ContactMessageReplyMail($contactMessage, $reply)
        );

        $reply->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);

        $contactMessage->update([
            'status' => 'replied',
        ]);

        AuditLogService::log(
            event: 'reply_sent',
            description: 'Sent reply for contact message #' . $contactMessage->id,
            category: 'contact_messages',
            subject: $contactMessage,
            properties: [
                'contact_message_id' => $contactMessage->id,
                'reply_id' => $reply->id,
                'recipient_email' => $contactMessage->email,
                'channel' => 'email',
            ]
        );

        return redirect()
            ->route('supervisor.contact-messages.show', $contactMessage)
            ->with('success', 'Reply sent successfully.');
    }
}