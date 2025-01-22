<?php

namespace Database\Factories;

use App\Module\City\Models\City;
use App\Module\City\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->domainWord,
            'region_id'   => Region::factory()->create(),
            'type_id'     => $this->faker->numberBetween(0, 100),
            'code'        => $this->faker->word,
            'latitude'    => $this->faker->latitude,
            'longitude'   => $this->faker->longitude,
            'coordinates' => json_encode($this->faker->localCoordinates),
        ];
    }
}
