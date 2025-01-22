<?php

namespace Database\Factories;

use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\Take\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'full_name'            => $this->faker->name,
            'address'              => $this->faker->address,
            'phone'                => $this->faker->phoneNumber,
            'additional_phone'     => $this->faker->phoneNumber,
            'latitude'             => (string)$this->faker->latitude,
            'longitude'            => (string)$this->faker->latitude,
            'dispatcher_sector_id' => DispatcherSector::factory()->create(),
            'sector_id'            => Sector::factory()->create(),
        ];
    }
}
