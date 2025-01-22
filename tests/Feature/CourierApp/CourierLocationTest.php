<?php

declare(strict_types=1);

namespace Tests\Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Contracts\Queries\CourierLocation\CourierLocationQuery;
use App\Module\CourierApp\Models\CourierLoaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Fake\Queries\FakeCourierLocationQuery;
use Tests\TestCase;

final class CourierLocationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testSaveCourierLocation()
    {
        $this->app->bind(CourierLocationQuery::class, function () {
            return new FakeCourierLocationQuery(null);
        });

        /** @var CourierLoaction $courierLocation */
        $courierLocation = CourierLoaction::factory()->make();

        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $data = [
            'latitude'  => $courierLocation->latitude,
            'longitude' => $courierLocation->longitude,
            'time'      => $this->faker->randomElement([
                null,
                $this->faker->time('Y-m-d H:s')
            ])
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.courier-locations.store'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Координаты успешно сохранены!"
            ]);

        $createdAt = $data['time']
            ? Carbon::parse($data['time'])
            : Carbon::now();

        $this->assertDatabaseHas($courierLocation->getTable(), [
            'courier_id' => $courier->id,
            'latitude'   => $data['latitude'],
            'longitude'  => $data['longitude'],
            'created_at' => $createdAt
        ]);
    }

    public function testSaveCourierLocationWithDowntime()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $downtime = $this->faker->numberBetween(1, 15);

        /** @var CourierLoaction $courierLocation1 */
        $courierLocation1 = CourierLoaction::factory()->create([
            'courier_id' => $courier->id,
            'created_at' => now()->subMinutes($downtime),
            'downtime'   => null
        ]);

        $this->app->bind(CourierLocationQuery::class, function () use ($courierLocation1) {
            return new FakeCourierLocationQuery($courierLocation1);
        });

        /** @var CourierLoaction $courierLocation2 */
        $courierLocation2 = CourierLoaction::factory()->make();

        $data = [
            'latitude'  => $courierLocation2->latitude,
            'longitude' => $courierLocation2->longitude,
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.courier-locations.store'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Координаты успешно сохранены!"
            ]);

        $this->assertDatabaseHas($courierLocation2->getTable(), [
            'courier_id' => $courier->id,
            'latitude'   => $data['latitude'],
            'longitude'  => $data['longitude'],
            'downtime'   => $downtime
        ]);
    }
}
