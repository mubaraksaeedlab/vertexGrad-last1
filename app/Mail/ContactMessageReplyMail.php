<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\ContactMessageReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public ContactMessage $contactMessage;
    public ContactMessageReply $reply;

    public function __construct(ContactMessage $contactMessage, ContactMessageReply $reply)
    {
        $this->contactMessage = $contactMessage;
        $this->reply = $reply;
    }

    public function build()
    {
        return $this->subject('Re: ' . $this->contactMessage->subject_label)
            ->view('emails.contact-reply');
    }
}