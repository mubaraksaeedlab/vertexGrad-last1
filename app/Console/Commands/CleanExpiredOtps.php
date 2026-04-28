<?php

namespace App\Console\Commands;

use App\Models\LoginOtp;
use Illuminate\Console\Command;

class CleanExpiredOtps extends Command
{
    protected $signature = 'auth:clean-expired-otps';
    protected $description = 'Delete expired or already-used old OTP records';

    public function handle(): int
    {
        $deleted = LoginOtp::where(function ($query) {
                $query->where('expires_at', '<', now()->subDay())
                      ->orWhereNotNull('verified_at');
            })
            ->delete();

        $this->info("Deleted {$deleted} old OTP records.");

        return self::SUCCESS;
    }
}