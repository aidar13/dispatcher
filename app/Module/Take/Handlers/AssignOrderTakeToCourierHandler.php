<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Take\Commands\AssignCourierToOrderIn1CCommand;
use App\Module\Take\Commands\AssignCourierToTakeCargoCommand;
use App\Module\Take\Commands\AssignOrderTakeToCourierCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Events\OrderTakesAssignedToCourierEvent;
use App\Module\Take\Models\OrderTake;

final class AssignOrderTakeToCourierHandler
{
    public function __construct(
        private readonly OrderTakeQuery $query
    ) {
    }

    public function handle(AssignOrderTakeToCourierCommand $command): void
    {
        $orderTakes = $this->query->getByOrderId($command->orderId);

        if ($orderTakes->isEmpty()) {
            throw new \DomainException("Не найден заказ по orderId - {$command->orderId}");
        }

        /** @var OrderTake $orderTake */
        foreach ($orderTakes as $orderTake) {
            dispatch(new AssignCourierToTakeCargoCommand($orderTake->invoice_id, $command->courierId, $command->userId));
            dispatch(new AssignCourierToOrderIn1CCommand(
                $orderTake->invoice_id,
                $command->courierId,
                $orderTake->getOrderNumber(),
            ));
        }

        event(new OrderTakesAssignedToCourierEvent($command->orderId, $command->courierId));
    }
}
