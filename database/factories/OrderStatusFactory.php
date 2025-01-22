<?php

namespace Database\Factories;

use App\Module\Status\Models\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderStatusFactory extends Factory
{
    protected $model = OrderStatus::class;

    public function definition(): array
    {
        return [
            'id'             => $this->faker->unique()->randomNumber(),
            'invoice_id'     => 1,
            'invoice_number' => (string)$this->faker->numberBetween(100000, 999999999),
            'order_id'       => 1,
            'code'           => $this->faker->randomNumber(),
            'title'          => $this->faker->name,
            'comment'        => $this->faker->name,
            'source_id'      => $this->faker->numberBetween(1, 10),
            'user_id'        => $this->faker->randomNumber(),
        ];
    }
}
