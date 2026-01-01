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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->foreignId('card_status_id')->constrained('card_statuses')->onDelete('cascade');
            $table->foreignId('parent_card_id')->nullable()->constrained('cards')->onDelete('cascade')->comment('RENEWAL LINK (Self-Join)');
            $table->foreignId('payment_type_id')->constrained('payment_types')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->comment('Staff Inputter');

            // Parameter Input
            $table->decimal('premi_percentages', 5, 2)->comment('Tarif Premi');
            $table->decimal('tax_percentages', 5, 2)->comment('Pajak');
            $table->decimal('commision_percentages', 5, 2)->comment('Komisi Agen');
            $table->decimal('object_value', 15, 2)->comment('Harga Asset dari object_component');
            $table->decimal('discount', 15, 2)->comment('Diskon');

            // Value (calculated)
            $table->decimal('production_value', 15, 2)->comment('Nilai Produksi');
            $table->decimal('premi', 15, 2)->comment('Total Premi');
            $table->decimal('commision_bruto', 15, 2)->comment('Komisi Awal');
            $table->decimal('commision_netto', 15, 2)->comment('Komisi Bersih');
            $table->decimal('tax', 15, 2)->comment('Nilai Pajak');
            $table->decimal('insurance_pay', 15, 2)->comment('Bayar Asuransi');

            // INSURANCE PAYMENT (TIMER STOP)
            $table->foreignId('insurance_payment_status_id')->default(1)->constrained('payment_statuses')->onDelete('cascade');
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
        Schema::dropIfExists('cards');
    }
};
