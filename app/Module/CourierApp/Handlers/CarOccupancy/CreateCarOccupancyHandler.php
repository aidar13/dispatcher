<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\CarOccupancy;

use App\Module\Car\Contracts\Repositories\CreateCarOccupancyRepository;
use App\Module\Car\Models\CarOccupancy;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\CourierApp\Commands\CarOccupancy\CreateCarOccupancyCommand;

final class CreateCarOccupancyHandler
{
    public function __construct(
        private readonly CreateCarOccupancyRepository $carOccupancyRepository,
        private readonly CourierQuery $query,
    ) {
    }

    public function handle(CreateCarOccupancyCommand $command): void
    {
        $courier = $this->query->getByUserId($command->userId);

        $carOccupancy                        = new CarOccupancy();
        $carOccupancy->car_occupancy_type_id = $command->DTO->carOccupancyTypeId;
        $carOccupancy->type_id               = $command->DTO->typeId;
        $carOccupancy->client_id             = $command->DTO->clientId;
        $carOccupancy->client_type           = $command->DTO->clientType;
        $carOccupancy->user_id               = $command->userId;
        $carOccupancy->car_id                = $courier->car_id;

        $this->carOccupancyRepository->create($carOccupancy);
    }
}
