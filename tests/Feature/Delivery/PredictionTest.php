<?php

declare(strict_types=1);

namespace Tests\Feature\Delivery;

use App\Helpers\DateHelper;
use App\Libraries\Codes\ResponseCodes;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Receiver;
use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\DTO\Integration\CreateOrderStatusDTO;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class PredictionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetPredictionReport()
    {
        Event::fake(OrderStatusCreatedEvent::class);

        Carbon::setTestNow(now()->setTime(4, 0));

        $date = now()->hour >= Wave::NEXT_DAY_PLANNING_TIME
            ? now()->addDay()
            : now();

        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();

        /** @var Wave $wave */
        $wave = Wave::factory()->create(['dispatcher_sector_id' => $dispatcher->id, 'from_time' => '07:00']);

        $factInvoices = Invoice::factory(5)->create([
            'wave_id'     => $wave->id,
            'receiver_id' => Receiver::factory()->create(['dispatcher_sector_id' => $dispatcher->id]),
            'status_id'   => Arr::random([RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY])
        ])->each(function (Invoice $item) {
            InvoiceCargo::factory()->create(['invoice_id' => $item->id]);
        });

        $incomingInvoices = Invoice::factory(5)->create([
            'wave_id'     => $wave->id,
            'receiver_id' => Receiver::factory()->create(['dispatcher_sector_id' => $dispatcher->id]),
            'status_id'   => RefStatus::ID_CARGO_IN_TRANSIT
        ])->each(function (Invoice $item) use ($date) {
            InvoiceCargo::factory()->create(['invoice_id' => $item->id]);

            $dto                = new CreateOrderStatusDTO();
            $dto->id            = $this->faker->numberBetween(0, 100);
            $dto->invoiceId     = $item->id;
            $dto->invoiceNumber = $item->invoice_number;
            $dto->orderId       = $item->order_id;
            $dto->title         = $this->faker->title;
            $dto->code          = RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY;
            $dto->comment       = $this->faker->title;
            $dto->sourceId      = null;
            $dto->userId        = null;
            $dto->createdAt     = $date->setTime(4, 0);

            dispatch(new CreateOrderStatusCommand($dto));
        });

        $this->get(route('prediction.index', ['dispatcherSectorId' => $dispatcher->id]))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'dispatcherSectorId',
                    'date',
                    'factCount',
                    'factWeight',
                    'factVolumeWeight',
                    'incomingCount',
                    'incomingWeight',
                    'incomingVolumeWeight',
                ],
            ])
            ->assertJson([
                'data' => [
                    'dispatcherSectorId'   => $dispatcher->id,
                    'date'                 => DateHelper::getDate($date),
                    'factCount'            => $factInvoices->count(),
                    'factWeight'           => floatval($factInvoices->sum(fn($item) => $item->cargo?->weight)),
                    'factVolumeWeight'     => floatval($factInvoices->sum(fn($item) => $item->cargo?->volume_weight)),
                ]]);
    }

    public function testGetCarPredictionReport()
    {
        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();

        /** @var Wave $wave */
        $wave = Wave::factory()->create(['dispatcher_sector_id' => $dispatcher->id, 'from_time' => '07:00']);

        Invoice::factory(5)->create([
            'wave_id'     => $wave->id,
            'receiver_id' => Receiver::factory()->create(['dispatcher_sector_id' => $dispatcher->id]),
            'status_id'   => Arr::random([RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY])
        ])->each(function (Invoice $item) {
            InvoiceCargo::factory()->create(['invoice_id' => $item->id]);
        });

        $response = $this->get(route('prediction.cars', ['dispatcherSectorId' => $dispatcher->id]))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'dispatcherSectorId',
                    'date',
                    'truck' => [
                        'autoCount',
                        'stopsCount',
                        'weight',
                        'volumeWeight',
                    ],
                    'cars' =>
                        [
                            '*' => [
                                'waveId',
                                'waveTitle',
                                'sectorId',
                                'sectorName',
                                'invoicesCount',
                                'stopsCount',
                                'weight',
                                'volumeWeight',
                                'truck' => [
                                    'invoicesCount',
                                    'carCount',
                                    'stopsCount',
                                    'weight',
                                    'volumeWeight',
                                ],
                                'passanger' => [
                                    'invoicesCount',
                                    'carCount',
                                    'stopsCount',
                                    'weight',
                                    'volumeWeight',
                                ],
                            ],
                        ],
                ],
            ]);
    }
}
