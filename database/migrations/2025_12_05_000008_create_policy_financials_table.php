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
        Schema::create('policy_financials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->foreignId('payment_batch_id')->nullable()->constrained('payment_batches')->onDelete('set null')->comment('Link ke Batch Pembayaran Owner');
            
            // Nilai Uang
            $table->decimal('premium_gross', 15, 2)->default(0)->comment('Tagihan ke Klien');
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('admin_fee', 15, 2)->default(0);
            $table->decimal('commission_pct', 5, 2)->default(0);
            $table->decimal('commission_amount', 15, 2)->default(0);
            $table->decimal('net_to_insurance', 15, 2)->default(0)->comment('Wajib setor ke Principal');
            
            // LOGIC 1: CLIENT PAYMENT (TIMER START)
            $table->enum('client_payment_status', ['UNPAID', 'PAID'])->default('UNPAID');
            $table->timestamp('client_paid_at')->nullable()->comment('START TIMER 24 JAM');
            $table->enum('collector_type', ['STAFF', 'OWNER'])->nullable();
            
            // LOGIC 2: INSURANCE PAYMENT (TIMER STOP)
            $table->enum('insurance_payment_status', ['UNPAID', 'PAID'])->default('UNPAID');
            $table->timestamp('insurance_paid_at')->nullable()->comment('STOP TIMER 24 JAM');
            
            // LOGIC 3: COMMISSION
            $table->boolean('is_commission_received')->default(false);
            
            $table->timestamps();
            
            $table->index('policy_id');
            $table->index('payment_batch_id');
            $table->index('client_payment_status');
            $table->index('insurance_payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_financials');
    }
};
