<?php

namespace Database\Factories;

use App\Module\Courier\Models\CourierScheduleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourierScheduleType>
 */
class CourierScheduleTypeFactory extends Factory
{
    protected $model = CourierScheduleType::class;

    public function definition(): array
    {
        return [
            'title'           => $this->faker->text,
            'work_time_from'  => $this->faker->time('H:i'),
            'work_time_until' => $this->faker->time('H:i'),
        ];
    }
}
