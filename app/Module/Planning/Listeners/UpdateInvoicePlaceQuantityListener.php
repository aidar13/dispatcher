<?php

declare(strict_types=1);

namespace App\Module\Planning\Listeners;

use App\Module\Planning\Commands\UpdateInvoicePlaceQuantityCommand;
use App\Module\Planning\Events\ContainerStatusUpdatedEvent;

final class UpdateInvoicePlaceQuantityListener
{
    public function handle(ContainerStatusUpdatedEvent $event): void
    {
        dispatch(new UpdateInvoicePlaceQuantityCommand($event->DTO->invoices));
    }
}
