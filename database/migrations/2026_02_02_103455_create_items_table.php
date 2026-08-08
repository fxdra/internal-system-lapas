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
        Schema::create('items', function (Blueprint $table) {
            
            $table->id();

            $table->unsignedBigInteger('titip_barang_id');

            $table->enum('barang_dari', ['keluarga', 'passmart']);
            $table->string('foto_barang');
            $table->string('jenis_barang');
            $table->unsignedInteger('jumlah_barang')->default(1);

            $table->timestamps();

            $table->foreign('titip_barang_id')
                ->references('id')
                ->on('titipans')
                ->cascadeOnDelete();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
