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
        Schema::table('cards', function (Blueprint $table) {
            // Tambahkan nullable() agar form tidak error jika dikosongi
            $table->string('agent_asuransi')->nullable()->after('client_pay');
            $table->string('agent_okj')->nullable()->after('agent_asuransi');
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn(['agent_asuransi', 'agent_okj']);
        });
    }
};
