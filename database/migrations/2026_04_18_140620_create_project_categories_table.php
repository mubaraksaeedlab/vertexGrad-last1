<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name_en', 150);
            $table->string('name_ar', 150)->nullable();
            $table->string('slug', 150)->unique();

            $table->string('deck_theme', 100)->default('default');
            $table->string('accent_color', 20)->nullable();
            $table->string('icon', 100)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_categories');
    }
};