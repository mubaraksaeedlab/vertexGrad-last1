<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();

            // اسم الصلاحية الظاهر
            $table->string('name', 150)->unique();

            // الاسم البرمجي الثابت
            $table->string('slug', 150)->unique();

            // المجموعة التي تنتمي لها الصلاحية
            $table->string('group', 100)->nullable();

            // وصف اختياري
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};