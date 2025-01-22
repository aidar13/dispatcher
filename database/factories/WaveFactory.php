<?php

namespace Database\Factories;

use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Wave;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wave>
 */
class WaveFactory extends Factory
{
    protected $model = Wave::class;

    public function definition(): array
    {
        return [
            'title'                => $this->faker->word,
            'dispatcher_sector_id' => DispatcherSector::factory()->create(),
            'from_time'            => $this->faker->time('H:i'),
            'to_time'              => $this->faker->time('H:i'),
        ];
    }
}
