<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('object_occupation_id')->constrained('object_occupations');
            $table->string('name')->comment('Nama Tertanggung');
            $table->string('keterangan')->comment('Alamat / Jenis Kendaraan');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('objects'); }
};