<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\ChangeInvoiceTakeDateCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;

final readonly class ChangeInvoiceTakeDateHandler
{
    public function __construct(
        private InvoiceQuery $invoiceQuery,
        private UpdateInvoiceRepository $invoiceRepository
    ) {
    }

    public function handle(ChangeInvoiceTakeDateCommand $command): void
    {
        $invoice = $this->invoiceQuery->getById($command->invoiceId);

        $invoice->take_date = $command->takeDate;
        $invoice->period_id = $command->periodId;
        $this->invoiceRepository->update($invoice);
    }
}
