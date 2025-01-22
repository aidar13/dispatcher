<?php

namespace Database\Factories;

use App\Module\Order\Models\Invoice;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContainerInvoiceFactory extends Factory
{
    protected $model = ContainerInvoice::class;

    public function definition(): array
    {
        return [
            'invoice_id'   => Invoice::factory()->create(),
            'container_id' => Container::factory()->create(),
            'position'     => mt_rand(1, 10)
        ];
    }
}
