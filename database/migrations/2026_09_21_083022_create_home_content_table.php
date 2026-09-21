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
        Schema::create('home_content', function (Blueprint $table) {
            $table->id();

            $table->json('hero_stats')->nullable();

            $table->string('intro_tag')->nullable();
            $table->string('intro_title')->nullable();
            $table->json('intro_paragraphs')->nullable();

            $table->string('pillars_tag')->nullable();
            $table->string('pillars_title')->nullable();
            $table->text('pillars_lede')->nullable();

            $table->string('routes_tag')->nullable();
            $table->string('routes_title')->nullable();
            $table->text('routes_lede')->nullable();

            $table->string('equip_title')->nullable();
            $table->text('equip_description')->nullable();

            $table->string('trips_tag')->nullable();
            $table->string('trips_title')->nullable();
            $table->text('trips_lede')->nullable();

            $table->string('activities_tag')->nullable();
            $table->string('activities_title')->nullable();
            $table->text('activities_lede')->nullable();
            $table->json('activities')->nullable();

            $table->string('about_tag')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_lede')->nullable();
            $table->string('about_story_title')->nullable();
            $table->text('about_story_text')->nullable();
            $table->string('about_team_title')->nullable();
            $table->text('about_team_text')->nullable();
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
        Schema::dropIfExists('home_content');
    }
};
