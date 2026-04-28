<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // ربط الطالب بجدول المستخدمين
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // إذا تم حذف المستخدم، يُحذف الطالب تلقائيًا

            // بيانات الطالب الخاصة
            $table->string('major', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
