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
        Schema::table('policies', function (Blueprint $table) {
            $table->dropForeign(['coverage_id']);
            $table->dropColumn(['coverage_id', 'coverage_percentage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->foreignId('coverage_id')->nullable()->constrained();
            $table->decimal('coverage_percentage', 8, 7)->nullable();
        });
    }
};
