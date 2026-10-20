<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_kode_akses', function (Blueprint $table) {
            $table->unique('simproin_wbp_id');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_kode_akses', function (Blueprint $table) {
            $table->dropUnique(
                'riwayat_kode_akses_simproin_wbp_id_unique'
            );
        });
    }
};
