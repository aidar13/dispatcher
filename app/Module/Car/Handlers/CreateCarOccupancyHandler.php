<?php

declare(strict_types=1);

namespace App\Module\Car\Handlers;

use App\Module\Car\Commands\CreateCarOccupancyCommand;
use App\Module\Car\Contracts\Queries\CarOccupancyQuery;
use App\Module\Car\Contracts\Repositories\CreateCarOccupancyRepository;
use App\Module\Car\Models\CarOccupancy;

final class CreateCarOccupancyHandler
{
    public function __construct(
        private readonly CarOccupancyQuery $query,
        private readonly CreateCarOccupancyRepository $repository,
    ) {
    }

    public function handle(CreateCarOccupancyCommand $command): void
    {
        $carOccupancy = $this->query->getById($command->DTO->id);

        if ($carOccupancy) {
            return;
        }

        $carOccupancy                        = new CarOccupancy();
        $carOccupancy->id                    = $command->DTO->id;
        $carOccupancy->car_occupancy_type_id = $command->DTO->carOccupancyTypeId;
        $carOccupancy->car_id                = $command->DTO->carId;
        $carOccupancy->user_id               = $command->DTO->userId;
        $carOccupancy->type_id               = $command->DTO->courierWorkTypeId;
        $carOccupancy->client_id             = $command->DTO->clientId;
        $carOccupancy->client_type           = $command->DTO->clientType;
        $carOccupancy->created_at            = $command->DTO->createdAt;

        $this->repository->create($carOccupancy);
    }
}
