<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers\Integration;

use App\Helpers\PolygonHelper;
use App\Module\DispatcherSector\Commands\Integration\UpdateDispatcherSectorCommand;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateDispatcherSectorRepository;

final class UpdateDispatcherSectorHandler
{
    public function __construct(
        private readonly DispatcherSectorQuery $dispatcherSectorQuery,
        private readonly UpdateDispatcherSectorRepository $updateDispatcherSectorRepository,
    ) {
    }

    public function handle(UpdateDispatcherSectorCommand $command): void
    {
        $dispatcherSector = $this->dispatcherSectorQuery->getById($command->DTO->id);

        $dispatcherSector->name        = $command->DTO->name;
        $dispatcherSector->city_id     = $command->DTO->cityId;
        $dispatcherSector->description = $command->DTO->description;
        $dispatcherSector->created_at  = $command->DTO->createdAt;
        $dispatcherSector->coordinates = $command->DTO->coordinates;
        $dispatcherSector->polygon     = PolygonHelper::getPolygonFromCoordinates(json_decode($command->DTO->coordinates));

        $this->updateDispatcherSectorRepository->update($dispatcherSector);

        // TODO: attach userIDs
    }
}
