<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_reminders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('investor_id')->constrained('investors')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->text('message')->nullable();

            $table->enum('type', ['meeting', 'follow_up', 'contract', 'custom'])->default('custom');
            $table->enum('status', ['pending', 'sent', 'completed', 'cancelled'])->default('pending');

            $table->dateTime('remind_at');

            $table->boolean('send_in_app')->default(true);
            $table->boolean('send_email')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_reminders');
    }
};