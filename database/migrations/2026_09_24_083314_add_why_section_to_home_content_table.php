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
            $table->string('why_tag')->nullable();
            $table->string('why_title')->nullable();
            $table->text('why_lede')->nullable();
            $table->json('why_items')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_content', function (Blueprint $table) {
            $table->dropColumn(['why_tag', 'why_title', 'why_lede', 'why_items']);
        });
    }
};
