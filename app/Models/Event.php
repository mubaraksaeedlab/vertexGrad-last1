<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function up(): void
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('ename');
        $table->date('edate');
        $table->text('edesc')->nullable();
        $table->string('ecolor')->default('fc-bg-default');
        $table->string('eicon')->default('circle');
        $table->timestamps();
    });
}

}
