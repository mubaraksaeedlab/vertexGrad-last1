<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->id();

            // ربط مع جدول المستخدمين
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('phone', 50)->nullable();
            $table->string('company', 150)->nullable();
            $table->string('position', 150)->nullable();
            $table->string('investment_type', 100)->nullable();
            $table->decimal('budget', 15,2)->nullable();
            $table->string('source', 100)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
