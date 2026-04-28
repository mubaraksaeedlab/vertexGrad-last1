<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorReminder extends Model
{
    protected $fillable = [
        'investor_id',
        'created_by',
        'title',
        'message',
        'type',
        'status',
        'remind_at',
        'send_in_app',
        'send_email',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'send_in_app' => 'boolean',
        'send_email' => 'boolean',
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