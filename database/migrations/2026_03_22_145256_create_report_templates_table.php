<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('entity');
            $table->string('period')->default('monthly');
            $table->json('filters_json')->nullable();
            $table->json('columns_json')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_system')->default(false);
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_templates');
    }
};