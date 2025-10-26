<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('places_products')) {
            throw new \RuntimeException("Table 'places_products' does not exist.");
        }

        // Use raw SQL for Postgres to change column type to numeric(8,2)
        DB::statement("ALTER TABLE places_products ALTER COLUMN price TYPE numeric(8,2) USING price::numeric;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('places_products')) {
            return;
        }

        // Convert back to integer by rounding
        DB::statement("ALTER TABLE places_products ALTER COLUMN price TYPE integer USING round(price)::integer;");
    }
};
