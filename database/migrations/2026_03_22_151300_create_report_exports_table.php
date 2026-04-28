<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_exports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scheduled_report_id')->nullable();
            $table->unsignedBigInteger('report_template_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('format')->default('pdf');
            $table->string('file_path')->nullable();
            $table->string('status')->default('completed');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->foreign('scheduled_report_id')
                ->references('id')
                ->on('scheduled_reports')
                ->nullOnDelete();

            $table->foreign('report_template_id')
                ->references('id')
                ->on('report_templates')
                ->nullOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_exports');
    }
};