<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kamar')->unique(); // gabungan blok+sel
            $table->string('lokasi_blok');
            $table->string('lokasi_sel');
            $table->string('nama_kamar')->nullable();
            $table->string('barcode_id')->unique()->nullable(); // kode unik untuk scan
            $table->string('img_barcode')->nullable();         // path atau nama file barcode
            $table->timestamps();
        });

        // Tambah kolom kamar_id di tabel wbp
        Schema::table('wbps', function (Blueprint $table) {
            $table->foreignId('kamar_id')->nullable()->constrained('kamars')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('wbps', function (Blueprint $table) {
            $table->dropForeign(['kamar_id']);
            $table->dropColumn('kamar_id');
        });

        Schema::dropIfExists('kamars');
    }
};
