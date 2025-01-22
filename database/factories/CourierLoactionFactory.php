<?php

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierLoaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourierLoaction>
 */
class CourierLoactionFactory extends Factory
{
    protected $model = CourierLoaction::class;

    public function definition(): array
    {
        return [
            'courier_id' => Courier::factory()->create(),
            'downtime'   => $this->faker->randomDigit(),
            'latitude'   => $this->faker->latitude,
            'longitude'  => $this->faker->longitude,
        ];
    }
}
