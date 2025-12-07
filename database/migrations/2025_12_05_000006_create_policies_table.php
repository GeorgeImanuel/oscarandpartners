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
            $table->foreignId('insurance_id')->constrained('insurances')->onDelete('cascade');
            $table->foreignId('parent_policy_id')->nullable()->constrained('policies')->onDelete('set null')->comment('RENEWAL LINK (Self-Join)');
            $table->foreignId('object_type_id')->constrained('object_types');
            $table->string('policy_number')->comment('No Polis Fisik');
            $table->text('object')->comment('Nama Objek');
            $table->text('object_address')->comment('Alamat Objek');
            $table->decimal('object_value', 15, 2)->comment('Nilai Objek');
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
