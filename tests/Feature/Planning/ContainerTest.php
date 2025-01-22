<?php

declare(strict_types=1);

namespace Tests\Feature\Planning;

use App\Helpers\DateHelper;
use App\Libraries\Codes\ResponseCodes;
use App\Module\Courier\Models\Courier;
use App\Module\DispatcherSector\Contracts\Queries\HttpWarehouseQuery;
use App\Module\DispatcherSector\DTO\WarehouseDTO;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Commands\CreateFastDeliveryOrderByContainerCommand;
use App\Module\Order\Contracts\Repositories\Integration\CreateFastDeliveryOrderRepository;
use App\Module\Order\Models\FastDeliveryOrder;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Receiver;
use App\Module\Planning\Contracts\Queries\PlanningQuery;
use App\Module\Planning\DTO\OnecContainerDTO;
use App\Module\Planning\Events\PartiallyAssembledInvoicesNotificationEvent;
use App\Module\Planning\Contracts\Repositories\Integration\SendToAssemblyRepository;
use App\Module\Planning\Exceptions\CannotDeleteContainerException;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerInvoice;
use App\Module\Planning\Models\ContainerInvoiceStatus;
use App\Module\Planning\Models\ContainerStatus;
use App\Module\Status\Models\RefStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\Fake\Repositories\FastDeliveryOrderFakerRepository;
use Tests\Fake\Repositories\WarehouseFakerQuery;
use Tests\TestCase;

