<?php

declare(strict_types=1);

namespace App\Module\Planning\Listeners;

use App\Module\Planning\Commands\UpdateContainerInvoiceStatusesCommand;
use App\Module\Planning\Events\ContainerStatusUpdatedEvent;

final class UpdateContainerInvoiceStatusesListener
{
    public function handle(ContainerStatusUpdatedEvent $event): void
    {
        dispatch(new UpdateContainerInvoiceStatusesCommand($event->DTO));
    }
}
