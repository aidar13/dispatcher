<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\Order\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourierPaymentFactory extends Factory
{
    protected $model = CourierPayment::class;

    public function definition(): array
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();
        return [
            'id'          => $this->faker->unique()->randomNumber(),
            'courier_id'  => $courier->id,
            'user_id'     => $courier->user_id,
            'client_id'   => Order::factory()->create(),
            'client_type' => Order::class,
            'type'        => $this->faker->randomElement([CourierPayment::TYPE_COST_FOR_ROAD, CourierPayment::TYPE_COST_FOR_PARKING]),
            'cost'        => $this->faker->randomNumber(),
        ];
    }
}
