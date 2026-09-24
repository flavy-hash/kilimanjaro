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
        Schema::table('packages', function (Blueprint $table) {
            $table->text('quote')->nullable()->after('blurb');
            $table->json('recommended_for')->nullable()->after('highlights');
            $table->text('route_comparison_intro')->nullable()->after('recommended_for');
            $table->json('route_comparison_tiers')->nullable()->after('route_comparison_intro');
            $table->json('choose_this_if')->nullable()->after('route_comparison_tiers');
            $table->json('choose_other_if')->nullable()->after('choose_this_if');
            $table->string('choose_other_label')->nullable()->after('choose_other_if');
            $table->json('tipping_table')->nullable()->after('excluded');
            $table->json('optional_extras')->nullable()->after('tipping_table');
            $table->text('closing_note')->nullable()->after('optional_extras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'quote',
                'recommended_for',
                'route_comparison_intro',
                'route_comparison_tiers',
                'choose_this_if',
                'choose_other_if',
                'choose_other_label',
                'tipping_table',
                'optional_extras',
                'closing_note',
            ]);
        });
    }
};
