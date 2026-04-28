<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRequest extends Model
{
    protected $fillable = [
        'project_id',
        'supervisor_id',
        'student_id',
        'title',
        'request_type',
        'description',
        'due_date',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function responses()
{
    return $this->hasMany(ProjectRequestResponse::class, 'project_request_id', 'id');
}

public function latestResponse()
{
    return $this->hasOne(ProjectRequestResponse::class, 'project_request_id', 'id')->latestOfMany();
}
}