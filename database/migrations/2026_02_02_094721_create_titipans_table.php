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
        Schema::create('titipans', function (Blueprint $table) {
            
            $table->id();

            $table->string('sesi_kunjungan')->nullable();

            // relasi ke WBP
            $table->unsignedBigInteger('wbp_id')->nullable();
            $table->string('no_reg_instansi')->nullable();

            // identitas
            $table->string('jenis_identitas');
            $table->string('foto_ktp');
            $table->string('foto_selfie');

            // data pengunjung
            $table->string('nik_pengunjung', 20);
            $table->string('nama_pengunjung');
            $table->string('nama_wbp');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat_pengunjung');

            $table->string('no_wa', 20);
            $table->string('hubungan_pengunjung');

            $table->unsignedInteger('jumlah_anak_pengunjung')->default(0);
            $table->date('tanggal_kunjungan');

            $table->enum('titip_barang', ['ya', 'tidak'])->default('tidak');
            
            // Barcode
            $table->string('id_barcode', 100)->nullable();
            $table->string('img_barcode', 255)->nullable();
            $table->string('status_barcode', 50)->default('pending');

            $table->timestamps();

            // FK ke wbps
            $table->foreign('wbp_id')
                ->references('id')
                ->on('wbps')
                ->nullOnDelete();
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titipans');
    }
};
