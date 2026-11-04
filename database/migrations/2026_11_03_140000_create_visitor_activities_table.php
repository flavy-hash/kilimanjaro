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
        Schema::create('visitor_activities', function (Blueprint $table) {
            $table->id();

            // Rotates daily (date baked into the hash) so a visitor can never be
            // traced across days — it only groups one visitor's pages on one day.
            $table->string('visitor_hash', 64)->index();

            $table->string('page_path');
            $table->string('page_label')->nullable();
            $table->string('package_slug')->nullable();

            $table->string('device_type', 20)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();
            $table->string('referrer_host')->nullable();

            $table->timestamp('visited_at')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_activities');
    }
};
