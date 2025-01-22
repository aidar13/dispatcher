<?php

namespace Database\Factories;

use App\Module\City\Models\City;
use App\Module\Order\Models\Sender;
use Illuminate\Database\Eloquent\Factories\Factory;

class SenderFactory extends Factory
{
    protected $model = Sender::class;

    public function definition(): array
    {
        return [
            'city_id'              => City::factory()->create(),
            'full_address'         => $this->faker->address,
            'title'                => $this->faker->title,
            'full_name'            => $this->faker->name,
            'phone'                => $this->faker->numerify('7707#######'),
            'additional_phone'     => $this->faker->numerify('7707#######'),
            'longitude'            => $this->faker->longitude,
            'latitude'             => $this->faker->latitude,
            'street'               => $this->faker->name,
            'house'                => $this->faker->name,
            'office'               => $this->faker->name,
            'index'                => $this->faker->name,
            'comment'              => $this->faker->name,
            'warehouse_id'         => $this->faker->randomNumber(),
        ];
    }
}
