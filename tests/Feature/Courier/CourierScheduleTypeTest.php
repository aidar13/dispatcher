<?php

declare(strict_types=1);

namespace Tests\Feature\Courier;

use App\Module\Courier\Models\CourierScheduleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CourierScheduleTypeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testGetCourierScheduleTypes()
    {
        CourierScheduleType::factory()->count(5)->create();

        $this->get(route('courier-schedule-types.index'))
            ->assertJsonStructure([
                'data'  => [
                    '*' => [
                        'id',
                        'workTimeUntil',
                        'workTimeFrom'
                    ]
                ],
            ]);
    }
}
