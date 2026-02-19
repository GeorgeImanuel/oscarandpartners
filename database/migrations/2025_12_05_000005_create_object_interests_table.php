<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('object_interests', function (Blueprint $table) {
            $table->foreignId('object_id')->constrained('objects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('interest_id')->constrained('interests')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('value', 20, 2)->comment('Nilai interest pada object tertentu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('object_interests');
    }
};
