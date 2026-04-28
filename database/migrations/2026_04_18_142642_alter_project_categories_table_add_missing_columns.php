<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('project_categories', 'name_en')) {
                $table->string('name_en', 150)->nullable()->after('id');
            }

            if (!Schema::hasColumn('project_categories', 'name_ar')) {
                $table->string('name_ar', 150)->nullable()->after('name_en');
            }

            if (!Schema::hasColumn('project_categories', 'slug')) {
                $table->string('slug', 150)->nullable()->after('name_ar');
            }

            if (!Schema::hasColumn('project_categories', 'deck_theme')) {
                $table->string('deck_theme', 100)->default('default')->after('slug');
            }

            if (!Schema::hasColumn('project_categories', 'accent_color')) {
                $table->string('accent_color', 20)->nullable()->after('deck_theme');
            }

            if (!Schema::hasColumn('project_categories', 'icon')) {
                $table->string('icon', 100)->nullable()->after('accent_color');
            }

            if (!Schema::hasColumn('project_categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('icon');
            }

            if (!Schema::hasColumn('project_categories', 'created_at') && !Schema::hasColumn('project_categories', 'updated_at')) {
                $table->timestamps();
            }
        });

        // Make slug unique only after column exists
        $columns = collect(DB::select("SHOW COLUMNS FROM project_categories"))->pluck('Field')->all();

        if (in_array('slug', $columns, true)) {
            try {
                DB::statement('ALTER TABLE project_categories ADD UNIQUE project_categories_slug_unique (slug)');
            } catch (\Throwable $e) {
                // ignore if already exists
            }
        }
    }

    public function down(): void
    {
        Schema::table('project_categories', function (Blueprint $table) {
            // intentionally left safe/minimal
        });
    }
};