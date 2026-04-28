<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_investments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('investor_id');

            $table->enum('status', ['interested', 'approved', 'rejected'])->default('interested');
            $table->decimal('amount', 12, 2)->nullable();

            $table->timestamps();

            $table->foreign('project_id')
                ->references('project_id')
                ->on('projects')
                ->onDelete('cascade');

            $table->foreign('investor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unique(['project_id', 'investor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_investments');
    }
};