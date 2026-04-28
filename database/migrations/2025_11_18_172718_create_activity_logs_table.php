<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم المرتبط بالنشاط
            $table->string('action'); // مثل View Page, Create, Update, Delete
            $table->string('model')->nullable(); // النموذج المتأثر (User, Post...)
            $table->text('description')->nullable(); // وصف النشاط
            $table->string('ip', 50)->nullable();
            $table->string('device', 150)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};
