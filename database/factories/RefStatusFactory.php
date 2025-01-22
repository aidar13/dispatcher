<?php

namespace Database\Factories;

use App\Module\Status\Models\RefStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefStatusFactory extends Factory
{
    protected $model = RefStatus::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->word(),
            'code'       => $this->faker->numberBetween(1, 1000),
            'order'      => $this->faker->randomDigitNotNull,
            'comment'    => $this->faker->word,
        ];
    }
}
