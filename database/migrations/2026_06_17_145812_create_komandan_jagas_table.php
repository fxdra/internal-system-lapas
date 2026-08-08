<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komandan_jagas', function (Blueprint $table) {
            $table->id();

            $table->string('nama_petugas');
            $table->string('nip')->unique();

            $table->string('pangkat')->nullable();
            $table->string('golongan')->nullable();

            $table->string('jabatan');
            $table->string('regu_jaga');

            $table->string('nomor_hp')->nullable();
            $table->string('email')->nullable();

            $table->string('foto')->nullable();

            $table->enum('status', [
                'Aktif',
                'Cuti',
                'Dinas Luar',
                'Sakit',
                'Pendidikan/Diklat',
                'Mutasi',
                'Pensiun',
                'Nonaktif'
            ])->default('Aktif');

            $table->date('tanggal_mulai_bertugas')->nullable();

            $table->enum('jadwal_jaga', [
                'Pagi',
                'Siang',
                'Malam'
            ])->nullable();

            $table->string('hari_bertugas')->nullable();

            $table->time('jam_masuk')->nullable();
            $table->time('jam_selesai')->nullable();

            $table->string('lokasi_penugasan')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komandan_jaga');
    }
};