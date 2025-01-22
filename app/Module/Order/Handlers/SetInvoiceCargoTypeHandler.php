<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\SetInvoiceCargoTypeCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;

final class SetInvoiceCargoTypeHandler
{
    public function __construct(
        private readonly InvoiceQuery $invoiceQuery,
        private readonly UpdateInvoiceRepository $invoiceRepository
    ) {
    }

    public function handle(SetInvoiceCargoTypeCommand $command): void
    {
        $invoice = $this->invoiceQuery->getById($command->invoiceId);

        if (!$invoice) {
            return;
        }

        $invoice->setCargoType();

        $this->invoiceRepository->update($invoice);
    }
}
