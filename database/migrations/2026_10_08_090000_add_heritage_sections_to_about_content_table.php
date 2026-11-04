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
        Schema::table('about_content', function (Blueprint $table) {
            $table->string('generations_tag')->nullable();
            $table->string('generations_title')->nullable();
            $table->json('generations')->nullable();
            $table->text('generations_closing')->nullable();

            $table->string('do_tag')->nullable();
            $table->string('do_title')->nullable();
            $table->json('do_items')->nullable();

            $table->string('safety_tag')->nullable();
            $table->string('safety_title')->nullable();
            $table->text('safety_lede')->nullable();
            $table->json('safety_points')->nullable();

            $table->string('locally_owned_title')->nullable();
            $table->text('locally_owned_text')->nullable();

            $table->text('closing_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_content', function (Blueprint $table) {
            $table->dropColumn([
                'generations_tag', 'generations_title', 'generations', 'generations_closing',
                'do_tag', 'do_title', 'do_items',
                'safety_tag', 'safety_title', 'safety_lede', 'safety_points',
                'locally_owned_title', 'locally_owned_text',
                'closing_text',
            ]);
        });
    }
};
