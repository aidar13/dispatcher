<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\SetInvoiceSectorsCommand;
use App\Module\Order\Events\InvoiceCreatedEvent;

final readonly class SetInvoiceSectorsListener
{
    public function handle(InvoiceCreatedEvent $event): void
    {
        dispatch(new SetInvoiceSectorsCommand($event->invoiceId));
    }
}
