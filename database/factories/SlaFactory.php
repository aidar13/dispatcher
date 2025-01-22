<?php

namespace Database\Factories;

use App\Module\City\Models\City;
use App\Module\Order\Models\Sla;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlaFactory extends Factory
{
    protected $model = Sla::class;

    public function definition(): array
    {
        return [
            'city_from'        => City::factory()->create(),
            'city_to'          => City::factory()->create(),
            'pickup'           => $this->faker->numberBetween(0, 100),
            'processing'       => $this->faker->numberBetween(0, 100),
            'transit'          => $this->faker->numberBetween(0, 100),
            'delivery'         => $this->faker->numberBetween(0, 100),
            'shipment_type_id' => $this->faker->numberBetween(1, 2),
        ];
    }
}
