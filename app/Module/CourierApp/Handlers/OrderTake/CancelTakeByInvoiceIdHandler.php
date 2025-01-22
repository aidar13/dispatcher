<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\CancelTakeByInvoiceIdCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;
use Illuminate\Support\Facades\Log;

final class CancelTakeByInvoiceIdHandler
{
    public function __construct(
        private readonly InvoiceQuery $query,
        private readonly UpdateOrderTakeRepository $repository
    ) {
    }

    public function handle(CancelTakeByInvoiceIdCommand $command): void
    {
        $invoice = $this->query->getById($command->invoiceId);
        $take = $invoice?->take;

        if (!$take) {
            return;
        }

        $take->setTakeStatus(StatusType::ID_TAKE_CANCELED);
        $this->repository->update($take);

        Log::info('Take cancelled. Invoice number: ' . $invoice?->invoice_number);
    }
}
