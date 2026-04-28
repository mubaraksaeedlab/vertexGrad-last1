<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'company',
        'position',
        'investment_type',
        'budget',
        'source',
        'notes',
        'status',
        'pref_in_app_notifications',
        'pref_email_notifications',
        'pref_meeting_reminders',
        'pref_announcements',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'deleted_at' => 'datetime',
        'pref_in_app_notifications' => 'boolean',
        'pref_email_notifications' => 'boolean',
        'pref_meeting_reminders' => 'boolean',
        'pref_announcements' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investorNotes()
    {
        return $this->hasMany(InvestorNote::class, 'investor_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(InvestorFile::class, 'investor_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(InvestorActivity::class, 'investor_id', 'id');
    }

 public function investmentRequests()
{
    return $this->hasMany(ProjectInvestment::class, 'investor_id', 'user_id');
}
public function meetings()
{
    return $this->hasMany(\App\Models\InvestorMeeting::class);
}
public function contracts()
{
    return $this->hasMany(\App\Models\InvestorContract::class);
}

public function reminders()
{
    return $this->hasMany(\App\Models\InvestorReminder::class);
}
}