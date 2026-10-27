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
        Schema::create('wbp_isolasi_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wbp_id')
                ->constrained('wbps')
                ->restrictOnDelete();

            $table->foreignId('kamar_id')
                ->constrained('kamars')
                ->restrictOnDelete();

            $table->dateTime('tgl_masuk_isolasi');
            $table->dateTime('tgl_keluar_isolasi')->nullable();

            // Waktu dan petugas yang memverifikasi kelayakan kunjungan.
            $table->dateTime('diverifikasi_pada')->nullable();
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();

            $table->timestamps();

            $table->index(
                ['tgl_keluar_isolasi', 'diverifikasi_pada'],
                'wbp_isolasi_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wbp_isolasi_histories');
    }
};
