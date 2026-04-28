<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id', 'major', 'phone', 'address'
];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'student_supervisor')->withTimestamps();
    }
    public function getStatusAttribute()
{
    return $this->user ? $this->user->status : null;
}
}
