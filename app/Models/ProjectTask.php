<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use HasFactory;

    protected $primaryKey = 'task_id';

    protected $fillable = [
        'project_id','title','description','status','assigned_to','due_date','progress'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    // العلاقة مع المشروع
    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // العلاقة مع الطالب المسؤول
    public function assignedStudent() {
        return $this->belongsTo(Student::class, 'assigned_to');
    }public function tasks() {
    return $this->hasMany(ProjectTask::class, 'project_id');
}

// حساب نسبة التقدم تلقائياً
public function updateProgress()
{
    $totalTasks = $this->tasks()->count();
    if($totalTasks == 0){
        $this->progress = 0;
    } else {
        $progressSum = $this->tasks()->sum('progress');
        $this->progress = round($progressSum / $totalTasks);
    }
    $this->save();
}

}
