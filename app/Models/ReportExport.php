<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportExport extends Model
{
    protected $fillable = [
        'scheduled_report_id',
        'report_template_id',
        'user_id',
        'format',
        'file_path',
        'status',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function scheduledReport()
    {
        return $this->belongsTo(ScheduledReport::class, 'scheduled_report_id');
    }

    public function template()
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}