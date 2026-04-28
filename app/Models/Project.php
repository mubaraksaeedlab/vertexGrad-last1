<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// Models
use App\Models\User;
use App\Models\ProjectTask;
use App\Models\ProjectFile;

class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'projects';
    protected $primaryKey = 'project_id';

    protected $fillable = [

        // ========================
        // Step 1
        // ========================
        'name',
        'description',
        'category',
        'project_type',
        'project_nature',
        'project_category_id',
        'problem_statement',
        'target_beneficiaries',

        // ========================
        // Step 2
        // ========================
        'student_name',
        'academic_level',
        'supervisor_name',
        'supervisor_title',
        'university_name',
        'college_name',
        'department',
        'governorate',

        // ========================
        // Step 3
        // ========================
        'is_feasible',
        'local_implementation',
        'expected_impact',
        'community_benefit',
        'needs_funding',
        'budget',
        'duration_months',
        'support_type',
        'budget_breakdown',

        // ========================
        // Milestones
        // ========================
        'milestone_1',
        'milestone_1_month',
        'milestone_2',
        'milestone_2_month',
        'milestone_3',
        'milestone_3_month',

        // ========================
        // System fields
        // ========================
        'status',
        'upload_token',

        'scanner_status',
        'scanner_project_id',
        'scan_score',
        'scan_report',
        'scanned_at',

        'student_id',
        'supervisor_id',
        'manager_id',
        'investor_id',

        'start_date',
        'end_date',
        'priority',
        'progress',
        'is_featured',
        'tags',
        'status_history',

        'frontend_url',
        'backend_url',
        'api_health_url',
        'admin_panel_url',
        'demo_account',
        'demo_password',
        'deployment_notes',

        'final_decision',
        'final_notes',
        'final_decided_at',
        'final_decided_by',
    ];

    protected $casts = [
        'tags' => 'array',
        'status_history' => 'array',
        'is_featured' => 'boolean',
        'scan_score' => 'decimal:2',
        'scanned_at' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'final_decided_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'project_id';
    }

    // =========================
    // Relationships
    // =========================

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }

    public function finalDecisionMaker()
    {
        return $this->belongsTo(User::class, 'final_decided_by');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class, 'project_id', 'project_id');
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class, 'project_id', 'project_id');
    }

    public function investors()
    {
        return $this->belongsToMany(
            User::class,
            'project_investments',
            'project_id',
            'investor_id',
            'project_id',
            'id'
        )->withPivot('status', 'amount')->withTimestamps();
    }

    public function approvedInvestments()
    {
        return $this->investors()
            ->wherePivot('status', 'approved');
    }

    public function meetings()
    {
        return $this->hasMany(\App\Models\ProjectMeeting::class, 'project_id', 'project_id');
    }

    public function requests()
    {
        return $this->hasMany(\App\Models\ProjectRequest::class, 'project_id', 'project_id');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\ProjectReview::class, 'project_id', 'project_id');
    }

    public function approvedReviews()
    {
        return $this->hasMany(\App\Models\ProjectReview::class, 'project_id', 'project_id')
            ->where('decision', 'approved');
    }
    public function pitchDecks()
{
    return $this->hasMany(\App\Models\ProjectPitchDeck::class, 'project_id', 'project_id');
}

public function latestPitchDeck()
{
    return $this->hasOne(\App\Models\ProjectPitchDeck::class, 'project_id', 'project_id')->latestOfMany();
}
public function projectCategory()
{
    return $this->belongsTo(\App\Models\ProjectCategory::class, 'project_category_id');
}
}