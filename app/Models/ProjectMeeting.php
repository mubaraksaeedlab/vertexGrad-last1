<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMeeting extends Model
{
    protected $fillable = [
        'project_id',
        'supervisor_id',
        'student_id',
        'title',
        'meeting_type',
        'meeting_link',
        'meeting_date',
        'meeting_time',
        'status',
        'notes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}