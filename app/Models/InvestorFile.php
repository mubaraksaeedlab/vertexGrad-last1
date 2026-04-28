<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id',
        'filename',
        'path',
        'mime',
        'size',
        'uploaded_by',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}