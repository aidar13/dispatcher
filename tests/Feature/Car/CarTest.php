<?php

declare(strict_types=1);

namespace Tests\Feature\Car;

use App\Module\Car\Commands\CreateCarCommand;
use App\Module\Car\Commands\UpdateCarCommand;
use App\Module\Car\DTO\CarDTO;
use App\Module\Car\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CarTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateCar()
    {
        /** @var Car $car */
        $car = Car::factory()->make();

        $dto                = new CarDTO();
        $dto->id            = $car->id;
        $dto->statusId      = $car->status_id;
        $dto->companyId     = $car->company_id;
        $dto->vehicleTypeId = $car->vehicle_type_id;
        $dto->code1C        = $car->code_1C;
        $dto->number        = $car->number;
        $dto->model         = $car->model;
        $dto->cubature      = $car->cubature;
        $dto->createdAt     = $car->created_at;

        dispatch(new CreateCarCommand($dto));

        $this->assertDatabaseHas('cars', [
            'id'              => $dto->id,
            'status_id'       => $dto->statusId,
            'company_id'      => $dto->companyId,
            'vehicle_type_id' => $dto->vehicleTypeId,
            'code_1C'         => $dto->code1C,
            'number'          => $dto->number,
            'model'           => $dto->model,
            'cubature'        => $dto->cubature,
            'created_at'      => $dto->createdAt,
            ]);
    }

    public function testUpdateCar()
    {
        /** @var Car $car */
        $car = Car::factory()->create();

        $dto                = new CarDTO();
        $dto->id            = $car->id;
        $dto->statusId      = $car->status_id;
        $dto->companyId     = $car->company_id;
        $dto->vehicleTypeId = $car->vehicle_type_id;
        $dto->code1C        = $car->code_1C;
        $dto->number        = $car->number;
        $dto->model         = $car->model;
        $dto->cubature      = $car->cubature;
        $dto->createdAt     = $car->created_at;

        dispatch(new UpdateCarCommand($dto));

        $this->assertDatabaseHas('cars', [
            'id'              => $dto->id,
            'status_id'       => $dto->statusId,
            'company_id'      => $dto->companyId,
            'vehicle_type_id' => $dto->vehicleTypeId,
            'code_1C'         => $dto->code1C,
            'number'          => $dto->number,
            'model'           => $dto->model,
            'cubature'        => $dto->cubature,
            'created_at'      => $dto->createdAt,
        ]);
    }
}
