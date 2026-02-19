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
        Schema::create('policy_insurances', function (Blueprint $table) {
            $table->foreignId('policy_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('insurance_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->boolean('is_main')->default(true);
            $table->decimal('percentage', 8, 7)->default(0)->comment('Share percentage dalam desimal (0.60 = 60%)');
            $table->decimal('tax', 15, 2)->default(0)->comment('Pajak per asuransi');
            $table->timestamps();

            $table->primary(['policy_id', 'insurance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_insurances');
    }
};
