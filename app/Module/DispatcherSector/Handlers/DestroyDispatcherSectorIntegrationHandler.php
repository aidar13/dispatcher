<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\DestroyDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Events\DispatcherSectorDeletedIntegrationEvent;

final class DestroyDispatcherSectorIntegrationHandler
{
    public function handle(DestroyDispatcherSectorIntegrationCommand $command): void
    {
        event(new DispatcherSectorDeletedIntegrationEvent($command->dispatcherSectorId));
    }
}
