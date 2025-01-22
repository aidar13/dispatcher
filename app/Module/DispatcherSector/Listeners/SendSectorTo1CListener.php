<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\SendSectorTo1CCommand;
use App\Module\DispatcherSector\Events\DefaultSectorCreatedEvent;
use App\Module\DispatcherSector\Events\SectorCreatedEvent;

final class SendSectorTo1CListener
{
    public function handle(SectorCreatedEvent|DefaultSectorCreatedEvent $event): void
    {
        dispatch(new SendSectorTo1CCommand($event->sectorId));
    }
}
