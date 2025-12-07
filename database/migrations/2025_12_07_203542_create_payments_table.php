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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->comment('Finance yg buat Request');
            $table->foreignId('collected_by')->constrained('users')->comment('User tanggungjawab penagih');
            $table->foreignId('collector_type_id')->constrained('collector_types')->comment('STAFF, OWNER');
            $table->date('started_date');
            $table->date('ended_date');
            $table->decimal('commission_percentage', 5, 2);
            $table->decimal('tax_percentage', 5, 2);
                        
            // Nilai Uang
            $table->decimal('premium_gross', 15, 2)->comment('Tagihan ke Klien');
            $table->decimal('discount', 15, 2);
            $table->decimal('admin_fee', 15, 2);
            $table->decimal('commission_pct', 5, 2);
            $table->decimal('commission_amount', 15, 2);
            $table->decimal('net_to_insurance', 15, 2)->comment('Wajib setor ke Principal');

            $table->decimal('premi_amount', 15, 2)->comment('Premi Produksi');
            $table->decimal('polis_amount', 15, 2)->comment('Premi Biaya Polis');
            $table->decimal('client_total', 15, 2)->comment('Premi Total');

            $table->decimal('commission_amount_initial', 15, 2)->comment('Komisi Awal');
            $table->decimal('tax_amount', 15, 2)->comment('Komisi Pajak');
            $table->decimal('discount_amount', 15, 2)->comment('Komisi Diskon');
            $table->decimal('commission_total', 15, 2)->comment('Komisi Total');

            $table->text('note')->nullable();

            // LOGIC 1: CLIENT PAYMENT (TIMER START)
            $table->foreignId('client_payment_status_id')->constrained('payment_statuses');
            $table->dateTime('client_paid_at')->nullable()->comment('START TIMER 24 JAM');
            $table->string('client_evident_file')->nullable()->comment('Bukti Transfer Nasabah');
            $table->string('client_evident_number')->nullable()->comment('Nomer Bukti Transfer Nasabah');

            // LOGIC 2: INSURANCE PAYMENT (TIMER STOP)
            $table->foreignId('insurance_payment_status_id')->constrained('payment_statuses');
            $table->dateTime('insurance_paid_at')->nullable()->comment('STOP TIMER 24 JAM');
            $table->string('insurance_evident_file')->nullable()->comment('Bukti Transfer Asurasi');
            $table->string('insurance_evident_number')->nullable()->comment('Nomer Bukti Transfer Asurasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
