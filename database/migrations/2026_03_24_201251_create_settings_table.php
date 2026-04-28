<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('group', 100)->index();          // general, project, scanner...
            $table->string('key')->unique();                // platform_name
            $table->string('label');                        // Platform Name
            $table->text('value')->nullable();              // actual value
            $table->string('type', 50)->default('text');    // text, textarea, boolean, number, select, json, image
            $table->text('description')->nullable();        // help text
            $table->boolean('is_public')->default(false);   // for frontend readable values if needed
            $table->json('options')->nullable();            // select options etc

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};