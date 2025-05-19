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
        Schema::table('assignments', function (Blueprint $table) {
        $table->dropForeign(['classroom_id']);
        $table->dropColumn('classroom_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
        $table->unsignedBigInteger('classroom_id');
        $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
    });
    }
};
