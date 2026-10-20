<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kode_akses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('simproin_wbp_id')
                ->constrained('simproin_wbp')
                ->cascadeOnDelete();

            $table->char('kode_akses_hash', 64)->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kode_akses');
    }
};
