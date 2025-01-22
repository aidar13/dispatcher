<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Module\Car\Models\CarOccupancy;
use App\Module\Car\Models\CarOccupancyType;
use App\Module\Order\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CarOccupancyFactory extends Factory
{
    protected $model = CarOccupancy::class;

    public function definition(): array
    {
        return [
            'car_occupancy_type_id' => $this->faker->randomElement([
                CarOccupancyType::ID_EMPTY,
                CarOccupancyType::ID_25_PERCENT,
                CarOccupancyType::ID_50_PERCENT,
                CarOccupancyType::ID_75_PERCENT,
                CarOccupancyType::ID_100_PERCENT
            ]),
            'type_id'               => CarOccupancy::COURIER_WORK_TYPE_ID_TAKE,
            'user_id'               => $this->faker->randomNumber(),
            'car_id'                => $this->faker->randomNumber(),
            'client_type'           => Order::class,
            'client_id'             => Order::factory()->create(),
            'created_at'            => Carbon::now()->toDateString(),
        ];
    }
}
