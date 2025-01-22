<?php

namespace Database\Factories;

use App\Models\User;
use App\Module\Delivery\Models\ReturnDelivery;
use App\Module\Order\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReturnDelivery>
 */
class ReturnDeliveryFactory extends Factory
{
    protected $model = ReturnDelivery::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory()->create(),
            'user_id'    => User::factory()->create(),
        ];
    }
}
