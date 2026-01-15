<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relation_id')->comment('Grup/Keluarga')->constrained('relations');
            $table->string('id_number')->unique()->comment('NIK/NPWP');
            $table->string('id_name')->comment('Nama Tertanggung');
            $table->text('id_address')->comment('Alamat Tertanggung');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('clients'); }
};