<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('policy_id')->constrained('policies')->onDelete('cascade');
            $table->foreignId('card_type_id')->constrained('card_types');
            $table->foreignId('card_status_id')->constrained('card_statuses');
            $table->foreignId('parent_card_id')->nullable()->constrained('cards')->onDelete('set null');
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->foreignId('created_by')->constrained('users');
            
            $table->bigInteger('object_value')->comment('Harga Asset dari object_component');
            $table->decimal('premi_percentages', 8, 7);
            $table->decimal('commision_percentage', 8, 7);
            $table->decimal('policy_fee', 20, 2);
            $table->decimal('discount_percentage', 8, 7);
            $table->decimal('discount_value', 20, 2);
            
            $table->decimal('production_value', 20, 2);
            $table->decimal('premi_value', 20, 2);
            $table->decimal('commision_bruto', 20, 2);
            $table->decimal('commision_netto', 20, 2);
            $table->decimal('tax', 20, 2);
            $table->decimal('discount_agent', 20, 2);
            $table->decimal('insurance_pay', 20, 2);
            $table->decimal('client_pay', 20, 2);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cards');
    }
};