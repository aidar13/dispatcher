<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\SetInvoiceSectorsCommand;
use App\Module\Order\Commands\SetReceiverDispatcherSectorCommand;
use App\Module\Order\Commands\SetSenderDispatcherSectorCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Events\InvoiceSectorsUpdatedEvent;

final readonly class SetInvoiceSectorsHandler
{
    public function __construct(
        private InvoiceQuery $invoiceQuery
    ) {
    }

    public function handle(SetInvoiceSectorsCommand $command): void
    {
        $invoice = $this->invoiceQuery->getById($command->invoiceId, ['id', 'receiver_id', 'order_id'], ['order:id,sender_id']);

        if (!$invoice) {
            return;
        }

        dispatch(new SetReceiverDispatcherSectorCommand($invoice->receiver_id));
        dispatch(new SetSenderDispatcherSectorCommand($invoice->order->sender_id));

        event(new InvoiceSectorsUpdatedEvent($invoice->id));
    }
}
