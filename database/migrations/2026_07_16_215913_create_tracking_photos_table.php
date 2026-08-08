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
        Schema::create('tracking_photos', function (Blueprint $table) {

        $table->id();

        $table->unsignedBigInteger('user_id')
              ->nullable();

        $table->string('image');

        $table->decimal(
            'latitude',
            10,
            8
        );

        $table->decimal(
            'longitude',
            11,
            8
        );

        $table->float(
            'accuracy'
        );

        $table->bigInteger(
            'timestamp'
        );

        $table->timestamps();

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_photos');
    }
};
