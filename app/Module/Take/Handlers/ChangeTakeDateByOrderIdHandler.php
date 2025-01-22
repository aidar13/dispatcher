<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Take\Commands\ChangeTakeDateByInvoiceIdCommand;
use App\Module\Take\Commands\ChangeTakeDateByOrderIdCommand;
use App\Module\Take\Events\ChangedTakeDateByOrderEvent;

final readonly class ChangeTakeDateByOrderIdHandler
{
    public function __construct(
        private OrderQuery $orderQuery
    ) {
    }

    public function handle(ChangeTakeDateByOrderIdCommand $command): void
    {
        $order = $this->orderQuery->getById($command->DTO->orderId);

        foreach ($order->invoices as $invoice) {
            if (!$invoice->isCanceled()) {
                dispatch(new ChangeTakeDateByInvoiceIdCommand(
                    $invoice->id,
                    $command->DTO->newDate,
                    $command->DTO->periodId
                ));
            }
        }

        event(new ChangedTakeDateByOrderEvent($command->DTO));
    }
}
