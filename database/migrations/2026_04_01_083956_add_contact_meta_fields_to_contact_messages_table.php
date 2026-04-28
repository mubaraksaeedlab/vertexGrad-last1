<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('sender_type')->default('guest')->after('status');
            $table->unsignedBigInteger('sender_user_id')->nullable()->after('sender_type');
            $table->unsignedBigInteger('assigned_admin_id')->nullable()->after('sender_user_id');
            $table->string('ip_address', 45)->nullable()->after('assigned_admin_id');
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn([
                'sender_type',
                'sender_user_id',
                'assigned_admin_id',
                'ip_address',
                'user_agent',
            ]);
        });
    }
};