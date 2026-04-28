<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('major', 50)->nullable()->change();
            $table->string('phone', 20)->nullable()->change();
            $table->string('address', 255)->nullable()->change();
           
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('major', 50)->nullable(false)->change();
            $table->string('phone', 20)->nullable(false)->change();
            $table->string('address', 255)->nullable(false)->change();
            
        });
    }
};
