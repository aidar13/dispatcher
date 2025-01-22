<?php

declare(strict_types=1);

namespace Tests\Feature\Order;

use App\Helpers\CargoHelper;
use App\Helpers\DateHelper;
use App\Libraries\Codes\ResponseCodes;
use App\Module\CourierApp\Commands\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCCommand;
use App\Module\Delivery\Models\Delivery;
use App\Module\DispatcherSector\Contracts\Queries\WaveQuery;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Commands\CreateInvoiceCommand;
use App\Module\Order\Commands\UpdateInvoiceCommand;
use App\Module\Order\Contracts\Repositories\Integration\UpdateInvoiceSectorsRepository;
use App\Module\Order\DTO\InvoiceDTO;
use App\Module\Order\DTO\InvoiceCargoDTO;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Order;
use App\Module\Order\Models\Receiver;
use App\Module\Order\Models\Sender;
use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Mockery\MockInterface;
use Tests\TestCase;

final class InvoiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        Carbon::setTestNow(now());
    }

    public function testCreateInvoice()
    {
        $this->mock(UpdateInvoiceSectorsRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')->once();
        });

        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->make();
        /** @var InvoiceCargo $invoiceCargo */
        $invoiceCargo = InvoiceCargo::factory()->make();

        $invoiceCargoDTO                = new InvoiceCargoDTO();
        $invoiceCargoDTO->invoiceId     = $this->faker->numberBetween(1);
        $invoiceCargoDTO->cargoName     = $invoiceCargo->cargo_name;
        $invoiceCargoDTO->productName   = $invoiceCargo->product_name;
        $invoiceCargoDTO->places        = $invoiceCargo->places;
        $invoiceCargoDTO->weight        = $invoiceCargo->weight;
        $invoiceCargoDTO->volume        = $invoiceCargo->volume;
        $invoiceCargoDTO->volumeWeight  = $invoiceCargo->volume_weight;
        $invoiceCargoDTO->width         = $invoiceCargo->width;
        $invoiceCargoDTO->height        = $invoiceCargo->height;
        $invoiceCargoDTO->depth         = $invoiceCargo->depth;
        $invoiceCargoDTO->codPayment    = $invoiceCargo->cod_payment;
        $invoiceCargoDTO->cargoSizeType = $invoiceCargo->size_type;
        $invoiceCargoDTO->cargoPackCode = $invoiceCargo->pack_code;
        $invoiceCargoDTO->annotation    = $invoiceCargo->annotation;
        $invoiceCargoDTO->createdAt     = Carbon::now();

        $dto                       = new InvoiceDTO();
        $dto->id                   = $invoiceCargoDTO->invoiceId;
        $dto->invoiceNumber        = $invoice->invoice_number;
        $dto->orderId              = $invoice->order_id;
        $dto->statusId             = $invoice->status_id;
        $dto->receiverId           = $invoice->receiver_id;
        $dto->directionId          = $invoice->direction_id;
        $dto->shipmentId           = $invoice->shipment_id;
        $dto->periodId             = $invoice->period_id;
        $dto->takeDate             = $invoice->take_date;
        $dto->takeTime             = $invoice->take_time;
        $dto->code1c               = $invoice->code_1c;
        $dto->dopInvoiceNumber     = $invoice->dop_invoice_number;
        $dto->cashSum              = $invoice->cash_sum;
        $dto->shouldReturnDocument = $invoice->should_return_document;
        $dto->weekendDelivery      = $invoice->weekend_delivery;
        $dto->verify               = $invoice->verify;
        $dto->type                 = $invoice->type;
        $dto->paymentType          = $invoice->payment_type;
        $dto->paymentMethod        = $invoice->payment_method;
        $dto->payerCompanyId       = $invoice->payer_company_id;
        $dto->createdAt            = Carbon::now();
        $dto->invoiceCargo         = $invoiceCargoDTO;
        $dto->cargoType            = CargoHelper::getTypeFromCargoDTO($invoiceCargoDTO);

        dispatch(new CreateInvoiceCommand($dto));

        $cargoType = CargoHelper::getType(
            $invoiceCargoDTO->depth,
            $invoiceCargoDTO->height,
            $invoiceCargoDTO->width,
            $invoiceCargoDTO->volume
        );

        $this->assertDatabaseHas('invoices', [
            'id'                     => $dto->id,
            'invoice_number'         => $dto->invoiceNumber,
            'order_id'               => $dto->orderId,
            'status_id'              => $dto->statusId,
            'receiver_id'            => $dto->receiverId,
            'direction_id'           => $dto->directionId,
            'shipment_id'            => $dto->shipmentId,
            'period_id'              => $dto->periodId,
            'take_date'              => $dto->takeDate,
            'take_time'              => $dto->takeTime,
            'code_1c'                => $dto->code1c,
            'dop_invoice_number'     => $dto->dopInvoiceNumber,
            'cash_sum'               => $dto->cashSum,
            'should_return_document' => $dto->shouldReturnDocument,
            'weekend_delivery'       => $dto->weekendDelivery,
            'verify'                 => $dto->verify,
            'type'                   => $dto->type,
            'created_at'             => $dto->createdAt,
            'payer_company_id'       => $dto->payerCompanyId,
            'cargo_type'             => $cargoType
        ]);

        $this->assertDatabaseHas('invoice_cargo', [
            'invoice_id'    => $dto->id,
            'cargo_name'    => $dto->invoiceCargo->cargoName,
            'product_name'  => $dto->invoiceCargo->productName,
            'places'        => $dto->invoiceCargo->places,
            'weight'        => $dto->invoiceCargo->weight,
            'volume'        => $dto->invoiceCargo->volume,
            'volume_weight' => $dto->invoiceCargo->volumeWeight,
            'width'         => $dto->invoiceCargo->width,
            'height'        => $dto->invoiceCargo->height,
            'depth'         => $dto->invoiceCargo->depth,
            'annotation'    => $dto->invoiceCargo->annotation,
            'created_at'    => $dto->invoiceCargo->createdAt,
        ]);
    }

    public function testUpdateInvoice()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        /** @var Invoice $invoiceModel */
        $invoiceModel = Invoice::factory()->create();
        /** @var InvoiceCargo $invoiceCargoModel */
        $invoiceCargoModel = InvoiceCargo::factory()->create(['invoice_id' => $invoiceModel->id]);

        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->make();
        /** @var InvoiceCargo $invoiceCargo */
        $invoiceCargo = InvoiceCargo::factory()->make();

        $invoiceCargoDTO                = new InvoiceCargoDTO();
        $invoiceCargoDTO->invoiceId     = $invoiceModel->id;
        $invoiceCargoDTO->cargoName     = $invoiceCargo->cargo_name;
        $invoiceCargoDTO->productName   = $invoiceCargo->product_name;
        $invoiceCargoDTO->places        = $invoiceCargo->places;
        $invoiceCargoDTO->weight        = $invoiceCargo->weight;
        $invoiceCargoDTO->volume        = $invoiceCargo->volume;
        $invoiceCargoDTO->volumeWeight  = $invoiceCargo->volume_weight;
        $invoiceCargoDTO->width         = $invoiceCargo->width;
        $invoiceCargoDTO->depth         = $invoiceCargo->depth;
        $invoiceCargoDTO->codPayment    = $invoiceCargo->cod_payment;
        $invoiceCargoDTO->height        = $invoiceCargo->height;
        $invoiceCargoDTO->annotation    = $invoiceCargo->annotation;
        $invoiceCargoDTO->cargoSizeType = $invoiceCargo->size_type;
        $invoiceCargoDTO->cargoPackCode = $invoiceCargo->pack_code;
        $invoiceCargoDTO->createdAt     = Carbon::now();

        $dto                       = new InvoiceDTO();
        $dto->id                   = $invoiceModel->id;
        $dto->invoiceNumber        = $invoice->invoice_number;
        $dto->orderId              = $invoice->order_id;
        $dto->statusId             = $invoice->status_id;
        $dto->receiverId           = $invoice->receiver_id;
        $dto->directionId          = $invoice->direction_id;
        $dto->shipmentId           = $invoice->shipment_id;
        $dto->periodId             = $invoice->period_id;
        $dto->takeDate             = $invoice->take_date;
        $dto->takeTime             = $invoice->take_time;
        $dto->code1c               = $invoice->code_1c;
        $dto->dopInvoiceNumber     = $invoice->dop_invoice_number;
        $dto->cashSum              = $invoice->cash_sum;
        $dto->shouldReturnDocument = $invoice->should_return_document;
        $dto->weekendDelivery      = $invoice->weekend_delivery;
        $dto->verify               = $invoice->verify;
        $dto->type                 = $invoice->type;
        $dto->paymentType          = $invoice->payment_type;
        $dto->paymentMethod        = $invoice->payment_method;
        $dto->payerCompanyId       = $invoice->payer_company_id;
        $dto->createdAt            = Carbon::now();
        $dto->invoiceCargo         = $invoiceCargoDTO;
        $dto->cargoType            = CargoHelper::getTypeFromCargoDTO($invoiceCargoDTO);

        dispatch(new UpdateInvoiceCommand($dto));

        $cargoType = CargoHelper::getType(
            $invoiceCargoDTO->depth,
            $invoiceCargoDTO->height,
            $invoiceCargoDTO->width,
            $invoiceCargoDTO->volume
        );

        $this->assertDatabaseHas('invoices', [
            'id'                     => $dto->id,
            'invoice_number'         => $dto->invoiceNumber,
            'order_id'               => $dto->orderId,
            'status_id'              => $dto->statusId,
            'receiver_id'            => $dto->receiverId,
            'direction_id'           => $dto->directionId,
            'shipment_id'            => $dto->shipmentId,
            'period_id'              => $dto->periodId,
            'take_date'              => $dto->takeDate,
            'take_time'              => $dto->takeTime,
            'code_1c'                => $dto->code1c,
            'dop_invoice_number'     => $dto->dopInvoiceNumber,
            'cash_sum'               => $dto->cashSum,
            'should_return_document' => $dto->shouldReturnDocument,
            'weekend_delivery'       => $dto->weekendDelivery,
            'verify'                 => $dto->verify,
            'type'                   => $dto->type,
            'created_at'             => $dto->createdAt,
            'payer_company_id'       => $dto->payerCompanyId,
            'cargo_type'             => $cargoType
        ]);

        $this->assertDatabaseHas('invoice_cargo', [
            'id'            => $invoiceCargoModel->id,
            'invoice_id'    => $dto->id,
            'cargo_name'    => $dto->invoiceCargo->cargoName,
            'places'        => $dto->invoiceCargo->places,
            'weight'        => $dto->invoiceCargo->weight,
            'volume'        => $dto->invoiceCargo->volume,
            'volume_weight' => $dto->invoiceCargo->volumeWeight,
            'width'         => $dto->invoiceCargo->width,
            'height'        => $dto->invoiceCargo->height,
            'depth'         => $dto->invoiceCargo->depth,
            'annotation'    => $dto->invoiceCargo->annotation,
        ]);
    }

    public function testUpdateInvoiceWaveId()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();
        InvoiceCargo::factory()->create(['invoice_id' => $invoice->id]);

        Wave::factory(4)->create(['dispatcher_sector_id' => $invoice->receiver->dispatcher_sector_id]);

        $code = Arr::random(RefStatus::WAVE_ASSIGNABLE_TO_INVOICE_STATUSES);
        $time = Carbon::parse(now());

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->unique()->numberBetween();
        $dto->invoiceId     = $invoice->id;
        $dto->invoiceNumber = $invoice->invoice_number;
        $dto->orderId       = $invoice->order_id;
        $dto->code          = $code;
        $dto->createdAt     = $time;
        $dto->title         = null;
        $dto->comment       = null;
        $dto->sourceId      = null;
        $dto->userId        = null;

        dispatch(new CreateOrderStatusCommand($dto));

        /** @var WaveQuery $waveQuery */
        $waveQuery = app(WaveQuery::class);
        $wave      =
            $waveQuery->getByDispatcherSectorIdAndTime($invoice->receiver->dispatcher_sector_id, $time)['wave'];

        $this->assertDatabaseHas('invoices', [
            'id'             => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
        ]);
    }

    public function testUpdateInvoiceDeliveryDate()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();
        $date    = $this->faker->date;

        $this->putJson(route('invoices.update-delivery-date', [
            'date' => $date,
            'id'   => $invoice->id
        ]))->assertJson([
            'message' => 'Дата доставки изменен!'
        ]);

        $this->assertDatabaseHas('invoices', [
            'id'            => $invoice->id,
            'delivery_date' => $date,
        ]);
    }

    public function testUpdateInvoiceWave()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();

        /** @var Wave $wave */
        $wave = Wave::factory()->create();

        $this->putJson(route('invoices.update-wave', [
            'id'     => $invoice->id,
            'waveId' => $wave->id
        ]))->assertJson([
            'message' => 'Волна изменена!'
        ]);

        $this->assertDatabaseHas('invoices', [
            'id'      => $invoice->id,
            'wave_id' => $wave->id,
        ]);
    }

    public function testSetInvoicesWave()
    {
        $invoices = Invoice::factory(4)->create();

        /** @var Wave $wave */
        $wave = Wave::factory()->create();

        $this->putJson(route('invoices.set-wave', [
            'invoiceIds' => $invoices->pluck('id')->toArray(),
            'waveId'     => $wave->id
        ]))->assertJson([
            'message' => 'Волна изменена!'
        ]);

        foreach ($invoices as $invoice) {
            $this->assertDatabaseHas('invoices', [
                'id'      => $invoice->id,
                'wave_id' => $wave->id,
            ]);
        }
    }

    public function testUpdateInvoiceWaitListId()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();

        $code       = Arr::random(array_keys(RefStatus::WAIT_LIST_STATUSES));
        $waitListId = RefStatus::WAIT_LIST_STATUSES[$code];

        $dto                = new CreateOrderStatusDTO();
        $dto->id            = $this->faker->unique()->numberBetween();
        $dto->invoiceId     = $invoice->id;
        $dto->invoiceNumber = $invoice->invoice_number;
        $dto->orderId       = $invoice->order_id;
        $dto->code          = $code;
        $dto->createdAt     = now();
        $dto->title         = null;
        $dto->comment       = null;
        $dto->sourceId      = null;
        $dto->userId        = null;

        dispatch(new CreateOrderStatusCommand($dto));

        $this->assertDatabaseHas('invoices', [
            'id'           => $invoice->id,
            'wait_list_id' => $waitListId,
        ]);
    }

    public function testInvoicesOnHold()
    {
        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();
        /** @var DispatcherSector $senderDispatcher */
        $senderDispatcher = DispatcherSector::factory()->create();

        Carbon::setTestNow(now());
        $date = now();

        /** @var Wave $wave */
        $wave = Wave::factory()->create(['dispatcher_sector_id' => $dispatcher->id, 'from_time' => '07:00']);
        /** @var Sector $sector */
        $sector = Sector::factory()->create(['dispatcher_sector_id' => $dispatcher->id]);

        $invoices = Invoice::factory(5)->create([
            'delivery_date' => DateHelper::getDate($date),
            'status_id'     => RefStatus::ID_CARGO_ARRIVED_CITY,
            'receiver_id'   => Receiver::factory()->create([
                'dispatcher_sector_id' => $dispatcher->id,
                'sector_id'            => $sector->id,
            ]),
            'order_id'      => Order::factory()->create([
                'sender_id' => Sender::factory()->create([
                    'dispatcher_sector_id' => $senderDispatcher->id
                ])
            ]),
            'wait_list_id'  => RefStatus::factory()->create(['id' => RefStatus::ID_ON_HOLD])
        ])->each(function (Invoice $item) {
            InvoiceCargo::factory()->create(['invoice_id' => $item->id]);
        });

        $response = $this->get(route('invoices.on-hold', [
            'waveId'             => $wave->id,
            'date'               => DateHelper::getDate($date),
            'dispatcherSectorId' => $dispatcher->id,
            'sectorIds'          => [$sector->id]
        ]))->assertStatus(ResponseCodes::SUCCESS);

        $response->assertJsonStructure([
            'data' => [
                'dispatcherSectorId',
                'date',
                'stopsCount',
                'invoicesCount',
                'places',
                'weight',
                'volumeWeight',
                'invoices' => [
                    '*' => [
                        'id',
                        'invoiceNumber',
                        'weight',
                        'places',
                        'volumeWeight',
                        'latitude',
                        'longitude',
                        'waitList' => ['id', 'name']
                    ]
                ],
            ],
        ])
            ->assertJsonPath('data.invoices.*.id', $invoices->sortBy(fn($item) => $item->id)->pluck('id')->toArray())
            ->assertJson([
                'data' => [
                    'dispatcherSectorId' => $dispatcher->id,
                    'date'               => DateHelper::getDate($date),
                    'invoicesCount'      => $invoices->count(),
                    'places'             => $invoices->sum(fn($item) => $item->cargo->places),
                    'weight'             => $invoices->sum(fn($item) => $item?->cargo?->weight),
                    'volumeWeight'       => $invoices->sum(fn($item) => floatval($item->cargo->volume_weight)),
                ]
            ]);
    }

    public function testGetInvoiceProblems()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create([
            'sla_date' => now()->subHour(),
        ]);

        $response = $this->get(route('invoices.problems', [
            'invoiceId' => $invoice->id,
        ]))->assertStatus(ResponseCodes::SUCCESS);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'invoiceNumber',
                'slaDate',
                'problems'
            ]
        ])
            ->assertJsonPath('data.id', $invoice->id)
            ->assertJson([
                'data' => [
                    'id'            => $invoice->id,
                    'invoiceNumber' => $invoice->invoice_number,
                ]
            ]);
    }

    public function testResendDeliveryStatus()
    {
        Bus::fake(ChangeDeliveryStatusInOneCCommand::class);

        /** @var Invoice $invoice */
        $invoice = Invoice::factory()->create();

        Delivery::factory()->count(5)->create([
            'status_id'  => StatusType::ID_DELIVERED,
            'invoice_id' => $invoice->id
        ]);

        $response = $this->post(route('invoices.resend-status-onec', [
            'invoiceId' => $invoice->id,
        ]))->assertStatus(ResponseCodes::SUCCESS);

        $response->assertJson([
            'message' => 'Статус успешно переотправлен!'
        ]);

        Bus::assertDispatched(ChangeDeliveryStatusInOneCCommand::class);
    }
}
