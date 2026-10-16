
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wbp_pembinaan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wbp_id')
                ->constrained('wbps')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('program_pembinaan_id')
                ->constrained('program_pembinaan')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            // Indikator keaktifan setiap WBP dalam program
            $table->enum('indikator_keaktifan', [
                'Sangat Aktif',
                'Aktif',
                'Kurang Aktif',
            ])->default('Aktif');

            // Status keikutsertaan berbeda dari indikator keaktifan
            $table->enum('status_keikutsertaan', [
                'Aktif',
                'Selesai',
            ])->default('Aktif');

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->index(
                ['program_pembinaan_id', 'status_keikutsertaan'],
                'wbp_pembinaan_program_status_index'
            );

            $table->index(
                ['wbp_id', 'status_keikutsertaan'],
                'wbp_pembinaan_wbp_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wbp_pembinaan');
    }
};
