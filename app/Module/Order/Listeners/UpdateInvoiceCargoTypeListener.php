<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\SetInvoiceCargoTypeCommand;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;

final class UpdateInvoiceCargoTypeListener
{
    public function handle(OrderStatusCreatedEvent $event): void
    {
        if ($event->code !== RefStatus::CODE_CARGO_AWAIT_SHIPMENT) {
            return;
        }

        dispatch(new SetInvoiceCargoTypeCommand($event->invoiceId));
    }
}
