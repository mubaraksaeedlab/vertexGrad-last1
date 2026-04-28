<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_activity')->nullable();

            $table->string('login_ip', 50)->nullable();
            $table->string('device', 150)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('os', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login',
                'last_activity',
                'login_ip',
                'device',
                'browser',
                'os',
            ]);
        });
    }
};
