<?php

declare(strict_types=1);

namespace Tests\Feature\Car;

use App\Module\Car\Commands\CreateCarOccupancyCommand;
use App\Module\Car\DTO\CarOccupancyDTO;
use App\Module\Car\Models\CarOccupancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CarOccupancyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateCarOccupancy()
    {
        /** @var CarOccupancy $carOccupancy */
        $carOccupancy = CarOccupancy::factory()->make(['id' => 4]);

        $dto                     = new CarOccupancyDTO();
        $dto->id                 = $carOccupancy->id;
        $dto->carOccupancyTypeId = $carOccupancy->car_occupancy_type_id;
        $dto->carId              = $carOccupancy->car_id;
        $dto->userId             = $carOccupancy->user_id;
        $dto->courierWorkTypeId  = $carOccupancy->type_id;
        $dto->clientType         = $carOccupancy->client_type;
        $dto->clientId           = $carOccupancy->client_id;
        $dto->createdAt          = $carOccupancy->created_at;

        dispatch(new CreateCarOccupancyCommand($dto));

        $this->assertDatabaseHas('car_occupancies', [
            'id'                    => $dto->id,
            'car_occupancy_type_id' => $dto->carOccupancyTypeId,
            'car_id'                => $dto->carId,
            'user_id'               => $dto->userId,
            'type_id'               => $dto->courierWorkTypeId,
            'client_type'           => $dto->clientType,
            'client_id'             => $dto->clientId,
            'created_at'            => $dto->createdAt,
        ]);
    }
}
