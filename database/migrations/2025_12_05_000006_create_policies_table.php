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
            $table->string('policy_number');
            $table->foreignId('object_id')->constrained('objects');
            $table->foreignId('coverage_id')->constrained('coverages');
            $table->string('currency_code')->nullable();
            $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('set null');
            $table->decimal('coverage_percentage', 8, 7);
            $table->date('started_date');
            $table->date('ended_date');
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
