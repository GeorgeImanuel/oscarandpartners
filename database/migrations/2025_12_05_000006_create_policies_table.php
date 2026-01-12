


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
            $table->string('policy_number')->unique()->comment('No Polis Fisik');
            $table->foreignId('object_id')->constrained('objects')->onDelete('cascade')->comment('Link ke Objek Asuransi');
            $table->string('coverage_code');
            $table->foreign('coverage_code')->references('code')->on('coverages')->onDelete('cascade')->comment('Link ke Coverage');
            $table->decimal('coverage_percentage', 8, 7)->comment('Persentase coverage');
            $table->date('started_date')->comment('Periode mulai polis');
            $table->date('ended_date')->comment('Periode berakhir polis');
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
