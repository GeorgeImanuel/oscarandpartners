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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Siapa yang beraksi (Staff/Client)');
            $table->string('action')->comment('CREATE, EDIT, PRINT, LOGIN, VIEW');
            $table->string('target_table')->comment('Tabel yang berubah');
            $table->unsignedBigInteger('target_id')->nullable()->comment('ID Data yang berubah');
            $table->json('old_value')->nullable()->comment('Data Sebelum');
            $table->json('new_value')->nullable()->comment('Data Sesudah');
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at');
            
            $table->index('user_id');
            $table->index('action');
            $table->index('target_table');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
