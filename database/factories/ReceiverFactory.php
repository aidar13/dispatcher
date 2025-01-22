<?php

namespace Database\Factories;

use App\Module\City\Models\City;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\Order\Models\Receiver;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiverFactory extends Factory
{
    protected $model = Receiver::class;

    public function definition(): array
    {
        /** @var City $city */
        $city = City::factory()->create();

        return [
            'city_id'              => $city->id,
            'full_address'         => $this->faker->address,
            'title'                => $this->faker->name,
            'full_name'            => $this->faker->name,
            'phone'                => $this->faker->numerify('7707#######'),
            'additional_phone'     => $this->faker->numerify('7707#######'),
            'longitude'            => (string)$this->faker->latitude('0'),
            'latitude'             => (string)$this->faker->longitude('0'),
            'street'               => $this->faker->name,
            'house'                => $this->faker->name,
            'office'               => $this->faker->name,
            'index'                => $this->faker->name,
            'comment'              => $this->faker->name,
            'warehouse_id'         => $this->faker->randomNumber(),
            'dispatcher_sector_id' => DispatcherSector::factory()->create(),
            'sector_id'            => Sector::factory()->create(),
        ];
    }
}
