<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Helpers\PolygonHelper;
use App\Module\DispatcherSector\Commands\CreateSectorCommand;
use App\Module\DispatcherSector\Contracts\Repositories\CreateSectorRepository;
use App\Module\DispatcherSector\Events\SectorCreatedEvent;
use App\Module\DispatcherSector\Models\Sector;

final class CreateSectorHandler
{
    public function __construct(
        private readonly CreateSectorRepository $repository,
    ) {
    }

    public function handle(CreateSectorCommand $command): void
    {
        $sector                        = new Sector();
        $sector->name                  = $command->DTO->name;
        $sector->dispatcher_sector_id  = $command->DTO->dispatcherSectorId;
        $sector->color                 = $command->DTO->color;
        $sector->coordinates           = json_encode($command->DTO->coordinates);
        $sector->polygon               = PolygonHelper::getPolygonFromCoordinates($command->DTO->coordinates);

        $this->repository->create($sector);

        event(new SectorCreatedEvent($sector->id));
    }
}
