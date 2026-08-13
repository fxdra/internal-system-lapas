<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wbps', function (Blueprint $table) {
            $table->unsignedBigInteger('denda_subsider')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('wbps', function (Blueprint $table) {
            $table->decimal('denda_subsider', 15, 2)
                ->nullable()
                ->change();
        });
    }
};
