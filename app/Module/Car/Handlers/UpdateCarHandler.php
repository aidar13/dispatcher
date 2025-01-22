<?php

declare(strict_types=1);

namespace App\Module\Car\Handlers;

use App\Module\Car\Commands\UpdateCarCommand;
use App\Module\Car\Contracts\Queries\CarQuery;
use App\Module\Car\Contracts\Repositories\UpdateCarRepository;

final class UpdateCarHandler
{
    public function __construct(
        private readonly CarQuery $carQuery,
        private readonly UpdateCarRepository $updateCarRepository,
    ) {
    }

    public function handle(UpdateCarCommand $command): void
    {
        $car = $this->carQuery->getById($command->DTO->id);

        $car->status_id       = $command->DTO->statusId;
        $car->company_id      = $command->DTO->companyId;
        $car->vehicle_type_id = $command->DTO->vehicleTypeId;
        $car->code_1C         = $command->DTO->code1C;
        $car->number          = $command->DTO->number;
        $car->model           = $command->DTO->model;
        $car->cubature        = $command->DTO->cubature;
        $car->created_at      = $command->DTO->createdAt;

        $this->updateCarRepository->update($car);
    }
}
