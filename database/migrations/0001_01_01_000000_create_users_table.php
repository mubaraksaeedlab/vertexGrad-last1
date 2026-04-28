<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50);
            $table->string('name', 150);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['Student','Supervisor','Manager','Investor','Admin'])->default('Student');
            $table->enum('status', ['active','inactive','pending','disabled'])->default('pending');
            $table->enum('gender', ['male','female'])->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('profile_image', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // هنا نضيف softDeletes مباشرة
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
