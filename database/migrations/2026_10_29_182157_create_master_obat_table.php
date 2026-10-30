<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_obat', function (Blueprint $table) {
            $table->id();

            $table->string('nama_produk', 500);
            $table->string('nie', 100)->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('masa_berlaku')->nullable();
            $table->string('bentuk_sediaan', 255)->nullable();
            $table->text('kemasan')->nullable();
            $table->text('komposisi')->nullable();
            $table->string('pendaftar', 500)->nullable();
            $table->string('nama_aplikasi', 255)->nullable();
            $table->string('diterbitkan_oleh', 255)->nullable();

            $table->timestamps();

            $table->index('nama_produk');
            $table->index('bentuk_sediaan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_obat');
    }
};
