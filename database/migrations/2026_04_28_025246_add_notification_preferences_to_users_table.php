<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (! Schema::hasColumn('users', 'notif_status_change')) {
            $table->boolean('notif_status_change')->default(true)->after('remember_token');
        }

        if (! Schema::hasColumn('users', 'notif_investor_interest')) {
            $table->boolean('notif_investor_interest')->default(true)->after('notif_status_change');
        }
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'notif_investor_interest')) {
            $table->dropColumn('notif_investor_interest');
        }

        if (Schema::hasColumn('users', 'notif_status_change')) {
            $table->dropColumn('notif_status_change');
        }
    });
}

};
