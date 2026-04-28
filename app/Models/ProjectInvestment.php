<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectInvestment extends Model
{
    protected $table = 'project_investments';

    protected $fillable = [
        'project_id',
        'investor_id',
        'status',
        'amount',
        'message',
    ];

    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }
}
