<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Module\Status\Models\StatusType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StatusType>
 */
class StatusTypeFactory extends Factory
{
    protected $model = StatusType::class;

    public function definition(): array
    {
        return [
            'title'       => $this->faker->name,
            'description' => $this->faker->name,
            'type'        => array_rand([StatusType::TYPE_TAKE, StatusType::TYPE_DELIVERY]),
        ];
    }
}
