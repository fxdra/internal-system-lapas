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
        Schema::create('buku', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')->nullable();

                $table->string('judul');
                $table->string('slug')->unique();

                $table->string('penulis');

                $table->string('publisher')->nullable();

                $table->string('kategori')->nullable();

                $table->string('tahun')->nullable();

                $table->text('sinopsis')->nullable();

                $table->string('lokasi_rak')->nullable();

                $table->integer('stock')->default(0);

                $table->string('status')->default('TERSEDIA');

                $table->boolean('is_active')->default(true);

                $table->string('foto')->nullable();

                $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
