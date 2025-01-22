<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\UpdateInvoiceSlaCommand;
use App\Module\Order\Events\InvoiceCreatedEvent;
use Illuminate\Bus\Dispatcher;

final class SetInvoiceSlaDateListener
{
    public function __construct(
        private readonly Dispatcher $dispatcher
    ) {
    }

    public function handle(InvoiceCreatedEvent $event): void
    {
        $this->dispatcher->dispatch(
            new UpdateInvoiceSlaCommand($event->invoiceId)
        );
    }
}
