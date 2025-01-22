<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Module\Car\Models\CarOccupancyType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarOccupancyTypeFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = CarOccupancyType::class;

    public function definition(): array
    {
        $percent = $this->faker->numberBetween(1, 100);

        return [
            'percent' => $percent,
            'title'   => sprintf('%d%%', $percent),
        ];
    }
}
