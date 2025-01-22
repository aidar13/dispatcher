<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\DeleteSectorCommand;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveSectorRepository;
use App\Module\DispatcherSector\Events\SectorDestroyedEvent;

final class DeleteSectorHandler
{
    public function __construct(
        private readonly SectorQuery $query,
        private readonly RemoveSectorRepository $repository,
    ) {
    }

    public function handle(DeleteSectorCommand $command): void
    {
        $sector = $this->query->getById($command->id);

        $this->repository->remove($sector);

        event(new SectorDestroyedEvent($sector->id));
    }
}
