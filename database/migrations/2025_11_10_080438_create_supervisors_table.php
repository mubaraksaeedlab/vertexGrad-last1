<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('supervisors', function (Blueprint $table) {
        $table->id();
        // 🔥 ADD THIS LINE - It links the supervisor to the user account
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        
        $table->string('department', 50)->nullable();
        $table->string('specialization', 100)->nullable(); // Matches your Model fillable
        $table->string('phone', 20)->nullable();
        $table->string('address', 255)->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
