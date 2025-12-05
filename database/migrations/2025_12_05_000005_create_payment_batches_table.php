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
        Schema::create('payment_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade')->comment('Finance yang buat Request');
            $table->decimal('total_amount_netto', 15, 2)->default(0);
            $table->enum('status', ['PENDING', 'PAID'])->default('PENDING');
            $table->string('proof_file')->nullable()->comment('Bukti Transfer Owner');
            $table->timestamp('paid_at')->nullable()->comment('Waktu Owner Bayar');
            $table->timestamps();
            
            $table->index('status');
            $table->index('created_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_batches');
    }
};
