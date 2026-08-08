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
        Schema::create('pengunjung_perpustakaans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('buku_id')
                ->constrained('buku')
                ->cascadeOnDelete();

            $table->string('nama_peminjam');

            $table->string('kamar_sel')->nullable();

            $table->date('tanggal_pinjam');

            $table->date('tanggal_kembali')->nullable();

            // STATUS HANYA 2
            $table->enum('status', ['PEMINJAMAN', 'PENGEMBALIAN'])
                ->default('PEMINJAMAN');

            $table->string('foto_buku')->nullable();

            // BARCODE
            $table->string('id_barcode')->unique();
            $table->string('img_barcode')->nullable();
            $table->boolean('status_barcode')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengunjung_perpustakaans');
    }
};
