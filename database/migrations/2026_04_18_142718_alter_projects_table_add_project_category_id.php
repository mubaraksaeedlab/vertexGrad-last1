<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('projects', 'project_category_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedBigInteger('project_category_id')->nullable()->after('category');
            });

            try {
                Schema::table('projects', function (Blueprint $table) {
                    $table->foreign('project_category_id')
                        ->references('id')
                        ->on('project_categories')
                        ->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // ignore if FK creation fails temporarily
            }
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            try {
                $table->dropForeign(['project_category_id']);
            } catch (\Throwable $e) {
            }

            if (Schema::hasColumn('projects', 'project_category_id')) {
                $table->dropColumn('project_category_id');
            }
        });
    }
};