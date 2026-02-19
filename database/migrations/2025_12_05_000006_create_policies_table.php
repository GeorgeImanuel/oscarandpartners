<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_number');
            $table->string('currency_code')->nullable();
            $table->foreign('currency_code')->references('code')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
            $table->date('started_date');
            $table->date('ended_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('policies');
    }
};