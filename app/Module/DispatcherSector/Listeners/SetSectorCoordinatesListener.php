<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\SetSectorCoordinatesCommand;
use App\Module\DispatcherSector\Events\SectorCreatedEvent;
use App\Module\DispatcherSector\Events\SectorUpdatedEvent;

final class SetSectorCoordinatesListener
{
    public function handle(SectorCreatedEvent|SectorUpdatedEvent $event): void
    {
        dispatch(new SetSectorCoordinatesCommand($event->sectorId));
    }
}
