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
            $table->foreignId('object_occupation_id')->constrained('object_occupation')->onUpdate('cascade')->onDelete('restrict')->comment('Jenis Objek');
            $table->string('name')->comment('Nama Tertanggung');
            $table->integer('tjh_value')->nullable()->comment('Nilai TJH'); 
            $table->text('keterangan')->comment('Alamat / Jenis Kendaraan'); 
            $table->decimal('tjh', 20, 2)->default(0)->nullable()->comment('TJH');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objects');
    }
};
