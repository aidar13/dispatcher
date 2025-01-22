<?php

declare(strict_types=1);

namespace Tests\Feature\Courier;

use App\Module\Courier\Commands\Integration\CreateCourierStopCommand;
use App\Module\Courier\DTO\Integration\CourierStopDTO;
use App\Module\CourierApp\Models\CourierStop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CourierStopTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = $this->faker('kk_KZ');
        $this->withoutExceptionHandling();
    }

    public function testCreateCourierStop()
    {
        /** @var CourierStop $courierStop */
        $courierStop = CourierStop::factory()->make();

        $dto             = new CourierStopDTO();
        $dto->id         = $courierStop->id;
        $dto->courierId  = $courierStop->courier_id;
        $dto->clientId   = $courierStop->client_id;
        $dto->clientType = $courierStop->client_type;

        dispatch(new CreateCourierStopCommand($dto));

        $this->assertDatabaseHas('courier_stops', [
            'id'          => $courierStop->id,
            'courier_id'  => $courierStop->courier_id,
            'client_id'   => $courierStop->client_id,
            'client_type' => $courierStop->client_type,
        ]);
    }
}
