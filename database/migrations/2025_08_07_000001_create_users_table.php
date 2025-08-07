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
        Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username', 50)->unique();
        $table->string('fullname', 100)->nullable();
        $table->string('email', 100)->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->enum('level', ['admin', 'user']);
        $table->enum('type', ['DS', 'SC', 'MF', 'FW'])->nullable();
        $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
        $table->timestamp('last_login')->nullable();
        $table->timestamps();
            
        $table->index(['level', 'status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};