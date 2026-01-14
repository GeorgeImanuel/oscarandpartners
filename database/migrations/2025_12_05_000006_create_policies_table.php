<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_number');
            $table->foreignId('object_id')->constrained('objects');
            $table->foreignId('coverage_id')->constrained('coverages');
            $table->decimal('coverage_percentage', 8, 7);
            $table->date('started_date');
            $table->date('ended_date');
            $table->timestamps();
        });

        Schema::create('policy_insurances', function (Blueprint $table) {
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->foreignId('insurance_id')->constrained('insurances');
            $table->boolean('is_main');
            $table->decimal('percentage', 8, 7);
            $table->primary(['policy_id', 'insurance_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('policy_insurances');
        Schema::dropIfExists('policies');
    }
};