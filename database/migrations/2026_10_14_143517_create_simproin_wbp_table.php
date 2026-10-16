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
        Schema::create('simproin_wbp', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wbp_id')
                ->unique()
                ->constrained('wbps')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('kode_akses', 20)->nullable()->unique();
            $table->enum('register_f', ['Ya', 'Tidak'])->default('Tidak');

            $table->enum('jenis_usulan', [
                'Asimilasi',
                'Cuti Bersyarat',
                'Cuti Mengunjungi Keluarga',
                'Cuti Menjelang Bebas',
                'Pembebasan Bersyarat',
                'Belum Usul',
            ])->default('Belum Usul')->nullable();

            $table->date('tgl_pengambilan_berkas')->nullable();
            $table->date('tgl_pengembalian_berkas')->nullable();
            $table->date('tgl_sidang_tpp')->nullable();
            $table->date('tgl_permintaan_litmas')->nullable();
            $table->date('tgl_litmas_bapas')->nullable();
            $table->date('tgl_hasil_litmas')->nullable();
            $table->date('tgl_input_sdp')->nullable();
            $table->date('tgl_verifikasi_ditjen')->nullable();
            $table->date('tgl_persetujuan_menteri')->nullable();
            $table->date('tgl_otorisasi_sk')->nullable();
            $table->date('tgl_pelaksanaan')->nullable();
            $table->date('tgl_pembebasan')->nullable();

            $table->string('status_bebas', 50)->default('Belum Bebas');

            $table->string('nama_penjamin')->nullable();
            $table->string('hubungan_penjamin', 100)->nullable();
            $table->string('no_hp_penjamin', 20)->nullable();
            $table->text('alamat_penjamin')->nullable();

            $table->enum('status_aktif', ['Aktif', 'Tidak Aktif', 'Batal',])->default('Tidak Aktif');
            $table->text('alasan_batal')->nullable();
            $table->timestamp('tgl_batal')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simproin_wbp');
    }
};
