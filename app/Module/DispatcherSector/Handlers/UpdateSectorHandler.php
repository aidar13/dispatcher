<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Helpers\PolygonHelper;
use App\Module\DispatcherSector\Commands\UpdateSectorCommand;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateSectorRepository;
use App\Module\DispatcherSector\Events\SectorUpdatedEvent;

final class UpdateSectorHandler
{
    public function __construct(
        private readonly SectorQuery $query,
        private readonly UpdateSectorRepository $repository,
    ) {
    }

    public function handle(UpdateSectorCommand $command): void
    {
        $sector = $this->query->getById($command->id);

        $sector->name                  = $command->DTO->name;
        $sector->dispatcher_sector_id  = $command->DTO->dispatcherSectorId;
        $sector->color                 = $command->DTO->color;
        $sector->coordinates           = json_encode($command->DTO->coordinates);
        $sector->polygon               = PolygonHelper::getPolygonFromCoordinates($command->DTO->coordinates);

        $this->repository->update($sector);

        event(new SectorUpdatedEvent($sector->id));
    }
}
