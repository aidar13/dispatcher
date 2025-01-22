<?php

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierStop;
use App\Module\Take\Models\OrderTake;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourierStopFactory extends Factory
{
    protected $model = CourierStop::class;

    public function definition(): array
    {
        return [
            'id'          => $this->faker->unique()->randomNumber(),
            'courier_id'  => Courier::factory()->create(),
            'client_id'   => OrderTake::factory()->create(),
            'client_type' => OrderTake::class
        ];
    }
}
