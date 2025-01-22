<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Commands\ChangeTakeDateByInvoiceIdCommand;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;
use App\Module\Take\Events\OrderTakeDateChangedEvent;
use App\Module\Take\Models\OrderTake;
use Illuminate\Support\Facades\Log;

final readonly class ChangeTakeDateByInvoiceIdHandler
{
    public function __construct(
        private InvoiceQuery $orderLogisticsInfoQuery,
        private UpdateOrderTakeRepository $repository
    ) {
    }

    public function handle(ChangeTakeDateByInvoiceIdCommand $command): void
    {
        $invoice = $this->orderLogisticsInfoQuery->getById($command->invoiceId);

        /** @var OrderTake $take */
        $take = $invoice->takes->first();

        if (!$take) {
            return;
        }

        $oldTakeDate = $take->take_date;

        $take->take_date  = $command->newDate;
        $take->courier_id = null;

        if (!$take->isCompleted()) {
            $take->setTakeStatus(StatusType::ID_NOT_ASSIGNED);
        }

        $this->repository->update($take);

        Log::info("Дата забора накладной: $invoice->invoice_number изменен c: $oldTakeDate на: $take->take_date");

        event(new OrderTakeDateChangedEvent($invoice->id, $command->newDate, $command->periodId));
    }
}
