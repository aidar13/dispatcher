<?php

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourierSchedule>
 */
class CourierScheduleFactory extends Factory
{
    protected $model = CourierSchedule::class;

    public function definition(): array
    {
        return [
            'courier_id'      => Courier::factory()->create(),
            'weekday'         => $this->faker->text,
            'work_time_from'  => $this->faker->time(),
            'work_time_until' => $this->faker->time(),
        ];
    }
}
