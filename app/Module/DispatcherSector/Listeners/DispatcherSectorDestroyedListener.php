<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\DeleteSectorCommand;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\Events\DispatcherSectorDestroyedEvent;

final class DispatcherSectorDestroyedListener
{
    public function __construct(private readonly SectorQuery $query)
    {
    }

    public function handle(DispatcherSectorDestroyedEvent $event): void
    {
        $sectors = $this->query->getAllByDispatcherSectorIdAndIds($event->dispatcherSectorId);

        foreach ($sectors as $sector) {
            dispatch(new DeleteSectorCommand($sector->id));
        }
    }
}
