<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Helpers\PolygonHelper;
use App\Module\DispatcherSector\Commands\CreateDefaultSectorCommand;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\CreateSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateDispatcherSectorRepository;
use App\Module\DispatcherSector\Events\DefaultSectorCreatedEvent;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;

final class CreateDefaultSectorHandler
{
    public function __construct(
        private readonly CreateSectorRepository $sectorRepository,
        private readonly DispatcherSectorQuery $query,
        private readonly UpdateDispatcherSectorRepository $repository,
    ) {
    }

    public function handle(CreateDefaultSectorCommand $command): void
    {
        $dispatcherSector = $this->query->getById($command->dispatcherSectorId);

        $sectorId = $this->createSector($dispatcherSector);

        $dispatcherSector->default_sector_id = $sectorId;
        $this->repository->update($dispatcherSector);

        event(new DefaultSectorCreatedEvent($sectorId));
    }

    private function createSector(DispatcherSector $dispatcherSector): int
    {
        $sector                       = new Sector();
        $sector->dispatcher_sector_id = $dispatcherSector->id;
        $sector->name                 = 'Неизвестный сектор';
        $sector->color                = 'F50C0C';
        $sector->coordinates          = json_encode(null);
        $sector->polygon              = PolygonHelper::getPolygonFromCoordinates(null);
        $sector->latitude             = $dispatcherSector->city->latitude;
        $sector->longitude            = $dispatcherSector->city->longitude;

        $this->sectorRepository->create($sector);

        return $sector->id;
    }
}
