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
        Schema::create('mutasis', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('wbp_id');

            $table->string('nama');
            $table->string('negara')->nullable();
            $table->string('agama')->nullable();
            $table->string('jenis_kejahatan')->nullable();
            $table->text('foto_wbp')->nullable();

            $table->unsignedBigInteger('kamar_asal_id');
            $table->string('kamar_asal_nama');

            $table->unsignedBigInteger('kamar_tujuan_id')->nullable();
            $table->string('kamar_tujuan_nama')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};
