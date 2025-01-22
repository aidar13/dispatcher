<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\SetOrderTakeInfoWaitListStatusCommand;
use App\Module\CourierApp\Commands\OrderTake\SetTakeInfoWaitListStatusCommand;
use App\Module\CourierApp\Events\OrderTake\OrderTakeInfoWaitListStatusChangedInfoEvent;
use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Status\Contracts\Queries\OrderStatusQuery;

final class SetOrderTakeInfoWaitListStatusHandler
{
    public function __construct(
        private readonly OrderQuery $orderQuery,
        private readonly OrderStatusQuery $orderStatusQuery
    ) {
    }

    public function handle(SetOrderTakeInfoWaitListStatusCommand $command): void
    {
        $order     = $this->orderQuery->getById($command->orderId);
        $takeInfos = $order?->orderTakes;

        $takeInfo = $takeInfos->first();

        if ($takeInfo && $this->checkStatusCodeExistInPeriod($takeInfo->invoice_id, $command->DTO->statusCode)) {
            return;
        }

        foreach ($takeInfos as $takeInfo) {
            dispatch(new SetTakeInfoWaitListStatusCommand($takeInfo->id, $command->DTO));
        }

        event(new OrderTakeInfoWaitListStatusChangedInfoEvent($order?->id, $command->DTO));
    }

    private function checkStatusCodeExistInPeriod(int $invoiceId, int $code): bool
    {
        $lastStatus = $this->orderStatusQuery->getLastByInvoiceId($invoiceId);

        return $lastStatus && $lastStatus->canCreateWaitListStatus($code);
    }
}
