<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Take\Commands\SetStatusToTakeByInvoiceCommand;
use App\Module\Take\Commands\SetStatusToTakeCommand;

final readonly class SetStatusToTakeByInvoiceHandler
{
    public function __construct(
        private InvoiceQuery $query,
    ) {
    }

    public function handle(SetStatusToTakeByInvoiceCommand $command): void
    {
        $invoice = $this->query->getById($command->DTO->invoiceId);

        if (!$invoice || !$invoice->take) {
            return;
        }

        dispatch(new SetStatusToTakeCommand(
            $invoice->take->id,
            $command->DTO->statusId
        ));
    }
}
