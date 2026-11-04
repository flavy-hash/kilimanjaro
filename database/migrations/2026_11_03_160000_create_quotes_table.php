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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();
            $table->string('source')->default('website');
            $table->string('status')->default('draft');

            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_id')->nullable();

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('customer_country')->nullable();

            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->date('travel_start')->nullable();
            $table->date('travel_end')->nullable();

            $table->json('items')->nullable();
            $table->decimal('discount', 10, 2)->default(0);

            $table->text('notes')->nullable();
            $table->boolean('show_bank_details')->default(true);
            $table->date('valid_until')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
