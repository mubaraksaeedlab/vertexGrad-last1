<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('investor_activities')) {
            Schema::create('investor_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('investor_id')->constrained('investors')->cascadeOnDelete();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action');
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('investor_activities')) {
            Schema::dropIfExists('investor_activities');
        }
    }
};