<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    if (!Schema::hasTable('project_meetings')) {
        Schema::create('project_meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->index();
            $table->string('title')->nullable();
            $table->dateTime('meeting_date')->nullable();
            $table->string('location')->nullable();
            $table->string('meeting_link', 500)->nullable();
            $table->string('status', 50)->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
}

public function down(): void
{
    Schema::dropIfExists('project_meetings');
}
};