<?php

declare(strict_types=1);

namespace Tests\Feature\DispatcherSector;

use App\Helpers\DateHelper;
use App\Libraries\Codes\ResponseCodes;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Order;
use App\Module\Order\Models\Receiver;
use App\Module\Order\Models\Sender;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class WaveTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetWavesSectors()
    {
        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();

        $waves = Wave::factory(5)->create([
            'dispatcher_sector_id' => $dispatcher->id
        ])->each(function ($item) {
            Invoice::factory(5)->create(['wave_id' => $item->id]);
        });

        $response = $this->get(route('waves.index', ['dispatcherSectorId' => $dispatcher->id]));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'dispatcherSectorId',
                        'fromTime',
                        'toTime',
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $waves->pluck('id')->toArray());
    }

    public function testGetWaveInvoices()
    {
        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();
        /** @var DispatcherSector $senderDispatcher */
        $senderDispatcher = DispatcherSector::factory()->create();

        $wave = Wave::factory()->create(['dispatcher_sector_id' => $dispatcher->id]);

        $date = now()->hour >= Wave::NEXT_DAY_PLANNING_TIME ? now()->addDay() : now();

        $invoices = Invoice::factory(5)->create([
            'status_id'     => RefStatus::ID_CARGO_ARRIVED_CITY,
            'wave_id'       => $wave->id,
            'delivery_date' => DateHelper::getDate($date),
            'receiver_id'   => Receiver::factory()->create(['dispatcher_sector_id' => $dispatcher->id]),
            'order_id' => Order::factory()->create([
                'sender_id' => Sender::factory()->create([
                    'dispatcher_sector_id' => $senderDispatcher->id
                ])
            ]),
        ])->each(function ($item) {
            InvoiceCargo::factory()->create(['invoice_id' => $item->id]);
            OrderStatus::factory()->create([
                'invoice_id' => $item->id,
                'code'       => RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY
            ]);
        });

        $response = $this->get(route('waves.invoices', [
            'dispatcherSectorId' => $dispatcher->id,
            'id'                 => $wave->id
        ]));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'invoicesCount',
                    'stopsCount',
                    'weight',
                    'weight',
                    'volumeWeight',
                    'sectors'  => [
                        '*' => ['id', 'name']
                    ],
                    'invoices' => [
                        '*' => [
                            'id',
                            'invoiceNumber',
                            'receiverSector',
                            'weight',
                            'volumeWeight',
                            'timer',
                            'status',
                            'hasAdditionalService',
                        ]
                    ],
                ],
            ])
            ->assertJson([
                'data' => [
                    'id'            => $wave->id,
                    'invoicesCount' => $invoices->count(),
                    'weight'        => $invoices->sum(fn($item) => $item?->cargo?->weight),
                    'volumeWeight'  => $invoices->sum(fn($item) => $item?->cargo?->volume_weight),
                ]
            ]);
    }

    public function testGetWaveById()
    {
        $wave = Wave::factory()->create();

        $this->get(route('waves.show', ['id' => $wave->id]))
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'dispatcherSectorId',
                    'fromTime',
                    'toTime',
                ],
            ])
            ->assertJsonPath('data.id', $wave->id);
    }

    public function testStoreWave()
    {
        /** @var Wave $wave */
        $wave = Wave::factory()->make();

        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();

        $data = [
            'title'              => $wave->title,
            'dispatcherSectorId' => $dispatcher->id,
            'fromTime'           => $wave->from_time,
            'toTime'             => $wave->to_time,
        ];

        $response = $this->postJson(
            route('waves.store'),
            $data
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Волна создан!"
            ]);

        $this->assertDatabaseHas('waves', [
            'title'                => $wave->title,
            'dispatcher_sector_id' => $dispatcher->id,
            'from_time'            => $wave->from_time,
            'to_time'              => $wave->to_time,
        ]);
    }

    public function testUpdateWave()
    {
        /** @var Wave $wave */
        $wave = Wave::factory()->create();

        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();

        /** @var Wave $waveTemplate */
        $waveTemplate = Wave::factory()->make();

        $data = [
            'dispatcherSectorId' => $dispatcher->id,
            'title'              => $waveTemplate->title,
            'fromTime'           => $waveTemplate->from_time,
            'toTime'             => $waveTemplate->to_time,
        ];

        $response = $this->putJson(
            route('waves.update', [$wave->id]),
            $data
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Волна обновлен!"
            ]);

        $this->assertDatabaseHas('waves', [
            'title'                => $waveTemplate->title,
            'dispatcher_sector_id' => $dispatcher->id,
            'from_time'            => $waveTemplate->from_time,
            'to_time'              => $waveTemplate->to_time,
        ]);
    }

    public function testDeleteWave()
    {
        /** @var Wave $wave */
        $wave = Wave::factory()->create();

        $response = $this->delete(
            route('waves.destroy', [$wave->id])
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Волна удален!"
            ]);

        $this->assertSoftDeleted('waves', [
            'id' => $wave->id,
        ]);
    }
}
