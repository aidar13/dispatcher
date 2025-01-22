<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners;

use App\Module\Order\Commands\SetInvoiceWaveCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;

final readonly class SetInvoiceWaveListener
{
    public function __construct(private InvoiceQuery $invoiceQuery)
    {
    }

    public function handle(OrderStatusCreatedEvent $event): void
    {
        $invoice = $this->invoiceQuery->getById($event->invoiceId);

        if (!in_array($event->code, RefStatus::WAVE_ASSIGNABLE_TO_INVOICE_STATUSES)) {
            return;
        }

        if ($event->code === RefStatus::CODE_CARGO_AWAIT_SHIPMENT && $invoice?->hasTransit()) {
            return;
        }

        dispatch(new SetInvoiceWaveCommand($event->invoiceId));
    }
}
