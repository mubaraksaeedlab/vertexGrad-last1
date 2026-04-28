<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorMeeting extends Model
{
    protected $fillable = [
        'investor_id',
        'created_by',
        'title',
        'type',
        'status',
        'meeting_at',
        'meeting_link',
        'location',
        'notes',
    ];

    protected $casts = [
        'meeting_at' => 'datetime',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}