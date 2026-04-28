<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title');
            $table->text('body');

            $table->enum('audience', [
                'all',
                'students',
                'investors',
                'supervisors',
            ])->default('all');

            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamp('publish_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->index(['audience', 'is_active']);
            $table->index(['publish_at', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};