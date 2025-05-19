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
        Schema::table('reports', function (Blueprint $table) {
        $table->unsignedBigInteger('student_id')->after('id'); // you can change position if needed
        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
        $table->dropForeign(['student_id']);
        $table->dropColumn('student_id');
    });
    }
};
