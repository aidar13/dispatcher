<?php

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierStatus;
use App\Module\Delivery\Models\Delivery;
use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Delivery>
 */
class RouteSheetFactory extends Factory
{
    protected $model = RouteSheet::class;

    public function definition(): array
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create(['status_id' => CourierStatus::ID_ACTIVE]);

        return [
            'number'     => $this->faker->unique()->word,
            'status_id'  => RouteSheet::ID_IN_PROGRESS,
            'date'       => $this->faker->date,
            'courier_id' => $courier->id,
            'city_id'    => $courier->dispatcherSector->city_id,
        ];
    }
}
