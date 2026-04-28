<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_role_policies', function (Blueprint $table) {
            $table->id();
            $table->string('role_name')->unique();

            $table->enum('email_verification_mode', ['required', 'optional', 'disabled'])->default('required');
            $table->enum('otp_mode', ['required', 'optional', 'disabled'])->default('required');

            $table->boolean('trusted_devices_enabled')->default(true);
            $table->boolean('recovery_codes_enabled')->default(true);
            $table->boolean('suspicious_login_alerts_enabled')->default(true);
            $table->boolean('remember_me_enabled')->default(true);
            $table->boolean('emergency_bypass_enabled')->default(false);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_role_policies');
    }
};