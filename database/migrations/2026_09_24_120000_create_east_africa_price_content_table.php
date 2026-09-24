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
        Schema::create('east_africa_price_content', function (Blueprint $table) {
            $table->id();

            $table->string('hero_title')->nullable();
            $table->text('hero_lede')->nullable();
            $table->string('hero_image')->nullable();
            $table->text('intro')->nullable();

            $table->json('routes')->nullable();

            $table->json('included')->nullable();
            $table->json('excluded')->nullable();

            $table->string('why_title')->nullable();
            $table->text('why_intro')->nullable();
            $table->json('why_points')->nullable();

            $table->string('citizens_title')->nullable();
            $table->json('citizens_paragraphs')->nullable();
            $table->json('reasons')->nullable();

            $table->string('cta_title')->nullable();
            $table->text('cta_text')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('east_africa_price_content');
    }
};
