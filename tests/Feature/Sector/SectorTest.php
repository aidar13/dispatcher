<?php

declare(strict_types=1);

namespace Tests\Feature\Sector;

use App\Helpers\PolygonHelper;
use App\Libraries\Codes\ResponseCodes;
use App\Module\DispatcherSector\Events\SectorCreatedEvent;
use App\Module\DispatcherSector\Events\SectorDestroyedEvent;
use App\Module\DispatcherSector\Events\SectorUpdatedEvent;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class SectorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetAllSectors()
    {
        $sectors = Sector::factory(5)->create();

        $response = $this->get(route('sectors.index'));

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'dispatcherSectorId',
                        'dispatcherSectorName',
                        'coordinates',
                        'polygon',
                        'color',
                    ]
                ],
            ])
            ->assertJsonPath('data.*.id', $sectors->sortByDesc('id')->pluck('id')->toArray());
    }

    public function testStoreSector()
    {
        Event::fake(SectorCreatedEvent::class);

        /** @var Sector $sector */
        $sector = Sector::factory()->make();

        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();

        $data = [
            'name'               => $sector->name,
            'dispatcherSectorId' => $dispatcher->id,
            'color'              => $sector->color,
            'coordinates'        => [
                [$this->faker->latitude, $this->faker->longitude],
                [$this->faker->latitude, $this->faker->longitude],
            ],
        ];

        $response = $this->postJson(
            route('sectors.store'),
            $data
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Сектор создан!"
            ]);

        $this->assertDatabaseHas('sectors', [
            'name'                 => $data['name'],
            'dispatcher_sector_id' => $data['dispatcherSectorId'],
            'color'                => $data['color'],
            'coordinates'          => json_encode($data['coordinates']),
            'polygon'              => PolygonHelper::getPolygonFromCoordinates($data['coordinates']),
        ]);
    }

    public function testUpdateSector()
    {
        Event::fake(SectorUpdatedEvent::class);

        /** @var Sector $sector */
        $sector = Sector::factory()->create();

        /** @var DispatcherSector $dispatcher */
        $dispatcher = DispatcherSector::factory()->create();

        $data = [
            'name'               => $sector->name,
            'dispatcherSectorId' => $dispatcher->id,
            'color'              => $sector->color,
            'coordinates'        => [
                [$this->faker->latitude, $this->faker->longitude],
                [$this->faker->latitude, $this->faker->longitude],
            ],
        ];

        $response = $this->putJson(
            route('sectors.update', [$sector->id]),
            $data
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Сектор обновлен!"
            ]);

        $this->assertDatabaseHas('sectors', [
            'name'                 => $data['name'],
            'dispatcher_sector_id' => $data['dispatcherSectorId'],
            'color'                => $data['color'],
            'coordinates'          => json_encode($data['coordinates']),
            'polygon'              => PolygonHelper::getPolygonFromCoordinates($data['coordinates']),
        ]);
    }

    public function testDeleteSector()
    {
        Event::fake(SectorDestroyedEvent::class);

        /** @var Sector $sector */
        $sector = Sector::factory()->create();

        $response = $this->delete(
            route('sectors.destroy', [$sector->id])
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Сектор удален!"
            ]);

        $this->assertSoftDeleted('sectors', [
            'id' => $sector->id,
        ]);
    }
}
