<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'sent_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')->withTimestamps();
    }
}
