<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\UpdateDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Events\DispatcherSectorUpdatedEvent;

final class DispatcherSectorUpdatedIntegrationListener
{
    public function handle(DispatcherSectorUpdatedEvent $event): void
    {
        dispatch(new UpdateDispatcherSectorIntegrationCommand($event->dispatcherSectorId));
    }
}
