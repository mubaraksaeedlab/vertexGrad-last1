<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->string('file_name',255);
            $table->string('file_path',500);
            $table->string('file_type',50)->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

           $table->foreign('project_id')->references('project_id')->on('projects')->onDelete('set null');

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
    }
};
