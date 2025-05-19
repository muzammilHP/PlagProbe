<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('assignments', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->date('completion_date');
        $table->string('type');
        $table->unsignedBigInteger('teacher_id');
        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        $table->unsignedBigInteger('classroom_id');
        $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_assignments'); 
        Schema::dropIfExists('assignments');
    }
};
