<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'sender_type',
        'sender_user_id',
        'assigned_admin_id',
        'ip_address',
        'user_agent',
    ];

    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_CLOSED = 'closed';

    public function replies()
    {
        return $this->hasMany(ContactMessageReply::class)->latest();
    }
    public function notes()
{
    return $this->hasMany(ContactMessageNote::class)->latest();
}
    

    public function getSubjectLabelAttribute(): string
    {
        return match ($this->subject) {
            'academic' => 'Academic Submission Inquiry',
            'investor' => 'Investor / Funding Inquiry',
            'support' => 'Technical Support',
            'other' => 'Other / General Inquiry',
            default => ucfirst($this->subject),
        };
    }

    public function getSenderTypeLabelAttribute(): string
    {
        return match ($this->sender_type) {
            'guest' => 'Guest',
            'student' => 'Student',
            'investor' => 'Investor',
            default => ucfirst((string) $this->sender_type),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => 'New',
            'in_progress' => 'In Progress',
            'replied' => 'Replied',
            'closed' => 'Closed',
            default => ucfirst((string) $this->status),
        };
    }
    
}