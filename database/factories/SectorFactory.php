<?php

namespace Database\Factories;

use App\Helpers\PolygonHelper;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sector>
 */
class SectorFactory extends Factory
{
    protected $model = Sector::class;

    public function definition(): array
    {
        return [
            'name'                 => $this->faker->city,
            'dispatcher_sector_id' => DispatcherSector::factory()->create(),
            'color'                => $this->faker->hexColor,
            'coordinates'          => json_encode($this->getCoordinates()),
            'polygon'              => PolygonHelper::getPolygonFromCoordinates($this->getCoordinates()),
            'latitude'             => (string)$this->faker->latitude,
            'longitude'            => (string)$this->faker->longitude,
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
