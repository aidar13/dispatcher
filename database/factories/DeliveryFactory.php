<?php

namespace Database\Factories;

use App\Module\Company\Models\Company;
use App\Module\Courier\Models\Courier;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Delivery>
 */
class DeliveryFactory extends Factory
{
    protected $model = Delivery::class;

    public function definition(): array
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        return [
            'customer_id'            => $customer->id,
            'status_id'              => StatusType::ID_NOT_ASSIGNED,
            'invoice_id'             => Invoice::factory()->create(),
            'invoice_number'         => $this->faker->uuid(),
            'internal_id'            => $this->faker->randomNumber(),
            'courier_id'             => Courier::factory()->create(),
            'city_id'                => $this->faker->randomNumber(),
            'company_id'             => Company::factory(),
            'wait_list_status_id'    => $this->faker->randomNumber(),
            'places'                 => $this->faker->randomNumber(),
            'weight'                 => $this->faker->randomFloat(2),
            'volume'                 => $this->faker->randomFloat(2),
            'volume_weight'          => $this->faker->randomFloat(2),
            'delivery_receiver_name' => $this->faker->name,
            'courier_comment'        => $this->faker->text,
            'delivered_at'           => $this->faker->dateTime()->format('Y-m-d H:i:s'),
        ];
    }
}
