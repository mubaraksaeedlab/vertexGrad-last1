<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduledReportReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $reportName;
    public string $frequency;
    public string $generatedAt;
    public string $filePath;

    public function __construct(string $reportName, string $frequency, string $generatedAt, string $filePath)
    {
        $this->reportName = $reportName;
        $this->frequency = $frequency;
        $this->generatedAt = $generatedAt;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Scheduled Report Ready: ' . $this->reportName)
            ->view('emails.scheduled-report-ready')
            ->attach($this->filePath, [
                'as' => basename($this->filePath),
                'mime' => 'application/pdf',
            ]);
    }
}