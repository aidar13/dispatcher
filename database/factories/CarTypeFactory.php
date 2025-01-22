<?php

namespace Database\Factories;

use App\Module\Car\Models\CarType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CarType>
 */
class CarTypeFactory extends Factory
{
    protected $model = CarType::class;

    public function definition(): array
    {
        return [
            'title'    => $this->faker->randomElement(['Легковая', '1.5 тонн', '3 тонны', '5 тонн']),
            'volume'   => $this->faker->numberBetween(1, 10),
            'capacity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
