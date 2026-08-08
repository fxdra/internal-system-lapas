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
        Schema::create('pengikuts', function (Blueprint $table) {

            $table->id();

         $table->foreignId('pengunjung_id')
                ->nullable()
                ->constrained('pengunjungs') // otomatis references id on pengunjungs
                ->onDelete('cascade');

      // kalau pengunjung dihapus, pengikut ikut terhapus
            $table->string('tipe_identitas', 20);
            $table->string('nik_pengunjung', 20)->index();

            $table->string('nama_pengunjung');
            $table->enum('jk_pengunjung', ['Laki-laki', 'Perempuan']);

            $table->text('alamat_pengunjung');
            $table->string('no_wa', 15);

            $table->string('hubungan_pengunjung');
            $table->unsignedInteger('jumlah_anak_pengunjung')->default(0);

            $table->date('tanggal_kunjungan');

            $table->string('ktp');
            $table->string('selfie');

            $table->enum('titip_barang', ['ya', 'tidak'])->default('tidak');
            $table->string('antrian')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengikuts');
    }
};
