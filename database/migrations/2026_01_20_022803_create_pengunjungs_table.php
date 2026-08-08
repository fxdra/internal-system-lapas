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
        Schema::create('pengunjungs', function (Blueprint $table) {
        $table->id();

         // Foreign key
        $table->string('no_reg_instansi', 50)->nullable();

        $table->string('jenis_identitas', 50);
        $table->string('nik_pengunjung', 50)->nullable();
        $table->string('nama_wbp', 100);
        $table->string('foto_ktp')->nullable();
        $table->string('foto_selfie')->nullable();
        $table->string('nama_pengunjung', 100);
        $table->string('jenis_kelamin', 10)->nullable();
        $table->string('alamat', 255)->nullable();
        $table->string('no_wa', 20)->nullable();
        $table->string('hubungan', 50)->nullable();
        $table->integer('jumlah_anak')->nullable();
        $table->date('tanggal_kunjungan')->nullable();

        // Barang Titipan
        $table->string('titip_barang')->nullable();
        $table->string('foto_barang')->nullable();
        $table->string('jenis_barang')->nullable();

        // Barcode
        $table->string('id_barcode', 100)->nullable();
        $table->string('img_barcode', 255)->nullable();
        $table->string('status_barcode', 50)->default('pending');
        $table->timestamps();


    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengunjungs');
    }
};
