<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_auth_policy_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->boolean('use_role_defaults')->default(true);

            $table->enum('email_verification_mode', ['required', 'optional', 'disabled'])->nullable();
            $table->enum('otp_mode', ['required', 'optional', 'disabled'])->nullable();

            $table->boolean('trusted_devices_enabled')->nullable();
            $table->boolean('recovery_codes_enabled')->nullable();
            $table->boolean('suspicious_login_alerts_enabled')->nullable();
            $table->boolean('remember_me_enabled')->nullable();
            $table->boolean('emergency_bypass_enabled')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_auth_policy_overrides');
    }
};