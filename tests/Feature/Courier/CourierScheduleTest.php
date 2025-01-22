<?php

declare(strict_types=1);

namespace Tests\Feature\Courier;

use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CourierScheduleTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetCourierSchedules()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        CourierSchedule::factory()->count(5)->create([
            'courier_id' => $courier->id
        ]);

        $this->get(route('courier-schedule.show', $courier->id))
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'weekday',
                        'workTimeUntil',
                        'workTimeFrom'
                    ]
                ],
            ]);
    }

    public function testCreateCourierSchedules()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $data = [
            'courierId' => $courier->id,
            'schedules' => [
                [
                    "weekday"       => 0,
                    "workTimeFrom"  => "12:00",
                    "workTimeUntil" => "13:00"
                ],
                [
                    "weekday"       => 1,
                    "workTimeFrom"  => "12:00",
                    "workTimeUntil" => "13:00"
                ],
                [
                    "weekday"       => 2,
                    "workTimeFrom"  => "12:00",
                    "workTimeUntil" => "13:00"
                ],
                [
                    "weekday"       => 3,
                    "workTimeFrom"  => "12:00",
                    "workTimeUntil" => "13:00"
                ],
            ]
        ];

        $this->post(route('courier-schedule.store'), $data)
            ->assertStatus(200);
    }
}
