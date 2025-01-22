<?php

declare(strict_types=1);

namespace Tests\Feature\Delivery;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Courier\Models\Courier;
use App\Module\Delivery\Contracts\Repositories\Integration\CreateDeliveriesInCabinetRepository;
use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

final class RouteSheetTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetRouteSheetIndex()
    {
        /** @var RouteSheet $routeSheet */
        $routeSheet = RouteSheet::factory(5)->create();

        $this->get(route('route-sheet.index'))
            ->assertJsonStructure([
                'data'  => [
                    '*' => [
                        'id',
                        'date',
                        'number',
                        'courier' => [
                            'id',
                            'fullName',
                            'phoneNumber',
                            'iin',
                        ],
                        'city' => [
                            'id',
                            'name',
                        ],
                        'sectors',
                        'waves',
                        'invoicesCount',
                        'placesCount',
                        'weightSum',
                        'volumeWeightSum',
                        'status',
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $routeSheet->sortByDesc('id')->pluck('id')->toArray());
    }

    public function testGetRouteSheetShow()
    {
        /** @var RouteSheet $routeSheet */
        $routeSheet = RouteSheet::factory()->create();

        $this->get(route('route-sheet.show', $routeSheet->id))
            ->assertJsonStructure([
                'data'  => [
                    'id',
                    'date',
                    'number',
                    'invoices' => [
                        '*' => [
                            'invoiceId',
                            'deliveredDate',
                            'invoiceNumber',
                            'courierReturnDate',
                            'cityName',
                            'sectorName',
                            'address',
                            'comment',
                            'waveName',
                            'receiverName',
                            'places',
                            'weight',
                            'volumeWeight',
                            'companyName',
                        ],
                    ],
                ]
            ]);
    }

    public function testRouteSheetReport()
    {
        /** @var RouteSheet $routeSheet */
        $routeSheet = RouteSheet::factory()->create();

        $response = $this->get(route('route-sheet.report', $routeSheet->id));

        $response->assertStatus(ResponseCodes::SUCCESS)->assertDownload();
    }

    public function testStoreRouteSheetFrom1C()
    {
        $this->mock(CreateDeliveriesInCabinetRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('createDeliveries')->once();
        });

        /** @var RouteSheet $routeSheet */
        $routeSheet = RouteSheet::factory()->make();

        $data = [
            'routeSheetNumber' => $routeSheet->number,
            'courierId'        => $routeSheet->courier_id,
        ];

        $this->postJson(route('one-c.route-sheet.store'), $data);

        $this->assertDatabaseHas('route_sheets', [
            'number'     => $data['routeSheetNumber'],
            'status_id'  => RouteSheet::ID_IN_PROGRESS,
            'courier_id' => $routeSheet->courier_id,
            'city_id'    => $routeSheet->city_id,
        ]);
    }
}
