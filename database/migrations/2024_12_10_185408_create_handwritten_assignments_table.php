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
        Schema::create('handwritten_assignments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Foreign key to students table
            $table->string('file_name'); // File name
            $table->string('file_path'); // Path to the file on the server
            $table->text('text_content')->nullable(); // Extracted text content (nullable for non-text assignments)
            $table->integer('assignment_number'); // 1 for Assignment 1, 2 for Assignment 2
            $table->enum('assignment_type', ['text']); // Only 'text' for this table
            $table->timestamps(); // Automatically adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hanwritten_assignments');
    }
};
