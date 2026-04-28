<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPitchDeck extends Model
{
    protected $fillable = [
        'project_id',
        'version',
        'pptx_path',
        'pdf_path',
        'status',
        'generation_error',
        'generated_at',
        'generated_by',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}