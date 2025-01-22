<?php

namespace Database\Factories;

use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceCargoFactory extends Factory
{
    protected $model = InvoiceCargo::class;

    public function definition(): array
    {
        return [
            'invoice_id'    => Invoice::factory()->create(),
            'cargo_name'    => $this->faker->word,
            'product_name'  => $this->faker->word,
            'places'        => $this->faker->numberBetween(1, 3),
            'weight'        => $this->faker->randomFloat(0, 2),
            'volume'        => $this->faker->randomFloat(2, 0.1, 0.2),
            'volume_weight' => $this->faker->randomFloat(0, 2),
            'width'         => $this->faker->numberBetween(10, 100),
            'height'        => $this->faker->numberBetween(10, 100),
            'depth'         => $this->faker->numberBetween(10, 100),
            'annotation'    => $this->faker->word,
            'cod_payment'   => $this->faker->numberBetween(1, 5),
            'pack_code'     => $this->faker->word,
        ];
    }
}
