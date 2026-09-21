<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            // Never shown on the public page — only so the admin can follow up.
            $table->string('email')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->unsignedTinyInteger('service_rating')->nullable();
            $table->unsignedTinyInteger('value_rating')->nullable();
            $table->string('title');
            $table->text('body');
            $table->string('photo')->nullable();
            // Public submissions land here unapproved; admin-entered reviews
            // can be created already approved.
            $table->boolean('is_approved')->default(false);
            // A short-list shown on the homepage, distinct from "approved for
            // the /reviews page" — lets the admin pick the strongest few.
            $table->boolean('is_featured')->default(false);
            $table->timestamp('stayed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
