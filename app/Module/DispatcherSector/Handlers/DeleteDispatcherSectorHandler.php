<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\DeleteDispatcherSectorCommand;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveDispatcherSectorRepository;
use App\Module\DispatcherSector\Events\DispatcherSectorDestroyedEvent;

final class DeleteDispatcherSectorHandler
{
    public function __construct(
        private readonly DispatcherSectorQuery $dispatcherSectorQuery,
        private readonly RemoveDispatcherSectorRepository $repository,
    ) {
    }

    public function handle(DeleteDispatcherSectorCommand $command): void
    {
        $dispatcherSector = $this->dispatcherSectorQuery->getById($command->dispatchersSectorId);

        $this->repository->remove($dispatcherSector);

        event(new DispatcherSectorDestroyedEvent($dispatcherSector->id));
    }
}
