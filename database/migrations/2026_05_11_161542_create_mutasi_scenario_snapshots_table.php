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
        Schema::create('mutasi_scenario_snapshots', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('scenario_id');
            $table->unsignedBigInteger('kamar_id');
        
            $table->integer('sebelum')->default(0);
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);
            $table->integer('sesudah')->default(0);
        
            $table->timestamps();
        
            $table->unique(['scenario_id', 'kamar_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_scenario_snapshots');
    }
};
