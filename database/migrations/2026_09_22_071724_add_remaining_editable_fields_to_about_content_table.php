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
            $table->string('values_tag')->nullable();
            $table->string('values_heading')->nullable();

            $table->string('about_cta_title')->nullable();
            $table->text('about_cta_text')->nullable();

            $table->string('team_cta_title')->nullable();
            $table->text('team_cta_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_content', function (Blueprint $table) {
            $table->dropColumn([
                'values_tag', 'values_heading',
                'about_cta_title', 'about_cta_text',
                'team_cta_title', 'team_cta_text',
            ]);
        });
    }
};
