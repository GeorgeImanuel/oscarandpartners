<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->onUpdate('cascade')->onDelete('restrict');
            $table->date('incident_date');
            $table->date('insurance_report_date');
            $table->text('chronology');
            $table->string('docs')->comment('Dokumen yg diperlukan untuk claim');
            $table->bigInteger('casco')->comment('Claim Nilai unit');
            $table->bigInteger('tjh')->comment('Claim jika ada pihak ke-3');
            $table->text('note')->nullable()->comment('Keterangan Proses Claim');
            $table->date('payment_date')->nullable()->comment('Tanggal Bayar Claim');
            $table->bigInteger('amount')->comment('Jumlah Uang claim');
            $table->timestamps();

            $table->index('incident_date');
        });
    }
    public function down(): void { Schema::dropIfExists('claims'); }
};