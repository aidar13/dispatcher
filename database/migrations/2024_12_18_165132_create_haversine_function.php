<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        // Define the Haversine function
        DB::statement('
            CREATE FUNCTION Haversine(lat1 DOUBLE, lon1 DOUBLE, lat2 DOUBLE, lon2 DOUBLE)
            RETURNS DOUBLE
            BEGIN
                DECLARE R DOUBLE DEFAULT 6371000; -- Earth radius in meters
                DECLARE dLat DOUBLE;
                DECLARE dLon DOUBLE;
                DECLARE a DOUBLE;
                DECLARE c DOUBLE;

                SET dLat = RADIANS(lat2 - lat1);
                SET dLon = RADIANS(lon2 - lon1);
                SET a = SIN(dLat / 2) * SIN(dLat / 2) +
                        COS(RADIANS(lat1)) * COS(RADIANS(lat2)) *
                        SIN(dLon / 2) * SIN(dLon / 2);
                SET c = 2 * ATAN2(SQRT(a), SQRT(1 - a));
                RETURN R * c; -- Distance in meters
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the Haversine function if it exists
        DB::statement('DROP FUNCTION IF EXISTS Haversine');
    }
};
