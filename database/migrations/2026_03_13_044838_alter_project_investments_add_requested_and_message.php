<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE project_investments
            MODIFY status ENUM('interested','requested','approved','rejected')
            NOT NULL DEFAULT 'interested'
        ");

        Schema::table('project_investments', function (Blueprint $table) {
            if (!Schema::hasColumn('project_investments', 'message')) {
                $table->text('message')->nullable()->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_investments', function (Blueprint $table) {
            if (Schema::hasColumn('project_investments', 'message')) {
                $table->dropColumn('message');
            }
        });

        DB::statement("
            ALTER TABLE project_investments
            MODIFY status ENUM('interested','approved','rejected')
            NOT NULL DEFAULT 'interested'
        ");
    }
};