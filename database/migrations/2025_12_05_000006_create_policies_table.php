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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
<<<<<<< Updated upstream
            $table->string('policy_number')->unique()->comment('No Polis Fisik');
            $table->foreignId('object_id')->constrained('objects')->onDelete('cascade')->comment('Link ke Objek Asuransi');
            $table->string('coverage_code');
            $table->foreign('coverage_code')->references('code')->on('coverages')->onDelete('cascade')->comment('Link ke Coverage');
            $table->decimal('coverage_percentage', 3, 2)->comment('Persentase coverage');
            $table->date('started_date')->comment('Periode mulai polis');
            $table->date('ended_date')->comment('Periode berakhir polis');
=======
            $table->string('policy_number');
            $table->foreignId('object_id')->constrained('objects');
            $table->foreignId('coverage_id')->constrained('coverages');
            $table->string('currency_code')->nullable();
            $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('set null');
            $table->decimal('coverage_percentage', 8, 7);
            $table->date('started_date');
            $table->date('ended_date');
>>>>>>> Stashed changes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
