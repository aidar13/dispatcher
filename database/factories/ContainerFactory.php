<?php

namespace Database\Factories;

use App\Models\User;
use App\Module\Courier\Models\Courier;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Planning\Models\Container;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Container>
 */
class ContainerFactory extends Factory
{
    protected $model = Container::class;

    public function definition(): array
    {
        return [
            'wave_id'          => Wave::factory()->create(),
            'sector_id'        => Sector::factory()->create(),
            'courier_id'       => Courier::factory()->create(),
            'user_id'          => User::factory()->create(),
            'title'            => $this->faker->title,
            'date'             => $this->faker->date,
            'doc_number'       => $this->faker->numerify('#######'),
            'cargo_type'       => $this->faker->randomElement([InvoiceCargo::TYPE_SMALL_CARGO, InvoiceCargo::TYPE_OVERSIZE_CARGO])
        ];
    }
}
