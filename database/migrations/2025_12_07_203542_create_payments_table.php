<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            $table->integer('sequence')->comment('Urutan Pembayaran');
            $table->foreignId('created_by')->constrained('users')->comment('Finance yg buat Request');
            $table->decimal('amount', 20, 2);
            
            // CLIENT PAYMENT
            $table->foreignId('payment_status_id')->constrained('payment_statuses');
            $table->datetime('deadline_at')->comment('tambah dari tanggal start +30');
            $table->datetime('paid_at')->nullable();
            $table->decimal('paid_amount', 20, 2)->nullable()->comment('Jumlah yg dibayar nasabah dalam Rp');            
            // INSURANCE PAYMENT
            $table->foreignId('insurance_payment_status_id')->nullable()->constrained('payment_statuses');
            $table->datetime('insurance_paid_at')->nullable()->comment('STOP TIMER 24 JAM');
            $table->decimal('insurance_paid_amount', 20, 2)->nullable()->comment('Jumlah yg dibayar nasabah dalam Rp');
            
            $table->timestamps();

            $table->index('deadline_at');
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};