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
            $table->foreignId('card_id')->constrained('cards');
            $table->integer('sequence')->comment('Urutan Pembayaran');
            $table->foreignId('created_by')->constrained('users')->comment('Finance yg buat Request');
            $table->decimal('amount', 15, 2);
            
            $table->text('note')->nullable();

            $table->foreignId('client_payment_status_id')->constrained('payment_statuses');
            $table->dateTime('deadline_at')->nullable()->comment('START TIMER 24 JAM');
            $table->dateTime('paid_at')->nullable();
            $table->string('client_evident_file')->nullable()->comment('Bukti Transfer Nasabah');
            $table->string('client_evident_number')->nullable()->comment('Nomer Bukti Transfer Nasabah');

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
