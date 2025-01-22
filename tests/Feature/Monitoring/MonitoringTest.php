<?php

declare(strict_types=1);

namespace Tests\Feature\Monitoring;

use App\Module\Car\Models\Car;
use App\Module\Courier\Models\Courier;
use App\Module\Delivery\Models\Delivery;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\Take\Models\Customer;
use App\Module\Take\Models\OrderTake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class MonitoringTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = $this->faker('kk_KZ');
        $this->withoutExceptionHandling();
    }

    public function testDeliveriesInfo()
    {
        $dispatcherSectorId = 24;

        /** @var Sector $sector */
        $sector = Sector::factory()->count(3)->create([
            'dispatcher_sector_id' => $dispatcherSectorId
        ]);

        /** @var Customer $customer1 */
        $customer1 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[0]->id
        ]);

        /** @var Customer $customer2 */
        $customer2 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[1]->id
        ]);

        /** @var Customer $customer3 */
        $customer3 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[2]->id
        ]);

        Delivery::factory()->count(10)->create([
            'created_at'  => '2023-08-01',
            'customer_id' => $customer1->id
        ]);

        Delivery::factory()->count(10)->create([
            'created_at'  => '2023-07-01',
            'customer_id' => $customer2->id
        ]);

        Delivery::factory()->count(10)->create([
            'created_at'  => '2023-06-01',
            'customer_id' => $customer3->id
        ]);

        $response = $this->get(route('monitoring.deliveries', [
            'dispatcherSectorId' => $dispatcherSectorId,
            'createdAtFrom'      => '2023-01-01',
            'createdAtTo'        => '2023-08-01'
        ]));

        $response->assertJsonStructure([
            'data' => [
                'total'     => [
                    '*' => [
                        'count',
                        'name'
                    ]
                ],
                'completed' => [
                    '*' => [
                        'count',
                        'name'
                    ]
                ],
                'remained'  => [
                    '*' => [
                        'count',
                        'name'
                    ]
                ],
                'cancelled' => [
                    '*' => [
                        'count',
                        'name'
                    ]
                ],
            ]
        ]);
    }

    public function testCouriersMonitoriingInfo()
    {
        $dispatcherSectorId = 24;

        /** @var Sector $sector */
        $sector = Sector::factory()->count(3)->create([
            'dispatcher_sector_id' => $dispatcherSectorId
        ]);

        /** @var Courier $courier */
        $courier = Courier::factory()->count(3)->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'car_id'               => Car::factory()->make()
        ]);

        /** @var Customer $customer1 */
        $customer1 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[0]->id
        ]);

        /** @var Customer $customer2 */
        $customer2 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[1]->id
        ]);

        /** @var Customer $customer3 */
        $customer3 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[2]->id
        ]);

        OrderTake::factory()->count(10)->create([
            'created_at'  => '2023-08-01',
            'customer_id' => $customer1->id,
            'courier_id'  => $courier[0]
        ]);

        OrderTake::factory()->count(10)->create([
            'created_at'  => '2023-07-01',
            'customer_id' => $customer2->id,
            'courier_id'  => $courier[1]
        ]);

        OrderTake::factory()->count(10)->create([
            'created_at'  => '2023-06-01',
            'customer_id' => $customer3->id,
            'courier_id'  => $courier[2]
        ]);

        Delivery::factory()->count(10)->create([
            'created_at'  => '2023-08-01',
            'customer_id' => $customer1->id,
            'courier_id'  => $courier[0]
        ]);

        Delivery::factory()->count(10)->create([
            'created_at'  => '2023-07-01',
            'customer_id' => $customer2->id,
            'courier_id'  => $courier[1]
        ]);

        Delivery::factory()->count(10)->create([
            'created_at'  => '2023-06-01',
            'customer_id' => $customer3->id,
            'courier_id'  => $courier[2]
        ]);

        $response = $this->get(route('monitoring.couriers', [
            'dispatcherSectorId' => $dispatcherSectorId,
            'createdAtFrom'      => '2023-01-01',
            'createdAtTo'        => '2023-08-01'
        ]));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'fullName',
                    'totalTakes',
                    'totalDeliveries',
                    'cancelledTakes',
                    'cancelledDeliveries',
                    'remainedTakes',
                    'remainedDeliveries',
                    'completedTakes',
                    'completedDeliveries'
                ]
            ]
        ]);
    }

    public function testOrderTakesInfo()
    {
        $dispatcherSectorId = 24;

        /** @var Sector $sector */
        $sector = Sector::factory()->count(3)->create([
            'dispatcher_sector_id' => $dispatcherSectorId
        ]);

        /** @var Customer $customer1 */
        $customer1 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[0]->id
        ]);

        /** @var Customer $customer2 */
        $customer2 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[1]->id
        ]);

        /** @var Customer $customer3 */
        $customer3 = Customer::factory()->create([
            'dispatcher_sector_id' => $dispatcherSectorId,
            'sector_id'            => $sector[2]->id
        ]);

        OrderTake::factory()->count(10)->create([
            'created_at'  => '2023-08-01',
            'customer_id' => $customer1->id
        ]);

        OrderTake::factory()->count(10)->create([
            'created_at'  => '2023-07-01',
            'customer_id' => $customer2->id
        ]);

        OrderTake::factory()->count(10)->create([
            'created_at'  => '2023-06-01',
            'customer_id' => $customer3->id
        ]);

        $response = $this->get(route('monitoring.order-takes', [
            'dispatcherSectorId' => $dispatcherSectorId,
            'createdAtFrom'      => '2023-01-01',
            'createdAtTo'        => '2023-08-01'
        ]));

        $response->assertJsonStructure([
            'data' => [
                'total'     => [
                    '*' => [
                        'count',
                        'name'
                    ]
                ],
                'completed' => [
                    '*' => [
                        'count',
                        'name'
                    ]
                ],
                'remained'  => [
                    '*' => [
                        'count',
                        'name'
                    ]
                ],
                'cancelled' => [
                    '*' => [
                        'count',
                        'name'
                    ]
                ],
            ]
        ]);
    }
}
