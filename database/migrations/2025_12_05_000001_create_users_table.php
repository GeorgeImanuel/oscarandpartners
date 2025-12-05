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
            $table->string('username')->unique()->comment('Staff: admin, Client: 08123/Budi');
            $table->string('password_hash');
            $table->string('full_name');
            $table->enum('role', ['OWNER', 'ADMIN', 'FINANCE', 'CLIENT']);
            $table->boolean('is_active')->default(true)->comment('True=Active, False=Block');
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('username');
            $table->index('role');
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
