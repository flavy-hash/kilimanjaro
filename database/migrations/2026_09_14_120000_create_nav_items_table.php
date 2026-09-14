<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');

            // 'link' renders as a plain top-level link (e.g. All Tours);
            // 'mega' renders the dropdown panel with photo, copy and link list.
            $table->string('type')->default('mega');

            $table->string('href')->nullable();

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->string('cta_label')->nullable();
            $table->string('cta_action')->nullable();
            $table->string('cta_target')->nullable();

            // [{ text, action, target }, ...] — same shape the dropdown link list uses.
            $table->json('links')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_items');
    }
};
