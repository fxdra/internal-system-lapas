<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wbps', function (Blueprint $table) {
            $table->unsignedInteger('subsider_bulan')
                ->nullable()
                ->after('putusan_bulan');

            $table->unsignedBigInteger('denda_subsider')
                ->nullable()
                ->after('subsider_bulan');
        });
    }

    public function down(): void
    {
        Schema::table('wbps', function (Blueprint $table) {
            $table->dropColumn([
                'subsider_bulan',
                'denda_subsider',
            ]);
        });
    }
};
