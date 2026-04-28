<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'description',
        'ip',
        'device',
        'browser',
        'os',
    ];

    // علاقة بالنموذج User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


if (!function_exists('logActivity')) {
    function logActivity($action, $model = null, $description = null)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $agent = new Agent();

            // تحديث آخر نشاط
            $user->update(['last_activity' => now()]);

            // تسجيل النشاط
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'model' => $model,
                'description' => $description,
                'ip' => request()->ip(),
                'device' => $agent->device(),
                'browser' => $agent->browser(),
                'os' => $agent->platform(),
            ]);
        }
    }
}
