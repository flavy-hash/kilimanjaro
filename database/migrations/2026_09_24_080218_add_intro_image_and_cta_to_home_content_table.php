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
            $table->string('intro_image')->nullable();
            $table->string('intro_cta_label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_content', function (Blueprint $table) {
            $table->dropColumn(['intro_image', 'intro_cta_label']);
        });
    }
};
