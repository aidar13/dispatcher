<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\SetInvoiceWaitListIdCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;

final class SetInvoiceWaitListIdHandler
{
    public function __construct(
        private readonly InvoiceQuery $invoiceQuery,
        private readonly UpdateInvoiceRepository $invoiceRepository
    ) {
    }

    public function handle(SetInvoiceWaitListIdCommand $command): void
    {
        $invoice = $this->invoiceQuery->getById($command->invoiceId);

        if (!$invoice) {
            return;
        }

        $invoice->wait_list_id = $command->waitListId;

        $this->invoiceRepository->update($invoice);
    }
}
