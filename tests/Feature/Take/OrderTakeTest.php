<?php

declare(strict_types=1);

namespace Tests\Feature\Take;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierStatus;
use App\Module\CourierApp\Commands\OrderTake\IntegrationOneC\ChangeOrderTakeStatusInOneCCommand;
use App\Module\Delivery\DTO\SetWaitListStatusDTO;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\Gateway\Contracts\Integration\SendToCabinetRepository;
use App\Module\Gateway\Models\Role;
use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Notification\Events\Integration\SendWebNotificationEvent;
use App\Module\Order\Commands\IntegrationChangeTakeDateByOrderCommand;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use App\Module\Status\Commands\SendOrderStatusToCabinetCommand;
use App\Module\Status\Models\StatusType;
use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;
use App\Module\Status\Models\RefStatus;
use App\Module\Take\Commands\AssignCourierToOrderIn1CCommand;
use App\Module\Take\Commands\CreateOrderTakeCommand;
use App\Module\Take\Commands\SetTakeWaitListCommand;
use App\Module\Take\Commands\SetWaitListStatusCommand;
use App\Module\Take\Commands\UpdateOrderTakeCommand;
use App\Module\Take\DTO\CustomerDTO;
use App\Module\Take\DTO\OrderTakeDTO;
use App\Module\Take\Events\OrderTakeAssignedToCourierEvent;
use App\Module\Take\Models\Customer;
use App\Module\Take\Models\OrderPeriod;
use App\Module\Take\Models\OrderTake;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\Repositories\GatewayUserFakeRepository;
use Tests\TestCase;

