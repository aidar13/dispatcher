<?php

declare(strict_types=1);

namespace Tests\Feature\Planning;

use App\Helpers\DateHelper;
use App\Libraries\Codes\ResponseCodes;
use App\Module\Courier\Models\Courier;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Order;
use App\Module\Order\Models\Receiver;
use App\Module\Order\Models\Sender;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerInvoice;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class PlanningTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testPlanningIndex()
    {
        Event::fake(OrderStatusCreatedEvent::class);

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
            'wave_id'       => $wave->id,
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
            'wait_list_id' => RefStatus::factory()->create(['code' => RefStatus::CODE_CAR_BREAKDOWN_ON_DELIVERY])
        ])->each(function (Invoice $item) {
            InvoiceCargo::factory()->create(['invoice_id' => $item->id]);
        });

        $response = $this->get(route('planning.index', [
            'waveId'             => $wave->id,
            'date'               => DateHelper::getDate($date),
            'dispatcherSectorId' => $dispatcher->id,
            'sectorIds'          => [$sector->id]
        ]))->assertStatus(ResponseCodes::SUCCESS);

        $response->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'date',
                        'timeFrom',
                        'timeTo',
                        'stopsCount',
                        'invoicesCount',
                        'places',
                        'weight',
                        'volumeWeight',
                        'invoices'   => [
                            '*' => [
                                'id',
                                'invoiceNumber',
                                'weight',
                                'places',
                                'shortAddress',
                                'volumeWeight',
                                'latitude',
                                'longitude',
                                'problems',
                                'waitList' => ['id', 'name']
                            ]
                        ],
                        'containers' => [
                            '*' => [
                                'id',
                                'title',
                                'places',
                                'weight',
                                'volumeWeight',
                                'stopsCount',
                                'invoicesCount',
                                'fastDeliveryCourier',
                                'fastDeliveryId',
                                'invoices' => [
                                    '*' => [
                                        'id',
                                        'invoiceNumber',
                                        'weight',
                                        'places',
                                        'volumeWeight',
                                        'longitude',
                                        'latitude',
                                        'problems'
                                    ]
                                ],
                            ]
                        ]
                    ],
                ]
            ])
            ->assertJsonPath('data.0.invoices.*.id', $invoices->sortBy(fn($item) => $item->id)->pluck('id')->toArray())
            ->assertJson([
                'data' => [
                    '0' => [
                        'id'            => $sector->id,
                        'date'          => DateHelper::getDate($date),
                        'timeFrom'      => DateHelper::getTime(Carbon::make($wave->from_time)),
                        'timeTo'        => DateHelper::getTime(Carbon::make($wave->to_time)),
                        'invoicesCount' => $invoices->count(),
                        'places'        => $invoices->sum(fn($item) => $item->cargo->places),
                        'weight'        => $invoices->sum(fn($item) => $item?->cargo?->weight),
                        'volumeWeight'  => $invoices->sum(fn($item) => floatval($item->cargo->volume_weight)),
                    ]
                ]
            ]);
    }

    public function testCourierPlanningIndex()
    {
        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();
        /** @var DispatcherSector $senderDispatcher */
        $senderDispatcher = DispatcherSector::factory()->create();
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        Carbon::setTestNow(now());
        $date = now();

        /** @var Wave $wave */
        $wave = Wave::factory()->create(['dispatcher_sector_id' => $dispatcher->id, 'from_time' => '07:00']);
        /** @var Sector $sector */
        $sector = Sector::factory()->create(['dispatcher_sector_id' => $dispatcher->id]);

        Container::factory()->create([
            'sector_id'  => $sector->id,
            'wave_id'    => $wave->id,
            'courier_id' => $courier->id,
            'date'       => DateHelper::getDate($date),
        ]);

        Invoice::factory()->create([
            'wave_id'       => $wave->id,
            'delivery_date' => DateHelper::getDate($date),
            'receiver_id'   => Receiver::factory()->create([
                'dispatcher_sector_id' => $dispatcher->id,
                'sector_id'            => $sector->id,
            ]),
            'order_id'      => Order::factory()->create([
                'sender_id' => Sender::factory()->create([
                    'dispatcher_sector_id' => $senderDispatcher->id
                ])
            ]),
        ])->each(function (Invoice $item) {
            InvoiceCargo::factory()->create(['invoice_id' => $item->id]);
            ContainerInvoice::factory()->create(['invoice_id' => $item->id]);
        });

        $this->get(route('planning.courier.index', [
            'waveId'             => $wave->id,
            'date'               => DateHelper::getDate($date),
            'dispatcherSectorId' => $dispatcher->id
        ]))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'courierId',
                        'invoiceQuantity',
                        'fullness',
                        'schedule'   => [
                            'id',
                            'weekday',
                            'workTimeFrom',
                            'workTimeUntil',
                        ],
                        'sectors' => [
                            '*' => [
                                'id',
                                'name',
                                'dispatcherSectorId',
                                'coordinates',
                                'polygon',
                                'color'
                            ]
                        ]
                    ],
                ]
            ]);
    }
}
