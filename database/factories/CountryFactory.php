<?php

namespace Database\Factories;

use App\Module\City\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'name'  => $this->faker->name,
        ];
    }
}
