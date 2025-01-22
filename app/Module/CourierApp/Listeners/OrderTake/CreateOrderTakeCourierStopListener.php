<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\OrderTake;

use App\Module\Courier\Commands\CreateCourierStopCommand;
use App\Module\CourierApp\Events\OrderTake\OrderTakeStatusChangedEvent;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Models\OrderTake;

final class CreateOrderTakeCourierStopListener
{
    public function __construct(
        private readonly OrderTakeQuery $query,
    ) {
    }

    public function handle(OrderTakeStatusChangedEvent $event): void
    {
        $orderTake = $this->query->getById($event->orderTakeId);

        if (!$orderTake->isStatusTaken()) {
            return;
        }

        dispatch(new CreateCourierStopCommand(
            $orderTake->id,
            OrderTake::class,
            $orderTake->courier_id,
        ));
    }
}
