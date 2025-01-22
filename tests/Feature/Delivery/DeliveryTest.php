<?php

declare(strict_types=1);

namespace Tests\Feature\Delivery;

use App\Helpers\DateHelper;
use App\Libraries\Codes\ResponseCodes;
use App\Module\Delivery\Commands\CreateDeliveryCommand;
use App\Module\Delivery\Commands\SetDeliveryWaitListCommand;
use App\Module\Delivery\Commands\UpdateDeliveryCommand;
use App\Module\Delivery\DTO\DeliveryDTO;
use App\Module\Delivery\DTO\SetWaitListStatusDTO;
use App\Module\Delivery\Models\Delivery;
use App\Module\Delivery\Models\RouteSheet;
use App\Module\Delivery\Models\RouteSheetInvoice;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use App\Module\Take\DTO\CustomerDTO;
use App\Module\Take\Models\Customer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

final class DeliveryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testCreateDelivery()
    {
        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        Carbon::setTestNow(now());

        /** @var Customer $customer */
        $customer = Customer::factory()->make();

        $customerDTO                  = new CustomerDTO();
        $customerDTO->fullName        = $customer->full_name;
        $customerDTO->address         = $customer->address;
        $customerDTO->phone           = $customer->phone;
        $customerDTO->additionalPhone = $customer->additional_phone;
        $customerDTO->latitude        = $customer->latitude;
        $customerDTO->longitude       = $customer->longitude;

        /** @var RouteSheet $routeSheet */
        $routeSheet = RouteSheet::factory()->make([
            'id' => 1,
        ]);

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->make([
            'id' => 1,
        ]);

        $deliveryDTO                       = new DeliveryDTO();
        $deliveryDTO->internalId           = $delivery->internal_id;
        $deliveryDTO->invoiceId            = $delivery->invoice_id;
        $deliveryDTO->invoiceNumber        = $delivery->invoice_number;
        $deliveryDTO->companyId            = $delivery->company_id;
        $deliveryDTO->cityId               = $delivery->city_id;
        $deliveryDTO->courierId            = $delivery->courier_id;
        $deliveryDTO->statusId             = $this->faker->numberBetween(0, 5);
        $deliveryDTO->waitListStatusId     = $delivery->wait_list_status_id;
        $deliveryDTO->places               = $delivery->places;
        $deliveryDTO->weight               = $delivery->weight;
        $deliveryDTO->volume               = $delivery->volume;
        $deliveryDTO->volumeWeight         = $delivery->volume_weight;
        $deliveryDTO->deliveryReceiverName = $delivery->delivery_receiver_name;
        $deliveryDTO->courierComment       = $delivery->courier_comment;
        $deliveryDTO->deliveredAt          = $delivery->delivered_at;
        $deliveryDTO->createdAt            = $delivery->created_at;
        $deliveryDTO->customerDTO          = $customerDTO;
        $deliveryDTO->routeSheetId         = $routeSheet->number;

        dispatch(new CreateDeliveryCommand($deliveryDTO));

        $this->assertDatabaseHas('customers', [
            'full_name'        => $customer->full_name,
            'address'          => $customer->address,
            'phone'            => $customer->phone,
            'additional_phone' => $customer->additional_phone,
            'latitude'         => $customer->latitude,
            'longitude'        => $customer->longitude,
        ]);

        $this->assertDatabaseHas('deliveries', [
            'invoice_id'             => $delivery->invoice_id,
            'internal_id'            => $delivery->internal_id,
            'invoice_number'         => $delivery->invoice_number,
            'company_id'             => $delivery->company_id,
            'city_id'                => $delivery->city_id,
            'courier_id'             => $delivery->courier_id,
            'wait_list_status_id'    => $delivery->wait_list_status_id,
            'places'                 => $delivery->places,
            'weight'                 => $delivery->weight,
            'volume'                 => $delivery->volume,
            'volume_weight'          => $delivery->volume_weight,
            'delivered_at'           => $delivery->delivered_at,
            'courier_comment'        => $delivery->courier_comment,
            'delivery_receiver_name' => $delivery->delivery_receiver_name,
            'created_at'             => Carbon::parse($delivery->created_at),
        ]);

        $this->assertDatabaseHas('route_sheets', [
            'number'     => $deliveryDTO->routeSheetId,
            'status_id'  => RouteSheet::ID_IN_PROGRESS,
            'date'       => DateHelper::getDateWithTime(Carbon::parse($deliveryDTO->createdAt)),
            'courier_id' => $deliveryDTO->courierId,
            'city_id'    => $deliveryDTO->cityId,
        ]);

        $this->assertDatabaseHas('route_sheet_invoices', [
            'invoice_id'     => $deliveryDTO->invoiceId,
            'route_sheet_id' => $routeSheet->id,
        ]);
    }

    public function testUpdateDelivery()
    {
        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        Carbon::setTestNow(now());

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

        /** @var RouteSheet $routeSheet */
        $routeSheet = RouteSheet::factory()->create();

        /** @var Delivery $deliveryModel */
        $deliveryModel = Delivery::factory()->create([
            'customer_id'    => $customerModel->id,
        ]);

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->make();

        /** @var RouteSheetInvoice $routeSheetInvoice */
        $routeSheetInvoice = RouteSheetInvoice::factory()->create([
            'route_sheet_id' => $routeSheet->id,
            'invoice_id'     => $delivery->invoice_id,
        ]);

        $deliveryDTO                       = new DeliveryDTO();
        $deliveryDTO->internalId           = $deliveryModel->internal_id;
        $deliveryDTO->invoiceId            = $delivery->invoice_id;
        $deliveryDTO->invoiceNumber        = $delivery->invoice_number;
        $deliveryDTO->companyId            = $delivery->company_id;
        $deliveryDTO->cityId               = $delivery->city_id;
        $deliveryDTO->courierId            = $delivery->courier_id;
        $deliveryDTO->statusId             = $this->faker->numberBetween(0, 5);
        $deliveryDTO->waitListStatusId     = $delivery->wait_list_status_id;
        $deliveryDTO->places               = $delivery->places;
        $deliveryDTO->weight               = $delivery->weight;
        $deliveryDTO->volume               = $delivery->volume;
        $deliveryDTO->volumeWeight         = $delivery->volume_weight;
        $deliveryDTO->deliveryReceiverName = $delivery->delivery_receiver_name;
        $deliveryDTO->courierComment       = $delivery->courier_comment;
        $deliveryDTO->deliveredAt          = $delivery->delivered_at;
        $deliveryDTO->customerDTO          = $customerDTO;

        dispatch(new UpdateDeliveryCommand($deliveryDTO));

        $this->assertDatabaseHas('customers', [
            'id'               => $customerModel->id,
            'full_name'        => $customer->full_name,
            'address'          => $customer->address,
            'phone'            => $customer->phone,
            'additional_phone' => $customer->additional_phone,
            'latitude'         => $customer->latitude,
            'longitude'        => $customer->longitude,
        ]);

        $this->assertDatabaseHas('deliveries', [
            'id'                     => $deliveryModel->id,
            'invoice_id'             => $delivery->invoice_id,
            'internal_id'            => $deliveryModel->internal_id,
            'invoice_number'         => $delivery->invoice_number,
            'company_id'             => $delivery->company_id,
            'city_id'                => $delivery->city_id,
            'courier_id'             => $delivery->courier_id,
            'wait_list_status_id'    => $delivery->wait_list_status_id,
            'places'                 => $delivery->places,
            'weight'                 => $delivery->weight,
            'volume'                 => $delivery->volume,
            'volume_weight'          => $delivery->volume_weight,
            'delivered_at'           => $delivery->delivered_at,
            'courier_comment'        => $delivery->courier_comment,
            'delivery_receiver_name' => $delivery->delivery_receiver_name,
            'created_at'             => Carbon::parse($delivery->created_at)->format('Y-m-d H:i:s')
        ]);

        $this->assertDatabaseHas('route_sheets', [
            'courier_id' => $delivery->courier_id,
            'city_id'    => $delivery->city_id,
        ]);
    }

    public function testGetDeliveries()
    {
        $invoice = Invoice::factory()->create([
            'status_id' => RefStatus::ID_CHANGE_TAKE_DATE
        ]);

        OrderStatus::factory()->create([
            'invoice_id' => $invoice->id,
            'code' => RefStatus::CODE_COURIER_RETURN_DELIVERY
        ]);

        /** @var Delivery $delivery */
        $deliveries = Delivery::factory()->count(5)->create([
            'status_id'  => StatusType::ID_DATE_CHANGE,
            'invoice_id' => $invoice->id
        ]);

        $response = $this->get(route('delivery.index'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'invoiceNumber',
                        'latitude',
                        'longitude',
                        'problems',
                        'status' => [
                            'id',
                            'title',
                        ],
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $deliveries->sortByDesc('id')->pluck('id')->toArray());
    }

    public function testDeliveriesReport()
    {
        /** @var Delivery $delivery */
        $deliveries = Delivery::factory()->count(5)->create([
            'status_id'  => StatusType::ID_IN_DELIVERING,
            'invoice_id' => Invoice::factory()->create()
        ]);

        $response = $this->get(route('delivery.report'));

        $response->assertStatus(ResponseCodes::SUCCESS)->assertDownload();
    }

    public function testSetWaitListStatus()
    {
        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create();

        $dto = new SetWaitListStatusDTO();
        $dto->internalId = $delivery->internal_id;
        $dto->statusId   = $this->faker->numberBetween(1, 10);

        dispatch(new SetDeliveryWaitListCommand($dto));

        $this->assertDatabaseHas('deliveries', [
            'id'                     => $delivery->id,
            'invoice_id'             => $delivery->invoice_id,
            'internal_id'            => $delivery->internal_id,
            'invoice_number'         => $delivery->invoice_number,
            'wait_list_status_id'    => $dto->statusId,
        ]);
    }

    public function testSetDeliveryStatusByOrderStatus()
    {
        $this->mock(SendPushNotificationRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        });

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create();

        /** @var OrderStatus $status */
        $status = OrderStatus::factory()->make(['invoice_id' => $delivery->invoice_id]);

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $status->id;
        $dto->invoiceId     = $status->invoice_id;
        $dto->invoiceNumber = $status->invoice_number;
        $dto->orderId       = $status->order_id;
        $dto->title         = $status->title;
        $dto->code          = $this->faker->randomElement(RefStatus::DELIVERY_CANCEL_STATUSES);
        $dto->comment       = $status->comment;
        $dto->sourceId      = $status->source_id;
        $dto->userId        = $status->user_id;
        $dto->createdAt     = \Illuminate\Support\Carbon::now();

        dispatch(new CreateOrderStatusCommand($dto));

        $this->assertDatabaseHas('order_statuses', [
            'id'             => $dto->id,
            'invoice_id'     => $dto->invoiceId,
            'invoice_number' => $dto->invoiceNumber,
            'order_id'       => $dto->orderId,
            'code'           => $dto->code,
            'title'          => $dto->title,
            'comment'        => $dto->comment,
            'source_id'      => $dto->sourceId,
            'user_id'        => $dto->userId,
            'created_at'     => $dto->createdAt,
        ]);

        $this->assertDatabaseHas('deliveries', [
            'id'        => $delivery->id,
            'status_id' => StatusType::ID_TAKE_CANCELED
        ]);
    }

    public function testUpdateDeliveryWithStatuses()
    {
        $this->mock(DispatcherSectorPolygonQuery::class, mock: function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        $this->mock(SectorPolygonQuery::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByCoordinates');
        });

        Carbon::setTestNow(now());

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

        /** @var RouteSheet $routeSheet */
        $routeSheet = RouteSheet::factory()->create();

        /** @var Delivery $deliveryModel */
        $deliveryModel = Delivery::factory()->create([
            'customer_id'    => $customerModel->id,
        ]);

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->make();

        /** @var RouteSheetInvoice $routeSheetInvoice */
        $routeSheetInvoice = RouteSheetInvoice::factory()->create([
            'route_sheet_id' => $routeSheet->id,
            'invoice_id'     => $delivery->invoice_id,
        ]);

        $deliveryDTO                       = new DeliveryDTO();
        $deliveryDTO->internalId           = $deliveryModel->internal_id;
        $deliveryDTO->invoiceId            = $delivery->invoice_id;
        $deliveryDTO->invoiceNumber        = $delivery->invoice_number;
        $deliveryDTO->companyId            = $delivery->company_id;
        $deliveryDTO->cityId               = $delivery->city_id;
        $deliveryDTO->courierId            = $delivery->courier_id;
        $deliveryDTO->statusId             = $this->faker->randomElement([0,4]);
        $deliveryDTO->waitListStatusId     = $delivery->wait_list_status_id;
        $deliveryDTO->places               = $delivery->places;
        $deliveryDTO->weight               = $delivery->weight;
        $deliveryDTO->volume               = $delivery->volume;
        $deliveryDTO->volumeWeight         = $delivery->volume_weight;
        $deliveryDTO->deliveryReceiverName = $delivery->delivery_receiver_name;
        $deliveryDTO->courierComment       = $delivery->courier_comment;
        $deliveryDTO->deliveredAt          = $delivery->delivered_at;
        $deliveryDTO->customerDTO          = $customerDTO;

        dispatch(new UpdateDeliveryCommand($deliveryDTO));

        $this->assertDatabaseHas('customers', [
            'id'               => $customerModel->id,
            'full_name'        => $customer->full_name,
            'address'          => $customer->address,
            'phone'            => $customer->phone,
            'additional_phone' => $customer->additional_phone,
            'latitude'         => $customer->latitude,
            'longitude'        => $customer->longitude,
        ]);

        $this->assertDatabaseHas('deliveries', [
            'id'                     => $deliveryModel->id,
            'invoice_id'             => $delivery->invoice_id,
            'internal_id'            => $deliveryModel->internal_id,
            'invoice_number'         => $delivery->invoice_number,
            'company_id'             => $delivery->company_id,
            'city_id'                => $delivery->city_id,
            'courier_id'             => $delivery->courier_id,
            'wait_list_status_id'    => $delivery->wait_list_status_id,
            'places'                 => $delivery->places,
            'weight'                 => $delivery->weight,
            'volume'                 => $delivery->volume,
            'volume_weight'          => $delivery->volume_weight,
            'delivered_at'           => $delivery->delivered_at,
            'courier_comment'        => $delivery->courier_comment,
            'delivery_receiver_name' => $delivery->delivery_receiver_name,
            'created_at'             => Carbon::parse($delivery->created_at)->format('Y-m-d H:i:s')
        ]);

        $this->assertDatabaseHas('route_sheets', [
            'courier_id' => $delivery->courier_id,
            'city_id'    => $delivery->city_id,
            'status_id'  => RouteSheet::ID_COMPLETED
        ]);

        $this->assertDatabaseHas('route_sheet_invoices', [
            'route_sheet_id' => $routeSheet->id,
            'invoice_id'     => $delivery->invoice_id,
        ]);
    }
}
