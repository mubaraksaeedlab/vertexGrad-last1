<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'file_path',
        'file_type',
        'uploaded_at',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
