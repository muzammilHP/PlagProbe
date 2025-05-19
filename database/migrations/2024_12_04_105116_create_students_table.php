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
    Schema::create('students', function (Blueprint $table) {
        $table->id(); // Primary key
        $table->string('username')->unique(); // Username for login
        $table->string('email')->unique(); // Email for login
        $table->string('contact')->nullable(); // Optional contact number
        $table->timestamp('email_verified_at')->nullable(); // For email verification
        $table->string('password'); // Hashed password
        $table->rememberToken(); // Token for "remember me" sessions
        $table->timestamps(); // created_at and updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
