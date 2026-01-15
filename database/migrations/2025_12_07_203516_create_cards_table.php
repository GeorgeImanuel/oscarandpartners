<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('policy_id')->constrained('policies');
            $table->foreignId('card_type_id')->constrained('card_types');
            $table->foreignId('card_status_id')->constrained('card_statuses');
            $table->foreignId('parent_card_id')->nullable()->constrained('cards')->onDelete('set null');
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->foreignId('created_by')->constrained('users');
            
            $table->bigInteger('object_value')->comment('Harga Asset dari object_component');
            $table->double('premi_percentages');
            $table->double('tax_percentages');
            $table->double('commision_percentages');
            $table->double('policy_fee');
            $table->integer('discount');
            
            $table->bigInteger('production_value');
            $table->bigInteger('premi_value');
            $table->bigInteger('commision_bruto');
            $table->bigInteger('commision_netto');
            $table->double('tax');
            $table->integer('disc_agent');
            $table->bigInteger('insurance_pay');
            $table->bigInteger('client_pay');
            
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cards');
    }
};