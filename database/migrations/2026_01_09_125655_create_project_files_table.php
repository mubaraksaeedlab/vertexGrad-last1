<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();

            // ربط الملف بالمشروع
            $table->unsignedBigInteger('project_id');

            // مسار الملف في storage
            $table->string('file_path');

            // نوع الملف (image / video / pdf)
            $table->string('file_type', 20);

            $table->timestamps();

            // المفتاح الخارجي
            $table->foreign('project_id')
                  ->references('project_id')
                  ->on('projects')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
