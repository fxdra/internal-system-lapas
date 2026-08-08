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
        Schema::create('bon_wbps', function (Blueprint $table) {
            
            $table->id();

            // ================= ADMIN RELATIONS =================
            $table->foreignId('dibuat_oleh')->constrained('admins');

            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('admins');

            $table->foreignId('ditutup_oleh')
                ->nullable()
                ->constrained('admins');

            // ================= BON CORE =================
            $table->string('nomor_bon')->unique();

            $table->foreignId('wbp_id')
                ->constrained('wbps')
                ->cascadeOnDelete();

            $table->foreignId('kamar_asal_id')
                ->nullable()
                ->constrained('kamars');

            $table->enum('unit_asal', [
                'registrasi',
                'bimpas',
                'kplp',
                'bimker'
            ]);

            $table->text('keperluan');

            $table->dateTime('jam_keluar')->nullable();
            $table->dateTime('jam_kembali')->nullable();

            $table->enum('status', [
                'menunggu',
                'aktif',
                'selesai',
                'batal'
            ])->default('menunggu');

            // ================= TIMESTAMP APPROVAL =================
            $table->dateTime('disetujui_at')->nullable();

            $table->dateTime('ditutup_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_wbps');
    }
};
