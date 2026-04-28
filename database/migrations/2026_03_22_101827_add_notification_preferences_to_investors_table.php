<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->boolean('pref_in_app_notifications')->default(true)->after('status');
            $table->boolean('pref_email_notifications')->default(true)->after('pref_in_app_notifications');
            $table->boolean('pref_meeting_reminders')->default(true)->after('pref_email_notifications');
            $table->boolean('pref_announcements')->default(true)->after('pref_meeting_reminders');
        });
    }

    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn([
                'pref_in_app_notifications',
                'pref_email_notifications',
                'pref_meeting_reminders',
                'pref_announcements',
            ]);
        });
    }
};