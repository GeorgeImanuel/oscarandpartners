<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('policy_objects', function (Blueprint $table) {
            $table->foreignId('policy_id')->constrained()->onDelete('cascade');
            $table->foreignId('object_id')->constrained('objects')->onDelete('cascade'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('policy_objects');
    }
};
