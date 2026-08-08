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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id(); // nanti kita pakai id=1
            $table->longText('sejarah_singkat')->nullable();
            $table->longText('struktur_organisasi')->nullable();
            $table->longText('visi_misi')->nullable();
            $table->longText('tugas_fungsi')->nullable();
            $table->longText('lokasi_kami')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
