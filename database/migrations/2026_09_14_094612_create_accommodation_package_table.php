<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodation_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('accommodation_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['package_id', 'accommodation_id']);
        });

        // Carry the existing single link over to the pivot before dropping it.
        if (Schema::hasColumn('packages', 'accommodation_id')) {
            DB::table('packages')
                ->whereNotNull('accommodation_id')
                ->orderBy('id')
                ->each(function ($package) {
                    DB::table('accommodation_package')->insertOrIgnore([
                        'package_id' => $package->id,
                        'accommodation_id' => $package->accommodation_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                });

            Schema::table('packages', function (Blueprint $table) {
                $table->dropForeign(['accommodation_id']);
                $table->dropColumn('accommodation_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('accommodation_id')->nullable()->constrained()->nullOnDelete();
        });

        // Restore the first linked accommodation as the single one.
        DB::table('accommodation_package')
            ->orderBy('id')
            ->get()
            ->groupBy('package_id')
            ->each(function ($rows, $packageId) {
                DB::table('packages')
                    ->where('id', $packageId)
                    ->update(['accommodation_id' => $rows->first()->accommodation_id]);
            });

        Schema::dropIfExists('accommodation_package');
    }
};
