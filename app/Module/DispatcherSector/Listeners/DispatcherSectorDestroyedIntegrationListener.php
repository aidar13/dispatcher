<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\DestroyDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Events\DispatcherSectorDestroyedEvent;

final class DispatcherSectorDestroyedIntegrationListener
{
    public function handle(DispatcherSectorDestroyedEvent $event): void
    {
        dispatch(new DestroyDispatcherSectorIntegrationCommand($event->dispatcherSectorId));
    }
}
