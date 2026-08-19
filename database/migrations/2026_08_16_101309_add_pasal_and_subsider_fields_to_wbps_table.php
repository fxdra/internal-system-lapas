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
        Schema::table('wbps', function (Blueprint $table) {

            // =========================
            // PASAL
            // =========================
            $table->string('pasal', 255)
                ->nullable()
                ->after('jenis_kejahatan');

            // =========================
            // SUBSIDER PIDANA
            // =========================
            $table->unsignedInteger('subsider_tahun')
                ->nullable()
                ->after('subsider_bulan');

            $table->unsignedInteger('subsider_hari')
                ->nullable()
                ->after('subsider_tahun');

            // =========================
            // UANG PENGGANTI (UP)
            // Khusus perkara korupsi
            // =========================
            $table->unsignedInteger('subsider_tahun_up')
                ->nullable()
                ->after('denda_subsider');

            $table->unsignedInteger('subsider_bulan_up')
                ->nullable()
                ->after('subsider_tahun_up');

            $table->unsignedInteger('subsider_hari_up')
                ->nullable()
                ->after('subsider_bulan_up');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wbps', function (Blueprint $table) {

            $table->dropColumn([
                'pasal',
                'subsider_tahun',
                'subsider_hari',
                'subsider_tahun_up',
                'subsider_bulan_up',
                'subsider_hari_up',
            ]);
        });
    }
};
