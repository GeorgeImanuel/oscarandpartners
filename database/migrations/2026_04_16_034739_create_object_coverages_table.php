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
        Schema::create('object_coverages', function (Blueprint $table) {
            $table->foreignId('object_id')->constrained('objects')->onUpdate('cascade')->onDelete('cascade');
            $table->string('coverage_code');
            $table->foreign('coverage_code')->references('code')->on('coverages')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('percentage', 8, 7);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('object_coverages');
    }
};
