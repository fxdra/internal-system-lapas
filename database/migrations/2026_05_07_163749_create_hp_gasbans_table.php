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
        Schema::create('hp_gasbans', function (Blueprint $table) {

            $table->id();

            // barcode
            $table->string('barcode_id')->unique();
            $table->text('img_barcode')->nullable();

            // header
            $table->string('logo')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();

            // petugas
            $table->string('foto_petugas')->nullable();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('jabatan')->nullable();

            // handphone
            $table->string('foto_handphone')->nullable();
            $table->string('jenis_hp')->nullable();
            $table->string('warna_hp')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hp_gasbans');
    }
};
