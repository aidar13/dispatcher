<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners\Integration;

use App\Module\Order\Commands\Integration\UpdateInvoiceSectorsInCabinetCommand;
use App\Module\Order\Events\InvoiceSectorsUpdatedEvent;

final class InvoiceSectorsUpdatedListener
{
    public function handle(InvoiceSectorsUpdatedEvent $event): void
    {
        dispatch(new UpdateInvoiceSectorsInCabinetCommand($event->id));
    }
}
