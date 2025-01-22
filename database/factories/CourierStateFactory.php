<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierState;
use App\Module\Take\Models\OrderTake;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CourierStateFactory extends Factory
{
    protected $model = CourierState::class;

    public function definition(): array
    {
        return [
            'courier_id'  => Courier::factory()->create(),
            'client_id'   => OrderTake::factory()->create(),
            'client_type' => OrderTake::class,
            'latitude'    => $this->faker->latitude,
            'longitude'   => $this->faker->longitude,
            'created_at'  => $this->faker->dateTime()->format('Y-m-d\TH:i:s')
        ];
    }
}
