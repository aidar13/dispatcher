<?php

declare(strict_types=1);

namespace Tests\Feature\DispatcherSector;

use App\Helpers\PolygonHelper;
use App\Libraries\Codes\ResponseCodes;
use App\Models\User;
use App\Module\DispatcherSector\Commands\CreateDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Commands\DestroyDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Events\DefaultSectorCreatedEvent;
use App\Module\DispatcherSector\Events\DispatcherSectorUpdatedEvent;
use App\Module\DispatcherSector\Events\SectorDestroyedEvent;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class DispatcherSectorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetDispatcherSectors()
    {
        $dispatcher = DispatcherSector::factory(5)->create();

        $response = $this->get(route('dispatcher-sectors.index'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'coordinates',
                        'polygon',
                        'courierId',
                        'dispatcherIds',
                        'sectors' => [
                            '*' => [
                                'id',
                                'name',
                                'dispatcherSectorId',
                                'coordinates',
                                'polygon',
                                'color',
                            ]
                        ]
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $dispatcher->sortByDesc('id')->pluck('id')->toArray());
    }

    public function testGetAllDispatcherSectors()
    {
        $dispatcher = DispatcherSector::factory(5)->create();

        $response = $this->get(route('dispatcher-sectors.get-all'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'coordinates',
                        'polygon',
                        'courierId',
                        'dispatcherIds',
                        'sectors' => [
                            '*' => [
                                'id',
                                'name',
                                'dispatcherSectorId',
                                'coordinates',
                                'polygon',
                                'color',
                            ]
                        ]
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $dispatcher->sortByDesc('id')->pluck('id')->toArray());
    }

    public function testStoreDispatcherSector()
    {
        Bus::fake([
            CreateDispatcherSectorIntegrationCommand::class,
            CreateDispatcherSectorIntegrationCommand::class,
        ]);
        Event::fake([
            DefaultSectorCreatedEvent::class,
        ]);

        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->make();
        /** @var User $user */
        $user = User::factory()->create(['id' => $this->faker->numberBetween(10, 100)]);

        $data = [
            'name'              => $dispatcher->name,
            'cityId'            => $dispatcher->city_id,
            'description'       => $dispatcher->description,
            'deliveryManagerId' => $dispatcher->delivery_manager_id,
            'dispatcherIds'     => [$user->id],
            'coordinates'       => [
                [$this->faker->latitude, $this->faker->longitude],
                [$this->faker->latitude, $this->faker->longitude],
            ]
        ];

        $response = $this->postJson(
            route('dispatcher-sectors.store'),
            $data
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Сектор диспетчера создан!"
            ]);

        $this->assertDatabaseHas('dispatcher_sectors', [
            'name'                => $data['name'],
            'city_id'             => $data['cityId'],
            'description'         => $data['description'],
            'delivery_manager_id' => $data['deliveryManagerId'],
            'coordinates'         => json_encode($data['coordinates']),
            'polygon'             => PolygonHelper::getPolygonFromCoordinates($data['coordinates']),
        ]);

        $this->assertDatabaseHas('dispatcher_sector_users', [
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('sectors', [
            'name' => 'Неизвестный сектор'
        ]);

        Event::assertDispatched(DefaultSectorCreatedEvent::class);
    }

    public function testUpdateDispatcherSector()
    {
        Event::fake(DispatcherSectorUpdatedEvent::class);

        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(['id' => $this->faker->numberBetween(10, 100)]);

        $data = [
            'name'              => $dispatcher->name,
            'cityId'            => $dispatcher->city_id,
            'description'       => $dispatcher->description,
            'deliveryManagerId' => $dispatcher->delivery_manager_id,
            'dispatcherIds'     => [$user->id],
            'coordinates'       => [
                [$this->faker->latitude, $this->faker->longitude],
                [$this->faker->latitude, $this->faker->longitude],
            ],
        ];

        $response = $this->put(
            route('dispatcher-sectors.update', [$dispatcher->id]),
            $data
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Сектор диспетчера обновлен!"
            ]);

        $this->assertDatabaseHas('dispatcher_sectors', [
            'name'                => $data['name'],
            'city_id'             => $data['cityId'],
            'description'         => $data['description'],
            'delivery_manager_id' => $data['deliveryManagerId'],
            'coordinates'         => json_encode($data['coordinates']),
            'polygon'             => PolygonHelper::getPolygonFromCoordinates($data['coordinates']),
        ]);

        $this->assertDatabaseHas('dispatcher_sector_users', [
            'dispatcher_sector_id' => $dispatcher->id,
            'user_id'              => $user->id
        ]);
    }

    public function testDeleteDispatcherSector()
    {
        Bus::fake(DestroyDispatcherSectorIntegrationCommand::class);
        Event::fake(SectorDestroyedEvent::class);

        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();
        $sector     = Sector::factory()->create(['dispatcher_sector_id' => $dispatcher->id]);

        $response = $this->delete(
            route('dispatcher-sectors.destroy', [$dispatcher->id])
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Сектор диспетчера удален!"
            ]);

        $this->assertSoftDeleted('dispatcher_sectors', [
            'id' => $dispatcher->id,
        ]);

        $this->assertSoftDeleted('sectors', [
            'id' => $sector->id,
        ]);

        Bus::assertDispatched(DestroyDispatcherSectorIntegrationCommand::class);
    }
}
