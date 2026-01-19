<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coverages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('PAR, EQ, CIS');
            $table->string('name')->comment('Property All Risk, Machinery Breakdown');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('coverages'); }
};