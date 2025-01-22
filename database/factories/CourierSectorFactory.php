<?php

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierSector;
use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourierSector>
 */
class CourierSectorFactory extends Factory
{
    protected $model = CourierSector::class;

    public function definition(): array
    {
        return [
            'courier_id' => Courier::factory()->create(),
            'sector_id'  => Sector::factory()->create(),
            'type_id' => $this->faker->randomElement([
                CourierSector::TYPE_ALLOWED,
                CourierSector::TYPE_FORBIDDEN,
                CourierSector::TYPE_OPTIONAL,
            ])
        ];
    }
}
