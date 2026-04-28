<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_investments', function (Blueprint $table) {
            $table->text('message')->nullable()->after('amount');
        });

        DB::statement("
            ALTER TABLE project_investments
            MODIFY status ENUM('interested','requested','approved','rejected')
            NOT NULL DEFAULT 'interested'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE project_investments
            MODIFY status ENUM('interested','approved','rejected')
            NOT NULL DEFAULT 'interested'
        ");

        Schema::table('project_investments', function (Blueprint $table) {
            $table->dropColumn('message');
        });
    }
};
