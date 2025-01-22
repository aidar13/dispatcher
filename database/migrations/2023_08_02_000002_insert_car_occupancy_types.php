<?php

use App\Module\Car\Models\CarOccupancyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertCarOccupancyTypes extends Migration
{
    public function up(): void
    {
        DB::table('car_occupancy_types')->insert([
            'id'         => CarOccupancyType::ID_EMPTY,
            'title'      => 'Пустой',
            'percent'    => 0,
            'is_visible' => true
        ]);

        DB::table('car_occupancy_types')->insert([
            'id'         => CarOccupancyType::ID_25_PERCENT,
            'title'      => '25%',
            'percent'    => 25,
            'is_visible' => true
        ]);

        DB::table('car_occupancy_types')->insert([
            'id'         => CarOccupancyType::ID_50_PERCENT,
            'title'      => '50%',
            'percent'    => 50,
            'is_visible' => true
        ]);

        DB::table('car_occupancy_types')->insert([
            'id'         => CarOccupancyType::ID_75_PERCENT,
            'title'      => '75%',
            'percent'    => 75,
            'is_visible' => true
        ]);

        DB::table('car_occupancy_types')->insert([
            'id'         => CarOccupancyType::ID_100_PERCENT,
            'title'      => '100%',
            'percent'    => 100,
            'is_visible' => true
        ]);

        for ($i = 1; $i <= 100; $i++) {
            if (in_array($i, [0, 25, 50, 75, 100], true)) {
                continue;
            }

            DB::table('car_occupancy_types')->insert([
                'title'   => sprintf('%d%%', $i),
                'percent' => $i,
            ]);
        }
    }

    public function down(): void
    {
        for ($i = 1; $i <= 101; $i++) {
            DB::table('car_occupancy_types')->delete($i);
        }
    }
}
