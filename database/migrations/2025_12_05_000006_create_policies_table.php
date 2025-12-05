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
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('insurance_id')->constrained('insurance_companies')->onDelete('cascade');
            $table->foreignId('previous_policy_id')->nullable()->constrained('policies')->onDelete('set null')->comment('RENEWAL LINK (Self-Join)');
            $table->string('reg_number')->unique()->comment('Auto-Gen (e.g. REG/2025/001)');
            $table->string('policy_number')->comment('No Polis Fisik');
            $table->date('period_start');
            $table->date('period_end')->comment('Trigger Alert Jatuh Tempo');
            $table->enum('status', ['ACTIVE', 'EXPIRED', 'CANCELLED'])->default('ACTIVE');
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade')->comment('Staff Inputter');
            $table->timestamps();
            
            $table->index('client_id');
            $table->index('insurance_id');
            $table->index('reg_number');
            $table->index('policy_number');
            $table->index('status');
            $table->index('period_end');
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
