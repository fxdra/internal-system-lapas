<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // ================== OPEN GRAPH ==================
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            $table->string('og_url')->nullable();

            // ================== TWITTER CARD ==================
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_site')->nullable();
            $table->string('twitter_creator')->nullable();
            $table->string('twitter_title')->nullable();
            $table->string('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // drop kolom jika rollback
            $table->dropColumn([
                'og_title',
                'og_description',
                'og_image',
                'og_type',
                'og_url',
                'twitter_card',
                'twitter_site',
                'twitter_creator',
                'twitter_title',
                'twitter_description',
                'twitter_image'
            ]);
        });
    }
};
