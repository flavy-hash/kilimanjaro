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
        Schema::table('home_content', function (Blueprint $table) {
            $table->string('hero_video')->nullable();
            $table->string('hero_region')->nullable();
            $table->string('hero_headline')->nullable();
            $table->string('hero_subtitle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_content', function (Blueprint $table) {
            $table->dropColumn(['hero_video', 'hero_region', 'hero_headline', 'hero_subtitle']);
        });
    }
};
