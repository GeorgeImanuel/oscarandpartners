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
                        
            $table->text('note')->nullable();

            // LOGIC 1: CLIENT PAYMENT (TIMER START)
            $table->foreignId('client_payment_status_id')->constrained('payment_statuses');
            $table->dateTime('client_paid_at')->nullable()->comment('START TIMER 24 JAM');
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
