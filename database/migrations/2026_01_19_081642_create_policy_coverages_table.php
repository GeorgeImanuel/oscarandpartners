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
        Schema::create('policy_coverages', function (Blueprint $table) {
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->string('coverage_code');
            $table->foreign('coverage_code')->references('code')->on('coverages')->onDelete('cascade');
            $table->decimal('percentage', 8, 7);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_coverages');
    }
};
