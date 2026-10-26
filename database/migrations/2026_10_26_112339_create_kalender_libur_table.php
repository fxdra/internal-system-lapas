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
        Schema::create('kalender_libur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->string('nama', 150);
            $table->enum('jenis', [
                'Libur Nasional',
                'Cuti Bersama',
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kalender_libur');
    }
};
