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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Link Login ke USERS (1-to-1)');
            $table->foreignId('relation_id')->nullable()->constrained('relations')->onDelete('set null')->comment('Grup/Keluarga');
            $table->string('name')->comment('Nama Sesuai KTP/Polis');
            $table->string('phone')->comment('Contact Info');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('id_card_number')->nullable()->comment('NIK/NPWP');
            $table->timestamps();
            
            $table->unique('user_id');
            $table->index('relation_id');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
