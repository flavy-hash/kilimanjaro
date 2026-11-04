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
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('country')->nullable()->after('name');
            // Up to a few trip photos, shown as a small strip above the card.
            // The older single `photo` column is kept as a fallback for
            // reviews submitted before this existed.
            $table->json('photos')->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['country', 'photos']);
        });
    }
};
