<?php

namespace Database\Factories;

use App\Module\Company\Models\Company;
use App\Module\Order\Models\Order;
use App\Module\Order\Models\Sender;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'number'     => (string)$this->faker->numberBetween(100000, 999999),
            'company_id' => Company::factory()->create(),
            'sender_id'  => Sender::factory()->create(),
            'user_id'    => $this->faker->randomNumber(1),
            'source'     => $this->faker->word,
            'parent_id'  => $this->faker->randomNumber(),
        ];
    }
}
