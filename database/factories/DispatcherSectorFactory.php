<?php

namespace Database\Factories;

use App\Helpers\PolygonHelper;
use App\Module\City\Models\City;
use App\Module\DispatcherSector\Models\DispatcherSector;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DispatcherSector>
 */
class DispatcherSectorFactory extends Factory
{
    protected $model = DispatcherSector::class;

    public function definition(): array
    {
        return [
            'name'                => $this->faker->word,
            'description'         => $this->faker->text,
            'delivery_manager_id' => $this->faker->numberBetween(1, 100),
            'city_id'             => City::factory()->create(),
            'coordinates'         => json_encode($this->getCoordinates()),
            'polygon'             => PolygonHelper::getPolygonFromCoordinates($this->getCoordinates()),
        ];
    }

    private function getCoordinates(): array
    {
        return [
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
            [$this->faker->latitude(), $this->faker->longitude()],
        ];
    }
}
