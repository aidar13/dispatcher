<?php

namespace Database\Factories;

use App\Module\Take\Models\OrderPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderPeriodFactory extends Factory
{
    protected $model = OrderPeriod::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'from'  => $this->faker->word,
            'to'    => $this->faker->word
        ];
    }
}
