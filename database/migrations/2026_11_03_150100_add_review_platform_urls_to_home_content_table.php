<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_content', function (Blueprint $table) {
            $table->string('tripadvisor_url')->nullable();
            $table->string('google_reviews_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('home_content', function (Blueprint $table) {
            $table->dropColumn(['tripadvisor_url', 'google_reviews_url']);
        });
    }
};
