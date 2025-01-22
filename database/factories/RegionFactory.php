<?php

namespace Database\Factories;

use App\Module\City\Models\Country;
use App\Module\City\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition(): array
    {
        /** @var Country $country */
        $country = Country::factory()->create();

        return [
            'name'       => $this->faker->word,
            'country_id' => $country->id,
        ];
    }
}
