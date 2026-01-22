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
        // 1. Hapus kolom object_id lama di tabel policies (jika ada)
        Schema::table('policies', function (Blueprint $table) {
            if (Schema::hasColumn('policies', 'object_id')) {
                $table->dropForeign(['object_id']);
                $table->dropColumn('object_id');
            }
        });

        // 2. Buat tabel pivot dengan nama 'policy_objects'
        Schema::create('policy_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained()->onDelete('cascade');
            
            // Pastikan 'objects' adalah nama tabel dari model ObjectModel kamu
            $table->foreignId('object_id')->constrained('objects')->onDelete('cascade'); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('policy_objects');
    }
};
