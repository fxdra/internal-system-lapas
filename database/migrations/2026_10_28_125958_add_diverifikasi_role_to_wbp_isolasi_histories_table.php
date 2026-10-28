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
        Schema::table('wbp_isolasi_histories', function (Blueprint $table) {
            $table->string('diverifikasi_role', 50)->nullable()->after('diverifikasi_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('wbp_isolasi_histories', function (Blueprint $table) {
            $table->dropColumn('diverifikasi_role');
        });
    }
};
