<?php

namespace Database\Factories;

use App\Module\City\Models\City;
use App\Module\Company\Models\Company;
use App\Module\Courier\Models\Courier;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use App\Module\Order\Models\ShipmentType;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Models\Customer;
use App\Module\Take\Models\OrderTake;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderTake>
 */
class OrderTakeFactory extends Factory
{
    protected $model = OrderTake::class;

    public function definition(): array
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        return [
            'customer_id'         => $customer->id,
            'status_id'           => StatusType::ID_NOT_ASSIGNED,
            'invoice_id'          => Invoice::factory()->create(),
            'internal_id'         => $this->faker->randomNumber(),
            'shipment_type'    => $this->faker->randomElement([
                ShipmentType::ID_CAR,
                ShipmentType::ID_PLANE
            ]),
            'courier_id'          => Courier::factory()->create(),
            'city_id'             => City::factory()->create(),
            'company_id'          => Company::factory(),
            'wait_list_status_id' => $this->faker->randomNumber(),
            'take_date'           => $this->faker->date,
            'places'              => $this->faker->randomNumber(),
            'weight'              => $this->faker->randomFloat(2),
            'volume'              => $this->faker->randomFloat(2),
            'order_id'            => Order::factory()->create(),
            'order_number'        => $this->faker->word(),
            'created_at'          => $this->faker->dateTime(),
        ];
    }
}
