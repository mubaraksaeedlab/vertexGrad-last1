<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRequestResponse extends Model
{
    protected $fillable = [
        'project_request_id',
        'student_id',
        'response_text',
        'response_link',
        'attachment_path',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(ProjectRequest::class, 'project_request_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }
}