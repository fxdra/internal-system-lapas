
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_pembinaan', function (Blueprint $table) {
            $table->id();

            $table->enum('kategori', [
                'Kepribadian',
                'Kemandirian',
            ]);

            $table->string('nama_program', 100);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['kategori', 'nama_program'],
                'program_pembinaan_kategori_nama_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_pembinaan');
    }
};
