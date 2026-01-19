<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('relations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Unique (e.g. REL-001)');
            $table->string('name')->comment('Nama Grup/Keluarga');
            $table->timestamps();
            
            $table->index('code');
        });
    }
    public function down(): void { Schema::dropIfExists('relations'); }
};