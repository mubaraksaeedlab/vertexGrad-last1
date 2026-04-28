<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorContract extends Model
{
    protected $fillable = [
        'investor_id',
        'created_by',
        'title',
        'type',
        'status',
        'start_date',
        'end_date',
        'file_path',
        'file_name',
        'file_size',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}