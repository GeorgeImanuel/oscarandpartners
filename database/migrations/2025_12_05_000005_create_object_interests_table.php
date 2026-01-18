<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('object_interests', function (Blueprint $table) {
            // FK ke tabel objects
            $table->foreignId('object_id')
                  ->constrained('objects')
                  ->onDelete('cascade');

            // FK ke tabel interests
            $table->foreignId('interest_id')
                  ->constrained('interests')
                  ->onDelete('restrict');

            // Nilai dari komponen objek (decimal 20,2)
            $table->decimal('value', 20, 2);

            // Composite Primary Key
            $table->primary(['object_id', 'interest_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('object_interests');
    }
};