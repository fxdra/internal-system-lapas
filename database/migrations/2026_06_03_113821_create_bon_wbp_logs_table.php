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
        Schema::create('bon_wbp_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bon_wbp_id')
                ->constrained('bon_wbps')
                ->cascadeOnDelete();
                
            $table->foreignId('admin_id')->nullable()->constrained('admins');
        
            $table->enum('aksi', [
                'create',
                'approve',
                'tracking',
                'selesai',
                'batal'
            ]);
        
            $table->text('keterangan')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_wbp_logs');
    }
};
