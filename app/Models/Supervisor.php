<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'department',
    'specialization',
    'phone',   // Added to match migration
    'address', // Added to match migration
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_supervisor')->withTimestamps();
    }

    public function managers()
    {
        return $this->belongsToMany(Manager::class, 'manager_supervisor')->withTimestamps();
    }
}
