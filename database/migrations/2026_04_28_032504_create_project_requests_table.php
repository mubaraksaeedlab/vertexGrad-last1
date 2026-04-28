<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    if (!Schema::hasTable('project_requests')) {
        Schema::create('project_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('supervisor_id')->nullable()->index();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('status', 50)->default('pending');
            $table->string('request_type', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
}

public function down(): void
{
    Schema::dropIfExists('project_requests');
}
};