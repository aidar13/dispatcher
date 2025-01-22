<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\CreateDefaultSectorCommand;
use App\Module\DispatcherSector\Commands\CreateDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Events\DispatcherSectorCreatedEvent;

final class DispatcherSectorCreatedIntegrationListener
{
    public function handle(DispatcherSectorCreatedEvent $event): void
    {
        dispatch(new CreateDispatcherSectorIntegrationCommand($event->dispatcherSectorId));
        dispatch(new CreateDefaultSectorCommand($event->dispatcherSectorId));
    }
}
