<?php

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierCall;
use App\Module\Take\Models\OrderTake;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourierCallFactory extends Factory
{
    protected $model = CourierCall::class;

    public function definition(): array
    {
        return [
            'courier_id'  => Courier::factory()->create(),
            'client_id'   => OrderTake::factory()->create(),
            'client_type' => OrderTake::class,
            'phone'       => $this->faker->phoneNumber,
        ];
    }
}
