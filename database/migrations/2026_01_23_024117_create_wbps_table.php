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
        Schema::create('wbps', function (Blueprint $table) {
             $table->id();

            // ================= IDENTITAS =================
            $table->string('no_reg_instansi', 50)->index();
            $table->string('nama', 100);
            
            // ================= DATA PRIBADI =================
            $table->string('negara', 50)->nullable();
            $table->string('agama', 30)->nullable();
            
            // ================= KASUS =================
            $table->string('jenis_kejahatan', 100)->nullable();
            $table->string('putusan', 255)->nullable();
            $table->date('ekspirasi')->nullable();
            
            // ================= LOKASI =================
            $table->string('lokasi_blok', 50)->nullable();
            $table->string('lokasi_sel', 50)->nullable();
            
            // ================= STATUS =================
            $table->enum('status_kamar', [
                'Terbuka',
                'Tertutup'
            ])->default('Terbuka');
            
            $table->enum('status_wbp', [
                'AKTIF',
                'PINDAH UPT',
                'BON',
                'SAKIT',
                'PULANG'
            ])->default('AKTIF');
            
            // ================= MASA PIDANA =================
            $table->date('masa_1_3')->nullable();
            $table->date('masa_1_2')->nullable();
            $table->date('masa_2_3')->nullable();
            
            // ================= REMISI =================
            $table->integer('total_bulan_remisi')->nullable();
            $table->integer('total_hari_remisi')->nullable();
            
            // ================= TAMBAHAN =================
            $table->string('keperluan', 255)->nullable();
            $table->date('tanggal_bon')->nullable();
            $table->string('foto_wbp', 255)->nullable();
            
            // ================= RELASI =================
            $table->foreignId('kamar_id')
                  ->nullable()
                  ->constrained('kamars')
                  ->nullOnDelete();
            
            // ================= TIMESTAMP =================
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wbps');
    }
};