final class ContainerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetContainers()
    {
        $containers = Container::factory(5)->create();

        $response = $this->get(route('container.index'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'invoiceQuantity',
                        'sectorId',
                        'sectorName',
                        'weight',
                        'volumeWeight',
                        'createdAt',
                        'courierId',
                        'courierName',
                        'userId',
                        'userName',
                        'date',
                        'waveId',
                        'cargoType',
                        'invoices',
                        'status',
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $containers->pluck('id')->toArray());
    }

    public function testGetContainersPaginated()
    {
        $containers = Container::factory(5)->create();

        $response = $this->get(route('container.paginated'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'invoiceQuantity',
                        'sectorId',
                        'sectorName',
                        'weight',
                        'volumeWeight',
                        'createdAt',
                        'courierId',
                        'courierName',
                        'userId',
                        'userName',
                        'date',
                        'waveId',
                        'cargoType',
                        'invoices',
                        'status',
                        'provider',
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $containers->reverse()->pluck('id')->toArray());
    }

    public function testGetContainerInvoice()
    {
        $container        = Container::factory()->create();
        $invoice          = Invoice::factory()->create();
        $containerInvoice = ContainerInvoice::factory()->create([
            'container_id' => $container->id,
            'invoice_id'   => $invoice->id
        ]);

        $response = $this->get(route('container.invoice', $container->invoices->first()->id));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'invoiceNumber',
                    'weight',
                    'volumeWeight',
                    'createdAt',
                    'deliveryDate',
                    'deliveryStatus',
                ],
            ]);
    }

    public function testContainersExport()
    {
        Container::factory(5)->create();

        $response = $this->get(route('container.export'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertDownload();
    }

    public function testGenerateContainer()
    {
        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();

        Carbon::setTestNow(now());
        $date = now();

        /** @var Wave $wave */
        $wave = Wave::factory()->create(['dispatcher_sector_id' => $dispatcher->id, 'from_time' => '07:00']);
        /** @var Sector $sector */
        $sector = Sector::factory()->create(['dispatcher_sector_id' => $dispatcher->id]);

        $invoices = Invoice::factory(10)->create([
            'wave_id'       => $wave->id,
            'delivery_date' => DateHelper::getDate($date),
            'receiver_id'   => Receiver::factory()->create([
                'dispatcher_sector_id' => $dispatcher->id,
                'sector_id'            => $sector->id,
            ]),
            'status_id'     => Arr::random([RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY])
        ])->each(function (Invoice $item) {
            InvoiceCargo::factory()->create(['invoice_id' => $item->id]);
        });

        // Делаем вид, что точка забора находится в секторе
        // Иначе в тестах будет вызываться MySQL функция "acos", которой нету в MySQLite
        $this->mock(PlanningQuery::class, function (MockInterface $mock) use ($invoices) {
            $mock->shouldReceive('getSectorInvoices')->andReturn(
                $invoices
            );
        });

        $this->post(route('container.generate', [
            'waveId'             => $wave->id,
            'date'               => DateHelper::getDate($date),
            'dispatcherSectorId' => $dispatcher->id,
            'sectorIds'          => [$sector->id]
        ]))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => 'Контейнеры сформированы!'
            ]);

        $this->assertDatabaseCount('containers', 2);
        $this->assertDatabaseCount('containers_invoices', 10);

        $this->assertDatabaseHas('containers', [
            'sector_id'  => $sector->id,
            'wave_id'    => $wave->id,
            'date'       => DateHelper::getDate($date),
            'cargo_type' => InvoiceCargo::TYPE_SMALL_CARGO,
        ]);

        $this->assertDatabaseHas('containers', [
            'sector_id'  => $sector->id,
            'wave_id'    => $wave->id,
            'date'       => DateHelper::getDate($date),
            'cargo_type' => InvoiceCargo::TYPE_OVERSIZE_CARGO,
        ]);

        $smallCargoContainer    = Container::query()->where('cargo_type', InvoiceCargo::TYPE_SMALL_CARGO)->first();
        $oversizeCargoContainer = Container::query()->where('cargo_type', InvoiceCargo::TYPE_OVERSIZE_CARGO)->first();

        $smallCargoInvoices    = $invoices->where('cargo_type', InvoiceCargo::TYPE_SMALL_CARGO);
        $oversizeCargoInvoices = $invoices->where('cargo_type', InvoiceCargo::TYPE_OVERSIZE_CARGO);

        foreach ($smallCargoInvoices as $invoice) {
            $this->assertDatabaseHas('containers_invoices', [
                'container_id' => $smallCargoContainer->id,
                'invoice_id'   => $invoice->id,
            ]);
        }

        foreach ($oversizeCargoInvoices as $invoice) {
            $this->assertDatabaseHas('containers_invoices', [
                'container_id' => $oversizeCargoContainer->id,
                'invoice_id'   => $invoice->id,
            ]);
        }
    }

    public function testCreateContainer()
    {
        /** @var Container $container */
        $container = Container::factory()->make();
        $invoices  = Invoice::factory()->count(5)->create();

        $data = [
            'waveId'     => $container->wave_id,
            'sectorId'   => $container->sector_id,
            'date'       => $container->date,
            'cargoType'  => $container->cargo_type,
            'invoiceIds' => $invoices->pluck('id')->toArray(),
        ];

        $response = $this->postJson(
            route('container.create'),
            $data
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Контейнер создан!"
            ]);

        $this->assertDatabaseHas('containers', [
            'wave_id'    => $container->wave_id,
            'sector_id'  => $container->sector_id,
            'date'       => $container->date,
            'cargo_type' => $container->cargo_type,
        ]);

        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            $this->assertDatabaseHas('containers_invoices', [
                'invoice_id' => $invoice->id,
            ]);
        }
    }

    public function testContainerAttachInvoices()
    {
        /** @var Container $container */
        $container = Container::factory()->create();
        $position  = 1;

        ContainerInvoice::factory()->create([
            'container_id' => $container->id,
            'position'     => $position
        ]);

        $invoices = Invoice::factory()->count(5)->create();

        $response = $this->postJson(
            route('container.attach-invoices', $container->id),
            [
                'invoiceIds' => $invoices->pluck('id')->toArray(),
            ]
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Накладные перенесены в контейнер!"
            ]);

        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            $this->assertDatabaseHas('containers_invoices', [
                'container_id' => $container->id,
                'invoice_id'   => $invoice->id,
                'position'     => ++$position,
            ]);
        }
    }

    public function testContainerDestroy()
    {
        /** @var Container $container */
        $container = Container::factory()->create();

        $response = $this->deleteJson(
            route('container.destroy', $container->id),
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Контейнер удален!"
            ]);

        $this->assertSoftDeleted('containers', [
            'id' => $container->id
        ]);
    }

    public function testContainerDestroyException()
    {
        $this->expectException(CannotDeleteContainerException::class);

        /** @var Container $container */
        $container = Container::factory()->create();
        $position  = 1;

        ContainerInvoice::factory()->create([
            'container_id' => $container->id,
            'position'     => $position
        ]);

        Invoice::factory()->count(5)->create();

        $this->deleteJson(
            route('container.destroy', $container->id),
        );
    }

    public function testChangeContainerStatuses()
    {
        Event::fake(PartiallyAssembledInvoicesNotificationEvent::class);
        Bus::fake([CreateFastDeliveryOrderByContainerCommand::class]);

        /** @var Container $container */
        $container = Container::factory()->create();
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();
        /** @var Invoice $invoice2 */
        $invoice2 = Invoice::factory()->create();

        ContainerInvoice::factory()->create([
            'container_id' => $container->id,
            'invoice_id'   => $invoice->id
        ]);

        ContainerInvoice::factory()->create([
            'container_id' => $container->id,
            'invoice_id'   => $invoice2->id
        ]);

        $response = $this->postJson(
            route('container.change-status'),
            [
                'containerId'       => $container->id,
                'containerStatusId' => ContainerStatus::ID_PARTIALLY_ASSEMBLED,
                'invoices'          => [
                    [
                        "invoiceNumber"   => $invoice->invoice_number,
                        "invoiceStatusId" => ContainerInvoiceStatus::ID_PARTIALLY_ASSEMBLED,
                        "placesQuantity"  => 5
                    ],
                    [
                        "invoiceNumber"   => $invoice2->invoice_number,
                        "invoiceStatusId" => ContainerInvoiceStatus::ID_ASSEMBLED,
                        "placesQuantity"  => 5
                    ]
                ]
            ]
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Статус контейнера был изменен!"
            ]);

        $this->assertDatabaseHas('containers', [
            'id'        => $container->id,
            'status_id' => ContainerStatus::ID_PARTIALLY_ASSEMBLED
        ]);

        $this->assertDatabaseHas('containers_invoices', [
            'container_id' => $container->id,
            'invoice_id'   => $invoice->id,
            'status_id'    => ContainerInvoiceStatus::ID_PARTIALLY_ASSEMBLED,
        ]);

        $this->assertDatabaseHas('containers_invoices', [
            'container_id' => $container->id,
            'invoice_id'   => $invoice2->id,
            'status_id'    => ContainerInvoiceStatus::ID_ASSEMBLED,
        ]);

        $this->assertDatabaseHas('invoices', [
            'place_quantity' => 5
        ]);
    }

    public function testContainerAssignCourier()
    {
        /** @var Courier $courier */
        $courier    = Courier::factory()->create();
        $containers = Container::factory()->count(2)->create();

        $response = $this->postJson(
            route('container.assign-courier'),
            [
                'courierId'    => $courier->id,
                'containerIds' => $containers->pluck('id')->toArray(),
            ]
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Курьер успешно назначен!"
            ]);

        /** @var Container $container */
        foreach ($containers as $container) {
            $this->assertDatabaseHas('containers', [
                'id'         => $container->id,
                'courier_id' => $courier->id,
                'status_id'  => ContainerStatus::ID_COURIER_APPOINTED,
            ]);
        }
    }

    public function testSendContainersToAssembly()
    {
        Bus::fake(CreateFastDeliveryOrderByContainerCommand::class);

        /** @var Courier $courier */
        $courier = Courier::factory()->create();
        /** @var Sector $sector */
        $sector = Sector::factory()->create();
        /** @var Wave $wave */
        $wave = Wave::factory()->create(['dispatcher_sector_id' => $sector->dispatcher_sector_id]);

        Carbon::setTestNow(now());
        $date = now();

        /** @var Container $container1 */
        $container1 = Container::factory()->create([
            'status_id'  => ContainerStatus::ID_COURIER_APPOINTED,
            'courier_id' => $courier->id,
            'wave_id'    => $wave->id,
            'date'       => DateHelper::getDate($date),
            'doc_number' => null
        ]);

        /** @var Container $container2 */
        $container2 = Container::factory()->create([
            'status_id'  => ContainerStatus::ID_COURIER_APPOINTED,
            'courier_id' => $courier->id,
            'wave_id'    => $wave->id,
            'date'       => DateHelper::getDate($date),
            'doc_number' => null
        ]);

        /** @var Container $container3 */
        $container3 = Container::factory()->create([
            'status_id'  => ContainerStatus::ID_CREATED,
            'wave_id'    => $wave->id,
            'date'       => DateHelper::getDate($date),
            'doc_number' => null
        ]);

        ContainerInvoice::factory(2)->create(['container_id' => $container1->id]);
        ContainerInvoice::factory(2)->create(['container_id' => $container2->id]);

        $docNumber = $this->faker->numerify('######');

        $oneCContainer1              = new OnecContainerDTO();
        $oneCContainer1->containerId = $container1->id;
        $oneCContainer1->docNumber   = $docNumber;
        $oneCContainer1->success     = true;
        $oneCContainer1->error       = '';

        $oneCContainer2              = new OnecContainerDTO();
        $oneCContainer2->containerId = $container2->id;
        $oneCContainer2->docNumber   = $docNumber;
        $oneCContainer2->success     = false;
        $oneCContainer2->error       = $this->faker->text;

        $data = collect([
            $oneCContainer1,
            $oneCContainer2
        ]);

        $this->mock(SendToAssemblyRepository::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('send')->once()->andReturn($data);
        });

        $response = $this->postJson(
            route('container.send-to-assembly'),
            [
                'waveId' => $wave->id,
                'date'   => DateHelper::getDate($date),
            ]
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Контейнеры успешно отправлены на сборку!"
            ]);

        $this->assertDatabaseHas('containers', [
            'id'         => $container1->id,
            'status_id'  => ContainerStatus::ID_SEND_TO_ASSEMBLY,
            'doc_number' => $oneCContainer1->docNumber
        ]);

        $this->assertDatabaseHas('containers', [
            'id'         => $container2->id,
            'status_id'  => ContainerStatus::ID_COURIER_APPOINTED,
            'doc_number' => null
        ]);

        $this->assertDatabaseHas('containers', [
            'id'         => $container3->id,
            'status_id'  => ContainerStatus::ID_CREATED,
            'doc_number' => null
        ]);
    }

    public function testCreateFastDeliveryOrderByContainer()
    {
        /** @var FastDeliveryOrder $fastDeliveryOrder */
        $fastDeliveryOrder = FastDeliveryOrder::factory()->create(['internal_id' => null]);
        $container         = $fastDeliveryOrder->container;

        $this->app->bind(HttpWarehouseQuery::class, function () use ($container) {
            $cityId = $container->sector->dispatcherSector->city_id;

            $DTO              = new WarehouseDTO();
            $DTO->id          = $this->faker->randomNumber();
            $DTO->title       = $this->faker->title;
            $DTO->cityId      = $cityId;
            $DTO->street      = $this->faker->streetName;
            $DTO->house       = $this->faker->title;
            $DTO->office      = $this->faker->title;
            $DTO->index       = $this->faker->title;
            $DTO->fullAddress = $this->faker->address;
            $DTO->latitude    = (string)$this->faker->latitude;
            $DTO->longitude   = (string)$this->faker->longitude;
            $DTO->fullName    = $this->faker->name;
            $DTO->phone       = $this->faker->phoneNumber;

            return new WarehouseFakerQuery($DTO);
        });

        $deliveryId = $this->faker->randomNumber();

        $this->app->bind(CreateFastDeliveryOrderRepository::class, function () use ($deliveryId) {
            return new FastDeliveryOrderFakerRepository($deliveryId);
        });

        dispatch(new CreateFastDeliveryOrderByContainerCommand($container->id));

        $this->assertDatabaseHas('fast_delivery_orders', [
            'container_id' => $container->id,
            'internal_id'  => $deliveryId
        ]);
    }
}
