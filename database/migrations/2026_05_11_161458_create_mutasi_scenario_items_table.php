<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_scenario_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scenario_id');
            $table->unsignedBigInteger('wbp_id');
        
            $table->unsignedBigInteger('kamar_asal_id');
            $table->unsignedBigInteger('kamar_tujuan_id');
        
            $table->timestamps();
        
            $table->unique(['scenario_id', 'wbp_id']); // 🔥 INI KUNCI CART SYSTEM
                });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_scenario_items');
    }
};