final class OrderTakeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testCreateTake()
    {
        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        /** @var Customer $customer */
        $customer = Customer::factory()->make();

        $customerDTO                  = new CustomerDTO();
        $customerDTO->fullName        = $customer->full_name;
        $customerDTO->address         = $customer->address;
        $customerDTO->phone           = $customer->phone;
        $customerDTO->additionalPhone = $customer->additional_phone;
        $customerDTO->latitude        = $customer->latitude;
        $customerDTO->longitude       = $customer->longitude;

        /** @var OrderTake $take */
        $take = OrderTake::factory()->make();

        $takeDTO               = new OrderTakeDTO();
        $takeDTO->internalId   = null;
        $takeDTO->invoiceId    = $take->invoice_id;
        $takeDTO->companyId    = $take->company_id;
        $takeDTO->orderId      = $take->order_id;
        $takeDTO->orderNumber  = $take->order_number;
        $takeDTO->cityId       = $take->city_id;
        $takeDTO->takeDate     = $take->take_date;
        $takeDTO->shipmentType = $take->shipment_type;
        $takeDTO->places       = $take->places;
        $takeDTO->weight       = $take->weight;
        $takeDTO->volume       = $take->volume;
        $takeDTO->customerDTO  = $customerDTO;

        dispatch(new CreateOrderTakeCommand($takeDTO));

        $this->assertDatabaseHas('customers', [
            'full_name'        => $customer->full_name,
            'address'          => $customer->address,
            'phone'            => $customer->phone,
            'additional_phone' => $customer->additional_phone,
            'latitude'         => $customer->latitude,
            'longitude'        => $customer->longitude,
        ]);

        $this->assertDatabaseHas('order_takes', [
            'invoice_id'    => $take->invoice_id,
            'internal_id'   => null,
            'company_id'    => $take->company_id,
            'city_id'       => $take->city_id,
            'take_date'     => $take->take_date,
            'shipment_type' => $take->shipment_type,
            'status_id'     => $take->status_id,
            'places'        => $take->places,
            'weight'        => $take->weight,
            'volume'        => $take->volume,
            'created_at'    => now()
        ]);
    }

    public function testUpdateTake()
    {
        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        /** @var Customer $customerModel */
        $customerModel = Customer::factory()->create();
        /** @var Customer $customer */
        $customer = Customer::factory()->make();

        $customerDTO                  = new CustomerDTO();
        $customerDTO->fullName        = $customer->full_name;
        $customerDTO->address         = $customer->address;
        $customerDTO->phone           = $customer->phone;
        $customerDTO->additionalPhone = $customer->additional_phone;
        $customerDTO->latitude        = $customer->latitude;
        $customerDTO->longitude       = $customer->longitude;

        /** @var OrderTake $takeModel */
        $takeModel = OrderTake::factory()->create(['customer_id' => $customerModel->id]);
        /** @var OrderTake $take */
        $take = OrderTake::factory()->make();

        $takeDTO               = new OrderTakeDTO();
        $takeDTO->internalId   = $takeModel->internal_id;
        $takeDTO->orderId      = $takeModel->order_id;
        $takeDTO->invoiceId    = $takeModel->invoice_id;
        $takeDTO->orderNumber  = $take->order_number;
        $takeDTO->companyId    = $take->company_id;
        $takeDTO->cityId       = $take->city_id;
        $takeDTO->takeDate     = $take->take_date;
        $takeDTO->shipmentType = $take->shipment_type;
        $takeDTO->places       = $take->places;
        $takeDTO->weight       = $take->weight;
        $takeDTO->volume       = $take->volume;
        $takeDTO->customerDTO  = $customerDTO;

        dispatch(new UpdateOrderTakeCommand($takeDTO));

        $this->assertDatabaseHas('customers', [
            'id'               => $customerModel->id,
            'full_name'        => $customer->full_name,
            'address'          => $customer->address,
            'phone'            => $customer->phone,
            'additional_phone' => $customer->additional_phone,
            'latitude'         => $customer->latitude,
            'longitude'        => $customer->longitude,
        ]);

        $this->assertDatabaseHas('order_takes', [
            'invoice_id'    => $takeModel->invoice_id,
            'internal_id'   => $takeModel->internal_id,
            'company_id'    => $take->company_id,
            'city_id'       => $take->city_id,
            'take_date'     => $take->take_date,
            'shipment_type' => $take->shipment_type,
            'status_id'     => $take->status_id,
            'places'        => $take->places,
            'weight'        => $take->weight,
            'volume'        => $take->volume,
        ]);
    }

    public function testDeleteTake()
    {
        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        /** @var Customer $customerModel */
        $customerModel = Customer::factory()->create();
        /** @var Customer $customer */
        $customer = Customer::factory()->make();

        $customerDTO                  = new CustomerDTO();
        $customerDTO->fullName        = $customer->full_name;
        $customerDTO->address         = $customer->address;
        $customerDTO->phone           = $customer->phone;
        $customerDTO->additionalPhone = $customer->additional_phone;
        $customerDTO->latitude        = $customer->latitude;
        $customerDTO->longitude       = $customer->longitude;

        /** @var OrderTake $takeModel */
        $takeModel = OrderTake::factory()->create(['customer_id' => $customerModel->id]);
        /** @var OrderTake $take */
        $take = OrderTake::factory()->make();

        $takeDTO               = new OrderTakeDTO();
        $takeDTO->internalId   = $takeModel->internal_id;
        $takeDTO->invoiceId    = $take->invoice_id;
        $takeDTO->companyId    = $take->company_id;
        $takeDTO->cityId       = $take->city_id;
        $takeDTO->takeDate     = $take->take_date;
        $takeDTO->shipmentType = $take->shipment_type;
        $takeDTO->places       = $take->places;
        $takeDTO->weight       = $take->weight;
        $takeDTO->volume       = $take->volume;
        $takeDTO->customerDTO  = $customerDTO;
        $takeDTO->deletedAt    = now()->toDateTimeString();

        dispatch(new UpdateOrderTakeCommand($takeDTO));

        $this->assertDatabaseHas('customers', [
            'id'               => $customerModel->id,
            'full_name'        => $customer->full_name,
            'address'          => $customer->address,
            'phone'            => $customer->phone,
            'additional_phone' => $customer->additional_phone,
            'latitude'         => $customer->latitude,
            'longitude'        => $customer->longitude,
        ]);

        $this->assertSoftDeleted('order_takes');
    }

    public function testGetAllOrderTakes()
    {
        $date = Carbon::now()->format('Y-m-d');

        /** @var Order $order */
        $order = Order::factory()->create();

        /** @var Collection $orderTakes */
        OrderTake::factory()->count(5)->create([
            'take_date'  => $date,
            'invoice_id' => Invoice::factory()->create(),
            'order_id'   => $order->id
        ]);

        $response = $this->get(route('order-take.index', ['date' => $date]));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'orderId',
                        'weight',
                        'takeDate',
                        'volumeWeight',
                        'places',
                        'hasPackType',
                        'problems',
                        'status'   => [
                            'id',
                            'title',
                        ],
                        'customer' => [
                            'id',
                            'fullName',
                            'address',
                            'shortAddress',
                            'phone',
                            'additionalPhone',
                            'sector' => [
                                'id',
                                'name',
                            ]
                        ],
                        'city'     => [
                            'id',
                            'name',
                        ],
                        'courier'  => [
                            'id',
                            'fullName',
                            'phoneNumber',
                        ],
                        'callCenterComment',
                        'hasAdditionalServices'
                    ]
                ],
            ]);
    }

    public function testAssignOrderTakesToCourier()
    {
        $this->mock(SendPushNotificationRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        });

        Event::fake([
            OrderTakeAssignedToCourierEvent::class,
        ]);

        Bus::fake([
            AssignCourierToOrderIn1CCommand::class,
        ]);

        $this->mock(SendToCabinetRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('assignOrderTakes')->once();
        });

        /** @var Order $order */
        $order = Order::factory()->create();

        /** @var Courier $courier */
        $courier = Courier::factory()->create(['status_id' => CourierStatus::ID_ACTIVE]);

        for ($i = 0; $i <= 5; $i++) {
            OrderTake::factory()->create([
                'invoice_id' => Invoice::factory()->create(['order_id' => $order->id]),
                'order_id'   => $order->id,
            ]);
        }

        $data = [
            'orderIds'  => [$order->id],
            'courierId' => $courier->id
        ];

        $response = $this->post(
            route('order-take.assign'),
            $data,
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Заборы успешно назначены на курьера"
            ]);

        foreach ($order->invoices as $invoice) {
            $this->assertDatabaseHas('order_takes', [
                'id'         => $invoice->take->id,
                'courier_id' => $courier->id,
                'status_id'  => StatusType::ID_ASSIGNED,
            ]);
        }

        Event::assertDispatched(OrderTakeAssignedToCourierEvent::class);
        Bus::assertDispatched(AssignCourierToOrderIn1CCommand::class);
    }

    public function testChangeTakeDateByOrderId()
    {
        Bus::fake([
            IntegrationChangeTakeDateByOrderCommand::class,
//            SetWaitListStatusCommand::class,
        ]);

        $this->app->bind(GatewayUserQuery::class, function () {
            return new GatewayUserFakeRepository(
                roles: [['id' => Role::ID_CALL_CENTER]],
            );
        });

        /** @var Order $order */
        $order = Order::factory()->create();

        /** @var OrderTake $orderTake */
        $orderTake = OrderTake::factory()->create([
            'take_date'  => Carbon::now()->format('Y-m-d'),
            'order_id'   => $order->id,
            'invoice_id' => Invoice::factory()->create([
                'order_id'  => $order->id,
                'status_id' => RefStatus::ID_CREATED
            ]),
        ]);

        /** @var OrderTake $secondOrderTake */
        $secondOrderTake = OrderTake::factory()->create([
            'invoice_id' => Invoice::factory()->create([
                'order_id'  => $orderTake->order_id,
                'status_id' => RefStatus::ID_CREATED
            ]),
        ]);

        $newDate = '2021-01-05';
        $period  = OrderPeriod::factory()->create();

        $response = $this->postJson(route('order-take.change-date'), [
            'orderId'  => $order->id,
            'newDate'  => $newDate,
            'periodId' => $period->id
        ]);

        $this->assertDatabaseHas('order_takes', [
            'id'        => $orderTake->id,
            'take_date' => $newDate,
        ]);

        $this->assertDatabaseHas('invoices', [
            'id'        => $orderTake->invoice_id,
            'take_date' => $newDate,
            'period_id' => $period->id,
        ]);

        $this->assertDatabaseHas('order_takes', [
            'id'         => $secondOrderTake->id,
            'take_date'  => $newDate,
            'status_id'  => StatusType::ID_NOT_ASSIGNED,
            'courier_id' => null
        ]);

        $this->assertDatabaseHas('invoices', [
            'id'        => $secondOrderTake->invoice_id,
            'take_date' => $newDate,
            'period_id' => $period->id,
        ]);

        $response->assertStatus(ResponseCodes::SUCCESS)->assertJson([
            'message' => 'Дата забора успешно обновлена'
        ]);

//        Bus::assertDispatched(SetWaitListStatusCommand::class);
        Bus::assertDispatched(IntegrationChangeTakeDateByOrderCommand::class);
    }

    public function testSetTakeWaitListStatus()
    {
        /** @var OrderTake $orderTake */
        $orderTake = OrderTake::factory()->create();

        $dto             = new SetWaitListStatusDTO();
        $dto->internalId = $orderTake->internal_id;
        $dto->statusId   = $this->faker->numberBetween(1, 10);

        dispatch(new SetTakeWaitListCommand($dto));

        $this->assertDatabaseHas('order_takes', [
            'id'                  => $orderTake->id,
            'invoice_id'          => $orderTake->invoice_id,
            'internal_id'         => $orderTake->internal_id,
            'wait_list_status_id' => $dto->statusId,
        ]);
    }

    public function testUpdateTakeStatus()
    {
        /** @var OrderTake $orderTake */
        $orderTake = OrderTake::factory()->create(
            ['status_id' => StatusType::ID_NOT_ASSIGNED],
        );

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->numberBetween(0, 100);
        $dto->invoiceId     = $orderTake->invoice_id;
        $dto->invoiceNumber = $orderTake->invoice->invoice_number;
        $dto->orderId       = $orderTake->order_id;
        $dto->title         = $this->faker->title;
        $dto->code          = RefStatus::CODE_CARGO_PICKED_UP;
        $dto->comment       = $this->faker->title;
        $dto->sourceId      = null;
        $dto->userId        = null;
        $dto->createdAt     = \Illuminate\Support\Carbon::now();

        dispatch(new CreateOrderStatusCommand($dto));

        $this->assertDatabaseHas('order_takes', [
            'id'        => $orderTake->id,
            'status_id' => StatusType::ID_TAKEN
        ]);
    }

    public function testGetOrderTakeInfos()
    {
        /** @var OrderTake $take1 */
        $take1 = OrderTake::factory()->create();
        /** @var OrderTake $take2 */
        $take2 = OrderTake::factory()->create([
            'invoice_id' => Invoice::factory()->create(['order_id' => $take1->order_id]),
            'status_id'  => StatusType::ID_TAKE_CANCELED,
            'order_id'   => $take1->order_id
        ]);

        Carbon::setTestNow(Carbon::now());

        $this->get(
            route('order-take.take-info-by-order-id', [
                'orderId' => $take2->order_id
            ]),
        )
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'number',
                    'orderId',
                    'sender',
                    'courier',
                    'status',
                    'problems',
                    'statusHistory',
                    'waitListStatus',
                    'takes' => [
                        '*' => [
                            'takeId',
                            'invoiceId',
                            'invoiceNumber',
                            'receiverCity',
                            'statusName',
                            'statusId',
                            'sizeType',
                        ]
                    ],
                    'orderAdditionalServicesValues'
                ]
            ]);
    }

    public function testSetWaitListStatus()
    {
        $this->withoutExceptionHandling();

        Bus::fake([
            ChangeOrderTakeStatusInOneCCommand::class,
            SendOrderStatusToCabinetCommand::class,
        ]);

        Event::fake([
            SendWebNotificationEvent::class,
        ]);

        $courier = Courier::factory()->create();

        /** @var OrderTake $orderTake */
        $orderTake = OrderTake::factory()->create(['courier_id' => $courier->id]);

        DispatcherSector::factory()->create(['city_id' => $orderTake->city_id]);

        /** @var RefStatus $waitListStatus */
        $waitListStatus = RefStatus::factory()->create([
            'wait_list_type' => $this->faker->randomDigitNotNull,
        ]);

        $data = [
            'statusCode' => $waitListStatus->code,
            'comment'    => $this->faker->text,
            'takeId'     => $orderTake->id
        ];

        $response = $this->actingAs($courier->user)->put(route(
            'courier-app.order-take.set-wait-list-status',
            $orderTake->order_id,
        ), $data);

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Статус листа ожидание успешно присвоен!'
            ]);

        $this->assertDatabaseHas('order_takes', [
            'id'                  => $orderTake->id,
            'order_id'            => $orderTake->order_id,
            'wait_list_status_id' => $waitListStatus->id,
        ]);
    }

    public function testSetWaitListStatusToOrderTakes()
    {
        $this->withoutExceptionHandling();

        Bus::fake([
            ChangeOrderTakeStatusInOneCCommand::class,
            SendOrderStatusToCabinetCommand::class,
        ]);

        Event::fake([
            SendWebNotificationEvent::class,
        ]);

        $courier = Courier::factory()->create();

        /** @var Order $order */
        $order = Order::factory()->create();
        /** @var OrderTake $orderTake */
        $orderTake = OrderTake::factory(2)->create([
            'courier_id' => $courier->id,
        ]);

        /** @var RefStatus $waitListStatus */
        $waitListStatus = RefStatus::factory()->create([
            'wait_list_type' => $this->faker->randomDigitNotNull,
        ]);

        $data = [
            'statusCode' => $waitListStatus->code,
            'comment'    => $this->faker->text,
        ];

        $response = $this->actingAs($courier->user)->put(route(
            'courier-app.order-take.set-wait-list-status',
            $order->id,
        ), $data);

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Статус листа ожидание успешно присвоен!'
            ]);

        foreach ($order->orderTakes as $takeInfo) {
            $this->assertDatabaseHas('order_takes', [
                'id'                  => $takeInfo->id,
                'order_id'            => $takeInfo->order_id,
                'wait_list_status_id' => $waitListStatus->id,
            ]);
        }
    }

    public function testSetStatusByInvoice()
    {
        $invoice = Invoice::factory()->create();
        $take    = OrderTake::factory()->create([
            'invoice_id' => $invoice->id,
            'status_id'  => StatusType::ID_TAKE_CANCELED,
        ]);

        $data = [
            'invoiceId' => $invoice->id,
            'statusId'  => StatusType::ID_NOT_ASSIGNED,
        ];

        $this->putJson(route('order-take.set-status-by-invoice'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Успешно'
            ]);

        $this->assertDatabaseHas('order_takes', [
            'invoice_id' => $invoice->id,
            'status_id'  => StatusType::ID_NOT_ASSIGNED
        ]);
    }
}
