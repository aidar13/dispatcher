<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\UpdateInvoiceDeliveryDateCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;

final class UpdateInvoiceDeliveryDateHandler
{
    public function __construct(
        private readonly InvoiceQuery $invoiceQuery,
        private readonly UpdateInvoiceRepository $updateInvoiceRepository
    ) {
    }

    public function handle(UpdateInvoiceDeliveryDateCommand $command): void
    {
        $invoice = $this->invoiceQuery->getById($command->id);

        $invoice->delivery_date = $command->date;

        $this->updateInvoiceRepository->update($invoice);
    }
}
