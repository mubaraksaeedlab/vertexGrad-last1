<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    protected $fillable = [
        'name',
        'entity',
        'period',
        'filters_json',
        'columns_json',
        'created_by',
        'is_system',
    ];

    protected $casts = [
        'filters_json' => 'array',
        'columns_json' => 'array',
        'is_system' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}