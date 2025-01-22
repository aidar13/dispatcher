<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Module\Order\Models\FastDeliveryOrder;
use App\Module\Planning\Models\Container;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FastDeliveryOrderFactory extends Factory
{
    protected $model = FastDeliveryOrder::class;

    public function definition(): array
    {
        return [
            'container_id'    => Container::factory()->create(),
            'internal_id'     => $this->faker->randomNumber(),
            'courier_name'    => $this->faker->name,
            'courier_phone'   => $this->faker->phoneNumber,
            'tracking_url'    => $this->faker->url,
            'internal_status' => $this->faker->word,
            'price'           => (string)$this->faker->numberBetween(100, 2000),
            'type'            => $this->faker->randomElement([FastDeliveryOrder::TYPE_RAKETA])
        ];
    }
}
