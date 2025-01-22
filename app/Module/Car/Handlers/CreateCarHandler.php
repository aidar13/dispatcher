<?php

declare(strict_types=1);

namespace App\Module\Car\Handlers;

use App\Module\Car\Commands\CreateCarCommand;
use App\Module\Car\Contracts\Queries\CarQuery;
use App\Module\Car\Contracts\Repositories\CreateCarRepository;
use App\Module\Car\Models\Car;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateCarHandler
{
    public function __construct(
        private readonly CreateCarRepository $createCarRepository,
        private readonly CarQuery $carQuery
    ) {
    }

    public function handle(CreateCarCommand $command): void
    {
        if ($this->carExists($command->DTO->id)) {
            return;
        }

        $car                  = new Car();
        $car->id              = $command->DTO->id;
        $car->status_id       = $command->DTO->statusId;
        $car->company_id      = $command->DTO->companyId;
        $car->vehicle_type_id = $command->DTO->vehicleTypeId;
        $car->code_1C         = $command->DTO->code1C;
        $car->number          = $command->DTO->number;
        $car->model           = $command->DTO->model;
        $car->cubature        = $command->DTO->cubature;
        $car->created_at      = $command->DTO->createdAt;

        $this->createCarRepository->create($car);
    }

    private function carExists(int $id): bool
    {
        try {
            $this->carQuery->getById($id);

            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
