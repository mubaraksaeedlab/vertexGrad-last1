<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_pitch_decks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->unsignedInteger('version')->default(1);

            $table->string('pptx_path')->nullable();
            $table->string('pdf_path')->nullable();

            $table->enum('status', ['pending', 'generated', 'failed'])->default('pending');
            $table->text('generation_error')->nullable();

            $table->timestamp('generated_at')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();

            $table->timestamps();

            $table->foreign('project_id')
                ->references('project_id')
                ->on('projects')
                ->onDelete('cascade');

            $table->foreign('generated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index(['project_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_pitch_decks');
    }
};