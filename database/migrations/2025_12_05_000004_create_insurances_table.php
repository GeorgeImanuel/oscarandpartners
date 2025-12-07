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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Principal (e.g. Sinar Mas)');
            $table->string('bank_account')->nullable();
            $table->string('va_number')->nullable();
            $table->decimal('default_commission_pct', 5, 2)->default(0)->comment('Default commission percentage');
            $table->timestamps();
            
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
