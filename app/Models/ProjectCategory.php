<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'deck_theme',
        'accent_color',
        'icon',
        'is_active',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'project_category_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->name_ar ?: $this->name_en)
            : $this->name_en;
    }
}