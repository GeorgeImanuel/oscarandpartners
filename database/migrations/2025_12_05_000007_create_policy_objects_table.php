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
        Schema::create('policy_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->enum('type', ['VEHICLE', 'PROPERTY', 'HEALTH', 'OTHER']);
            $table->string('description')->comment('Nama Aset (e.g. Innova Zenix)');
            $table->string('identifier_1')->nullable()->comment('No Rangka / Alamat (Kunci Print Lock)');
            $table->string('identifier_2')->nullable()->comment('No Mesin');
            $table->decimal('sum_insured', 15, 2)->default(0)->comment('Nilai Pertanggungan');
            $table->string('plate_number')->nullable()->comment('Plat No');
            $table->integer('year')->nullable()->comment('Tahun Pembuatan');
            $table->timestamps();
            
            $table->index('policy_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_objects');
    }
};
