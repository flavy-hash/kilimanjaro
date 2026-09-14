<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            // safari | kilimanjaro | zanzibar — drives the site's colour + tab grouping
            $table->string('category');
            // northern | southern — only meaningful for safaris, splits the two admin areas
            $table->string('circuit')->nullable();

            $table->string('name');
            $table->string('nickname')->nullable();
            $table->string('location')->nullable();
            $table->unsignedSmallInteger('days')->default(1);
            $table->unsignedInteger('price')->default(0);
            $table->string('tag')->nullable();
            $table->string('rate')->nullable();
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->unsignedInteger('reviews')->default(0);

            $table->string('group')->nullable();
            $table->string('difficulty')->nullable();
            $table->string('best_time')->nullable();
            $table->string('start_point')->nullable();

            $table->string('image')->nullable();
            $table->string('accommodation')->nullable();
            $table->foreignId('accommodation_id')->nullable()->constrained()->nullOnDelete();

            $table->text('blurb')->nullable();
            $table->text('overview')->nullable();
            $table->json('highlights')->nullable();
            $table->json('included')->nullable();
            $table->json('excluded')->nullable();
            $table->json('gallery')->nullable();
            $table->json('itinerary')->nullable();

            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'circuit']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
