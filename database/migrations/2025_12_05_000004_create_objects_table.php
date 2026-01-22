<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('object_occupation_id')
                ->constrained('object_occupation')
                ->onDelete('cascade'); // Jenis Objek
            $table->string('name'); // Nama Tertanggung
            $table->integer('tjh_value')->nullable(); // Nilai TJH
            $table->text('keterangan'); // Alamat / Jenis Kendaraan
            $table->decimal('tjh', 20, 2)->default(0)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objects');
    }
};
