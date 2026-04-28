<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'ip', 'device', 'browser', 'os', 'login_at', 'session_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
