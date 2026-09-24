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
        Schema::create('climbing_prep_content', function (Blueprint $table) {
            $table->id();

            $table->string('hero_tag')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_lede')->nullable();
            $table->string('hero_image')->nullable();

            $table->string('fitness_title')->nullable();
            $table->json('fitness_paragraphs')->nullable();

            $table->string('altitude_title')->nullable();
            $table->json('altitude_paragraphs')->nullable();

            $table->string('tips_title')->nullable();
            $table->json('tips')->nullable();

            $table->json('faqs')->nullable();

            $table->string('cta_title')->nullable();
            $table->text('cta_text')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('climbing_prep_content');
    }
};
