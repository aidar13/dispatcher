<?php

namespace Database\Factories;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use App\Module\Order\Models\Receiver;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'id'                     => $this->faker->unique()->randomNumber(),
            'invoice_number'         => (string)$this->faker->unique()->numberBetween(100000, 999999999),
            'order_id'               => Order::factory()->create(),
            'status_id'              => $this->faker->numberBetween(1, 10),
            'receiver_id'            => Receiver::factory()->create(),
            'direction_id'           => $this->faker->randomNumber(),
            'shipment_id'            => $this->faker->numberBetween(1, 1000),
            'period_id'              => $this->faker->randomNumber(),
            'take_date'              => $this->faker->dateTime->format('Y-m-d'),
            'take_time'              => $this->faker->dateTime->format('Y-m-d'),
            'code_1c'                => $this->faker->numerify('###########'),
            'dop_invoice_number'     => (string)$this->faker->unique()->numberBetween(100000, 999999999),
            'cash_sum'               => $this->faker->randomFloat(2),
            'should_return_document' => (int)$this->faker->boolean,
            'weekend_delivery'       => (int)$this->faker->boolean,
            'verify'                 => $this->faker->numberBetween(0, 2),
            'cargo_type'             => $this->faker->numberBetween(1, 2),
            'payer_company_id'       => $this->faker->numberBetween(1, 200),
            'sla_date'               => $this->faker->dateTimeBetween(now()->subDays(2), now()->addDays(10)),
        ];
    }
}
