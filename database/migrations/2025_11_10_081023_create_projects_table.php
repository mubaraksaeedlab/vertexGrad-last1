<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id('project_id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('category', 50)->nullable();
            $table->enum('status', ['pending', 'scan_requested', 'awaiting_manual_review', 'approved', 'published', 'active', 'completed', 'rejected', 'scan_failed'])->nullable()->default('pending');

            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('supervisor_id')->nullable()->constrained('users');
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->foreignId('investor_id')->nullable()->constrained('users');


            // الحقول الإضافية
            $table->decimal('budget', 10,2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('priority', ['Low','Medium','High'])->default('Medium');
            $table->integer('progress')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->json('tags')->nullable();
            $table->json('status_history')->nullable(); 
// لتتبع كل حالة تغير

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
