<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->after('id');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']); // Drop foreign key
            $table->dropColumn('teacher_id'); // Drop column
        });
    }
};
