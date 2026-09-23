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
        Schema::create('about_content', function (Blueprint $table) {
            $table->id();

            $table->string('about_hero_tag')->nullable();
            $table->string('about_hero_title')->nullable();
            $table->text('about_hero_lede')->nullable();
            $table->string('about_hero_image')->nullable();

            $table->string('story_title')->nullable();
            $table->json('story_paragraphs')->nullable();

            $table->json('stats')->nullable();
            $table->json('values')->nullable();

            $table->string('team_hero_tag')->nullable();
            $table->string('team_hero_title')->nullable();
            $table->text('team_hero_lede')->nullable();
            $table->json('team_members')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_content');
    }
};
